<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\adapters\AzureBlobFilesystem;
use app\models\BackupLog;
use app\models\Schedule;
use BackupManager\Compressors\CompressorProvider;
use BackupManager\Compressors\GzipCompressor;
use BackupManager\Config\Config;
use BackupManager\Databases\DatabaseProvider;
use BackupManager\Databases\MysqlDatabase;
use BackupManager\Filesystems\Awss3Filesystem;
use BackupManager\Filesystems\Destination;
use BackupManager\Filesystems\DropboxFilesystem;
use BackupManager\Filesystems\FilesystemProvider;
use BackupManager\Filesystems\FtpFilesystem;
use BackupManager\Filesystems\GcsFilesystem;
use BackupManager\Filesystems\LocalFilesystem;
use BackupManager\Filesystems\RackspaceFilesystem;
use BackupManager\Filesystems\SftpFilesystem;
use BackupManager\Manager;
use yii\console\Controller;
use yii\db\Exception;

/**
 * This command is the entry point for backup process
 *
 *
 * @author Prabath Perera <dryize@gmail.com>
 */
class CronController extends Controller
{


    protected $sensivity = 15*60;
    protected $filesystems = null;
    protected $compressors = null;


    public function actionIndex()
    {
        if(!\Yii::$app->mutex->acquire('cron_process')){
            echo "Lock failed. Exiting";
            return 0;
        }

        $start_time = time();
        $last_time = \Yii::$app->cache->get('cron_last');

        $this->sensivity = 600;
        if($last_time !== false) {
            $this->sensivity = ceil(($last_time - $start_time) / 2);
        }

        \Yii::$app->cache->set('cron_last', $start_time);

        $start = date('Y-m-d H:i:s', $start_time);

        echo "Lock acquired {$start}\n";


        $this->filesystems = new FilesystemProvider(new Config(\Yii::$app->params['stores']));
        $this->filesystems->add(new LocalFilesystem);
        $this->filesystems->add(new AzureBlobFilesystem);
        $this->filesystems->add(new Awss3Filesystem);
        $this->filesystems->add(new GcsFilesystem);
        $this->filesystems->add(new DropboxFilesystem);
        $this->filesystems->add(new FtpFilesystem);
        $this->filesystems->add(new RackspaceFilesystem);
        $this->filesystems->add(new SftpFilesystem);

        $this->compressors = new CompressorProvider;
        $this->compressors->add(new GzipCompressor);


        \Yii::$app->cache->set('last_run',$start);

        $schedules = Schedule::findAll(['status' => 'ACTIVE']);
        foreach ($schedules as $schedule){
            $stime = strtotime($schedule->next);
            if(true || $stime < $start_time || ($stime - $start_time) < $this->sensivity){
                echo "Processing {$schedule->id} {$schedule->next}  {$start_time} {$stime} => ";
                $this->run_backup($schedule);
            }
        }

        echo "Completed\n\n";
    }


    protected function run_backup(Schedule $schedule){

        $filename =  date('Ymd_His') . '.sql';


        $host = $schedule->host0;

        $log = new BackupLog();
        $log->schedule = $schedule->id;
        $log->schema = $schedule->schema;
        $log->hash = '';
        $log->artifact = $filename . '.gz';
        $log->save();


        $databases = new DatabaseProvider(new Config([
            $host->tag => [
                'type' => 'mysql',
                'host' => $host->host,
                'port' => $host->port,
                'user' => $host->username,
                'pass' => $host->password,
                'database' => $schedule->schema,
                'singleTransaction' => true,
                'ignoreTables' => [],
            ]
        ]));
        $databases->add(new MysqlDatabase);

        $manager = new Manager($this->filesystems, $databases, $this->compressors);

        try {

            $manager
                ->makeBackup()
                ->run($host->tag, [
                    new Destination($schedule->destination, $filename)
                ], 'gzip');

           // $log->hash = sha1_file($destination);
            $log->status = 'COMPLETED';

            echo "Backup created [{$log->hash}]\n";

            //calculate next run
            if($schedule->type == 'SCHEDULED'){
                $schedule->status = 'COMPLETED';
            }else{
                $difference = 0;
                switch($schedule->frequency){
                    case 'HOURLY': $difference = 1;
                        break;
                    case 'EVERY_4HOUR': $difference = 4;
                        break;
                    case 'DAILY': $difference = 24;
                        break;
                }
                $next = time() + 3600 * $difference;
                //$schedule->next = date('Y-m-d H:i:s', $next);
            }

            $schedule->save();

            //check retention

            $store = $this->filesystems->get($schedule->destination);
            while($schedule->getBackupLogs()->count() > $schedule->retention){
                $todelete = $schedule->getBackupLogs()->addOrderBy(['id' => SORT_ASC])->one();
                echo "Removing {$todelete->artifact}\n";
                if($store->has($todelete->artifact)) {
                    $store->delete($todelete->artifact);
                }
                $todelete->delete();
            }

            return true;
        }catch(Exception $ex){
            $log->status = 'FAILED';
            echo "Backup failed\n";
            return false;
        }finally{
            $log->save();
        }

    }
}
