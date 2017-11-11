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
        $start_time = time();


        $schedules = Schedule::findAll(['status' => 'ACTIVE']);

        foreach ($schedules as $schedule){
            $stime = strtotime($schedule->next);
            echo "Processing {$schedule->id} {$schedule->next}  {$start_time} {$stime}\n";
            if($stime < $start_time || ($stime - $start_time) < $this->correction){
                $this->run_backup($schedule);
            }
        }

//        $process = new Process('ls -lsa');
//        $process->run();
//
//// executes after the command finishes
//        if (!$process->isSuccessful()) {
//            throw new ProcessFailedException($process);
//        }
//
//        echo $process->getOutput();

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
        $log->artifact = $destination;
        $log->save();

        try {
            $process->mustRun();

            if (!$process->isSuccessful()) {
                throw new \Exception('Process Failed');
            }
            $log->hash = sha1_file($destination);
            $log->status = 'COMPLETED';
            
            //check retention
            return true;
        }catch(Exception $ex){
            $log->status = 'FAILED';
            return false;
        }finally{
            $log->save();
        }

    }
}
