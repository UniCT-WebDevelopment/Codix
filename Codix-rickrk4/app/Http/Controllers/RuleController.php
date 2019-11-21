<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Comic;
use App\Directory;
use App\Series;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        error_log( json_encode( $request->input('rules')));

        foreach ($request->input('rules') as $rule) {
            error_log(json_encode($rule));
            $user = User::findOrFail($rule['userId']);

            $resource = (function () use ($rule, $user)
                {
                    switch ($rule['type']) {
                        case 'comic':
                            $user->comics()->attach(
                                (
                                 is_numeric($rule['name'])
                                 //array_key_exists('resource_id', $rule)
                                 ? Comic::findOrFail($rule['name'])
                                 : Comic::where('title',$rule['name'])->firstOrFail()
                                )->id
                                ,['allow' => $rule['allow']]);

                            return array_key_exists('resource_id', $rule)
                                    ? Comic::findOrFail($rule['resurce_id'])
                                    : Comic::where('title', $rule['name'])->firstOrFail();
                        case 'series':
                            $user->series()->attach((
                                array_key_exists('resource_id', $rule)
                                ? Series::findOrFail($rule['resource_id'])
                                : Series::where('name', $rule['name'])->firstOrFail()
                            )->id,['allow' => $rule['allow']]);
                            return array_key_exists('resource_id', $rule) ? Series::findOrFail($rule['userId']) : Series::where('name', $rule['name'])->firstOrFail();
                        case 'directory':
                            $user->directories()->attach((
                                array_key_exists('resource_id', $rule)
                                ? Directory::findOrFail($rule['resource_id'])
                                : Directory::where('name', $rule['name'])->firstOrFail()
                            )->id,['allow' => $rule['allow']]);
                            return array_key_exists('resource_id', $rule) ? Directory::findOrFail($rule['userId']) : Directory::where('name', $rule['name'])->firstOrFail();
                        case 'collection':
                            $user->collections()->attach((
                                array_key_exists('resource_id', $rule)
                                ? Collection::findOrFail($rule['resource_id'])
                                : Collection::where('name', $rule['name'])->firstOrFail()
                            )->id,['allow' => $rule['allow']]);

                            return array_key_exists('resource_id', $rule) ? Collection::findOrFail($rule['userId']) : Collection::where('name', $rule['name'])->firstOrFail();
                        default:
                            abort('Resource not found', 500);
                    }
                })();
            error_log("attach user: $user->id whit ".$rule['type'].": $resource->id");
        }
        return response(json_decode("$user->id updated"), 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::table('readables')->findOrFail($id)->update(['allow', $request->input('allow')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        DB::table('readables')->delete($id);
        error_log("deleted $id");
        return response(json_decode("rule $id deleted"), 200);
    }
}
