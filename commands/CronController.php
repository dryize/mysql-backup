<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\BackupLog;
use app\models\Schedule;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
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


    protected $correction = 15*60;
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        if(!\Yii::$app->mutex->acquire('cron_process')){
            echo "Lock failed. Exiting";
            return 0;
        }
        echo "Lock acquired\n";

        $start_time = time();

        echo date('Y-m-d H:i:s', $start_time);

        $schedules = Schedule::findAll(['status' => 'ACTIVE']);
        foreach ($schedules as $schedule){
            $stime = strtotime($schedule->next);
            if($stime < $start_time || ($stime - $start_time) < $this->correction){
                echo "Processing {$schedule->id} {$schedule->next}  {$start_time} {$stime} => ";
                $this->run_backup($schedule);
            }
        }

        echo "Completed\n\n";
    }


    protected function run_backup(Schedule $schedule){

        if(!file_exists($schedule->destination)){
            mkdir($schedule->destination);
        }

        $destination = $schedule->destination . '/' . time() . '.sql';

        $host = $schedule->host0;
        $cmd = "mysqldump -h {$host->host} -P {$host->port} -u {$host->username} -p{$host->password} {$schedule->schema} --single-transaction --result-file $destination";
        $process = new Process($cmd);

        $log = new BackupLog();
        $log->schedule = $schedule->id;
        $log->schema = $schedule->schema;
        $log->hash = '';
        $log->artifact = $destination;
        $log->save();

        try {
            $process->mustRun();

            if (!$process->isSuccessful()) {
                throw new \Exception('Process Failed');
            }
            $log->hash = sha1_file($destination);
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
                $schedule->next = date('Y-m-d H:i:s', $next);
            }

            $schedule->save();

            //check retention
            while($schedule->getBackupLogs()->count() > $schedule->retention){
                $todelete = $schedule->getBackupLogs()->addOrderBy(['id' => SORT_ASC])->one();
                echo "Removing {$todelete->artifact}\n";
                unlink($todelete->artifact);
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
