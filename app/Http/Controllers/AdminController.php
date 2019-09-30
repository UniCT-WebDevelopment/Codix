<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Jobs\ScannerJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Imtigger\LaravelJobStatus\JobStatus;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }


    public function index()
    {
        Log::info('Request options');

        $options = config('settings');
        $settings = array();
        foreach ($options as $key => $value)
            if(!is_array($value))
                array_push($settings, ['name' => $key, 'value' => $value]);

        return ['data' => $settings];
    }

    public function show(Request $request, $option){
        Log::info("request option: $option");

        $settings = config("settings.$option") ?: abort(404, 'Not found');
        $response = array();
        foreach ($settings as $key => $value) {
            $value['name'] = $key;
            array_push($response, $value);
        }
        return ['data' => $response];
    }

    public function update(Request $request, $option)
    {
        $value = $request->input('value');
        Log::info("update option $option to $value");

        config(["settings.$option" => $value]);
        if($fp = fopen(base_path() .'/config/settings.php' , 'w')){
            fwrite($fp, '<?php return ' . var_export(config('settings'), true) . ';');
            fclose($fp);
        }

        return json_decode(config("settings.$option"));

        if(is_bool($value))
            return $value ? 1 : 0;
        else
            return config("settings.$option");

    }

    public function scan(){

        Log::info("avvio nuova scansione");

        if(!Directory::where('path', config('settings.comics_dir'))->exists())
            Directory::create(['path' => config('settings.comics_dir'),'name'=>'/']);

        $jobStatusId = ScannerJob::make();
        return $jobStatusId;
    }

    public function scanJob($id = null){
        if(is_null($id)){
            return ScannerJob::started() ? ScannerJob::make() : 0;
        }else
            return \App\JobStatus::findOrFail($id);
    }

    public function scanStarted(){
        return ScannerJob::started();
    }

    public function scanId() {
        return ScannerJob::scanId();
    }

    public function jobStatus($id) {
        return \App\JobStatus::findOrFail($id);
    }


    public function forceRoload(){
        DB::table(config('queue.connections.database.table'))->truncate();
    }
}
