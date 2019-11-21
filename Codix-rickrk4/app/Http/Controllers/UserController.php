<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Comic as AppComic;
use App\Directory;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Resources\Comic;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserDetail;
use App\Series as AppSeries;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index(){
        return new UserCollection(User::all());
    }

    public function show($id){
        return new UserDetail( User::findOrFail($id) );
    }

    public function store(Request $request){
        (new RegisterController)->register($request);
        return response('', 200);
    }

    public function update(Request $request, $id) {
        error_log('new rule');
        $user = User::findOrFail($id);
        error_log("update user: $user->name");
        if($rules = $request->input('rules'))
            foreach ($rules as $rule) {
                error_log(json_encode($rule));
                $resource = (function () use ($rule)
                {
                    switch ($rule['type'])
                    {
                        case 'comic':
                            return array_key_exists('id', $rule) ? AppComic::findOrFail($rule['id']) : AppComic::where('title', $rule['name'])->firstOrFail();
                        case 'series':
                            return array_key_exists('id', $rule) ? AppSeries::findOrFail($rule['id']) : AppSeries::where('name', $rule['name'])->firstOrFail();
                        case 'directory':
                            return array_key_exists('id', $rule) ? Directory::findOrFail($rule['id']) : Directory::where('name', $rule['name'])->firstOrFail();
                        case 'collection':
                            return array_key_exists('id', $rule) ? Collection::findOrFail($rule['id']) : Collection::where('name', $rule['name'])->firstOrFail();
                        default:
                            abort('Resource not found', 500);
                    }
                })();

                error_log("add rule to user: $user->name, of type: ".$rule['type']." to id: $resource->id");

                $user->comics()->sync([$resource->id, ['allow' => $rule['allow']]]);

            }
    }

    public function getToken(){
        return json_encode(Session::token());
    }

    public function addRule(Request $request, $id)
    {
        error_log('new rule');
        $user = User::findOrFail($id);
        error_log("update user: $user->name");

        if($rules = $request->input('rules'))
            foreach ($rules as $rule) {
                error_log(json_encode($rule));
                $resource = (function () use ($rule)
                {
                    switch ($rule['type'])
                    {
                        case 'comic':
                            return array_key_exists('id', $rule) ? AppComic::findOrFail($rule['id']) : AppComic::where('title', $rule['name'])->firstOrFail();
                        case 'series':
                            return array_key_exists('id', $rule) ? AppSeries::findOrFail($rule['id']) : AppSeries::where('name', $rule['name'])->firstOrFail();
                        case 'directory':
                            return array_key_exists('id', $rule) ? Directory::findOrFail($rule['id']) : Directory::where('name', $rule['name'])->firstOrFail();
                        case 'collection':
                            return array_key_exists('id', $rule) ? Collection::findOrFail($rule['id']) : Collection::where('name', $rule['name'])->firstOrFail();
                        default:
                            abort('Resource not found', 500);
                    }
                })();

                error_log("add rule to user: $user->name, of type: ".$rule['type']." to id: $resource->id");
                $user->comics()->sync([$resource->id, ['allow' => $rule['allow']]]);
            }
        return response(json_encode("ok"), 200);
    }

    public function deleteRule(Request $request, $id){
        error_log('delete rule');
        $user = User::findOrFail($id);
        error_log("update user: $user->name");

        if($rules = $request->input('rules'))
            foreach($rules as $rule)
            {
                error_log(json_encode($rule));
                $resource = (function () use ($rule)
                {
                    switch ($rule['type'])
                    {
                        case 'comic':
                            return array_key_exists('id', $rule) ? AppComic::findOrFail($rule['id']) : AppComic::where('title', $rule['name'])->firstOrFail();
                        case 'series':
                            return array_key_exists('id', $rule) ? AppSeries::findOrFail($rule['id']) : AppSeries::where('name', $rule['name'])->firstOrFail();
                        case 'directory':
                            return array_key_exists('id', $rule) ? Directory::findOrFail($rule['id']) : Directory::where('name', $rule['name'])->firstOrFail();
                        case 'collection':
                            return array_key_exists('id', $rule) ? Collection::findOrFail($rule['id']) : Collection::where('name', $rule['name'])->firstOrFail();
                        default:
                            abort('Resource not found', 500);
                    }
                })();
                error_log("detach rule to user: $user->name, of type: ".$rule['type']." to id: $resource->id");
                $user->comics()->detach($resource->id);
            }

        return response(json_encode("ok"), 200);
    }
}
