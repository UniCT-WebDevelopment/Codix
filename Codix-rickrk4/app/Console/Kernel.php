<?php

namespace App\Console;

use App\Image;
use App\Jobs\ScannerJob;
use App\JobStatus;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function(){
            $query = DB::table('active_comics')->where('updated_at', '<=' , Carbon::now()->subDay());
//            $query = DB::table('active_comics'); // just for test
                    foreach($query->get() as $old_comic){
                        Image::where('comic_id', $old_comic->id)->delete();
                        File::deleteDirectory(gallery_dir.$old_comic->id);
                        error_log("cancellato ".gallery_dir."/$old_comic->id");
                    }

                    $query->delete();
                    error_log("old comics deleted");
        })
//        ->everyMinute(); //just for test
        ->daily();

/*
        $scan = $schedule->job(JobStatus::find(ScannerJob::make()), 'scan');
        if(!ScannerJob::started())
        switch (config('settings.scan_frequency.value')) {
            case 'daily':
                error_log('dealy');
                $scan->daily();
                break;
            case 'weekly':
                error_log('weekly');
                $scan->weekly();
                break;
            case 'monthly':
                error_log('monthly');
                $scan->monthly();
                break;
        }
/*
        $schedule->job(JobStatus::find(ScannerJob::make()), 'scan')->everyMinute();
*/

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
