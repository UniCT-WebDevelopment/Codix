<?php

namespace App\Libreries;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Imtigger\LaravelJobStatus\Trackable;

abstract class SingletonTrackableJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected function __construct()
    {
        $this->prepareStatus();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    abstract public function handle();
    abstract protected function factory();
    public static function make()
    {
        if(DB::table(config('queue.connections.database.table'))->where('queue','scan')->doesntExist()){

            $job = factory();
            self::dispatch($job)->onQueue('scan');
            config(["settings.jobKey" => $job->getJobStatusId()+1]);
            if($fp = fopen(base_path() .'/config/settings.php' , 'w')){
                fwrite($fp, '<?php return ' . var_export(config('settings'), true) . ';');
                fclose($fp);
            }
            //return 'processo avviato';
        }
        else {
            //return "Processo già in corso, attendere che finisca prima di rilanciarlo";
        }

        return config("settings.jobKey");
    }

    public function failed(Exception $exception)
    {
        DB::table(config('queue.connections.database.table'))->truncate();
    }
}
