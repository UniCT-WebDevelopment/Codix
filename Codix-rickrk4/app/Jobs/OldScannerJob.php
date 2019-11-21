<?php

namespace App\Jobs;

use App\Comic;
use App\Directory;
use App\Libreries\Chain\Chain;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Imtigger\LaravelJobStatus\Trackable;
use wapmorgan\UnifiedArchive\UnifiedArchive;

class ScannerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    private $scraper;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected function __construct()
    {
        $this->prepareStatus();
        $scrapers = config('settings.scrapers');
        $this->scraper = new Chain(\config('settings.scrapers'));
    }

    protected function write_config($file){
        if($fp = fopen(base_path() ."/config/$file.php" , 'w')){
            fwrite($fp, '<?php return ' . var_export(config($file), true) . ';');
            fclose($fp);
        }
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($dir = null){
        if(is_null($dir))
            $dir = Directory::where('path',config('settings.comics_dir'))->first();
        if(is_dir($dir->path)){
            $files = array_slice(scandir($dir->path),2);
            foreach($files as $file)
            {
                $this->setOutput(['file' => $file]);
                $file = $dir->path.'/'.$file;
                if(is_dir($file)){

                    if(!($directory = Directory::where('path',$file)->first())){
                        $dir->directories()->save(($directory = Directory::create(['name' =>  pathinfo($file)['basename'], 'path' => $file])));
                        handle($directory);
                        $directory->cover_path = $dir->comics()->first()->cover_path;
                    }else
                        handle($directory);
                } else {
                    if(!Comic::where('file_path',$file)->exists() && ($comic = (new Chain(config('settings.scrapers')))->call($file))){
                        $dir->comics()->save($comic);
                    }
                }

            }
        }
        /*
        $max = 20;
        $this->setProgressMax($max);

        for ($i = 0; $i <= $max; $i += 1) {
            config(["tests.progress" => "$i"]);
            $this->write_config('tests');
            /*
            if($fp = fopen(base_path() ."/config/$file.php" , 'w')){
                fwrite($fp, '<?php return ' . var_export(config($file), true) . ';');
                fclose($fp);
            }

            sleep(1); // Some Long Operations
            $this->setProgressNow($i);
        }

        $this->setOutput(['total' => $max]);
        */
    }

    public static function make()
    {
        if(DB::table(config('queue.connections.database.table'))->where('queue','scan')->doesntExist()){

            $job = new self();
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
