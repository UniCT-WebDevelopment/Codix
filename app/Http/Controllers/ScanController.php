<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Jobs\ScannerJob;
use App\JobStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScanController extends Controller
{
    public function scan(){

        Log::info("avvio nuova scansione");
        Directory::firstOrCreate(['path' => config('settings.comics_dir'),'name'=>'/']);

        $jobStatusId = ScannerJob::make();
        return $jobStatusId;
    }

    public function scanJob($id = null){
        if(is_null($id)){
            return ScannerJob::started() ? ScannerJob::make() : 0;
        }else
            return JobStatus::findOrFail($id);
    }

    public function scanStarted(){
        return ScannerJob::started();
    }

    public function scanId() {
        return ScannerJob::scanId();
    }

    public function jobStatus($id) {
        return JobStatus::findOrFail($id);
    }


    public function forceRoload(){
        DB::table(config('queue.connections.database.table'))->truncate();
    }
}
