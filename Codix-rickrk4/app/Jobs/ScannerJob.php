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

    protected $scrapers;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected function __construct()
    {
        $this->prepareStatus();
        $this->scrapers = new Chain(config('settings.scrapers'));
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
            $dir = Directory::firstOrCreate(['name' => '/', 'path' => config('settings.comics_dir')]);

        if(is_dir($dir->path)){
            $files = array_slice(scandir($dir->path),2);
            foreach($files as $file){
                $this->setOutput(['file' => $file]);
                error_log("examinate file: $file");
                $file = $dir->path.'/'.$file;

                if(is_dir($file)){

                    if(!($directory = Directory::where('path',$file)->first())){
                        $dir->directories()->save(($directory = Directory::create(['name' =>  pathinfo($file)['basename'], 'path' => $file])));

                        $this->handle($directory);
                        if(is_null($directory->cover_id)){

                            if( ($child = $directory->comics()->first()) || ($child = $directory->directories()->first())){
                                $directory->cover_id = $child->cover_id;
                                $directory->save();
                            }else
                                $directory->delete();
                        }
                    }else
                        $this->handle($directory);
                } else {
                    if(!Comic::where('file_path',$file)->exists() && ($comic = (new Chain(config('settings.scrapers')))->call($file))){
                        $dir->comics()->save($comic);
                    }
                }
            }
        }
    }

    public static function started(){
        return DB::table(config('queue.connections.database.table'))->where('queue','scan')->exists();
    }

    public static function scanId() {
        $jobId = self::started()
                    ? DB::table('job_statuses')->where('job_id', DB::table('jobs')->first()->id)->first()->id
                    : 0;
        return $jobId;
    }

    public static function make()
    {
        if(DB::table(config('queue.connections.database.table'))->where('queue','scan')->doesntExist())

        {
            error_log('Aggiorno il filesystem');
            $job = new self();
            self::dispatch($job)->onQueue('scan');

/*
            config(["settings.jobKey" => $job->getJobStatusId()+1]);
            if($fp = fopen(base_path() .'/config/settings.php' , 'w')){
                fwrite($fp, '<?php return ' . var_export(config('settings'), true) . ';');
                fclose($fp);
            }
*/

            sleep(1);
            do{
                $jobId = $job->getJobStatusId();
            }while (is_null($job));
            error_log("Job $jobId avviato");
            return $jobId + 1;
        }
        else {
            $jobId =  DB::table('job_statuses')->where('job_id', DB::table('jobs')->first()->id)->first();
            error_log("Job $jobId già in esecuzione");
            return $jobId;
        }


        return config("settings.jobKey");
    }

    public function failed(Exception $exception)
    {
        DB::table(config('queue.connections.database.table'))->truncate();
    }
}
