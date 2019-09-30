<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Comic;
use App\Directory;
use App\Http\Resources\Collection as AppCollection;
use App\Http\Resources\CollectionDetail;
use App\Http\Resources\CollectionCollection;
use App\Http\Resources\Collection as ResourceCollection;
use App\Http\Resources\TagCollection;
use App\Series;
use App\User;
use App\Policies\CollectionPolicy;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\ErrorHandler\Collecting;

class CollectionController extends Controller
{

    private $collectionPolicy;
    public function __construct()
    {
        //$this->authorizeResource(Collection::class, 'collection');
        $this->collectionPolicy = new CollectionPolicy();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(!$this->collectionPolicy->viewAny(Auth::user()))
            abort(403,'Not Authorized');

        return new CollectionCollection( Collection::readable()->paginate(25) );
/*
        $collections = Auth::user() && Auth::user()->isAdmin() ? Collection::withTrashed()->get() : Collection::all();

        foreach($collections as $collection){
            $collection['title'] = $collection['name'];
            $collection['type' ] = 'cl';
            $collection['coverUrl'] = "g/".$collection->cover();
        }
        return ['data' => $collections];
*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        error_log('ciao');
        return 'ok';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        error_log($request->input('title'));
        if($name = $request->input('title')){
            error_log($name);
            $collection = new Collection;
            $collection->name = $name;
            if($user = Auth::user())
                $collection->user_id = $user->id;
            $collection->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$this->collectionPolicy->view(Auth::user(), Collection::findOrFail($id)))
            abort(403,'Not Authorized');
        $collection = Auth::user() && Auth::user()->isAdmin() ? Collection::withTrashed()->findOrFail($id) : Collection::findOrFail($id);
        return new CollectionDetail($collection->readableChildren()->paginate(25));
        return ['data' => Collection::readable()->findOrFail($id)->children()];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $collection = Auth::user() && Auth::user()->isAdmin() ? Collection::withTrashed()->findOrFail($id) : Collection::findOrFail($id);
        return [
            'data' => [
            'id' => $collection->id,
            'name' => $collection->name,
            'createdBy' => User::find($collection->user_id),
            'tags' => new TagCollection($collection->tags()->get()),
            'children' => $collection->collected()
            ],
            'trashed' => $collection->trashed(),
        ];
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

        $collection = Collection::findOrFail($id);
        error_log("update collection $id");
        //error_log($request);
        if(array_key_exists('toUpdate', $request->all()))
            $collection->update($request->input('toUpdate'));

        if($name = $request->input('name')){
            $collection->name = $name;
            $collection->save();
            error_log($name);
        }
        if($attach = $request->input('attach')){
            error_log(json_encode($attach));
            foreach ($attach as $entry) {
                error_log(json_encode($entry));
                switch ($entry['type']) {
                    case 'comic':
                        if(($comic = Comic::findOrFail($entry['resource'])) && !$collection->comics()->find($comic->id))
                            $collection->comics()->attach($comic);
                        break;
                    case 'directory':
                        if(($directory = Directory::findOrFail($entry['resource'])) && !$collection->directories()->find($directory->id))
                            $collection->directories()->attach($directory);
                        break;
                    case 'series':
                        if(($series = Series::findOrFail($entry['resource'])) && !$collection->series()->find($series->id))
                            $collection->series()->attach($series);
                        break;
                    case 'tag':
                        if(($tag = is_numeric($entry['resource']) ? Tag::findOrFail($entry['resource']) : Tag::firstOrCreate(['name' => $entry['resource']])) && !$collection->tags()->find($tag))
                            $collection->tags()->attach($tag);
                        break;
                    default:
                        abort(500,'Internal server error');
                }
            }
/*
            if(array_key_exists('comic', $attach)){
                $comics = $attach['comics'];
                foreach($comics as $comic_id)
                    if(($comic = Comic::find($comic_id)) && !$collection->comics()->find($comic_id))
                        $collection->comics()->attach($comic);
            }
            if(array_key_exists('series', $attach)){
                $series = $attach['series'];
                foreach($series as $series_id)
                    if(($serie = Series::find($series_id)) && !$collection->series()->find($series_id))
                        $collection->series()->attach($serie);
            }
            if(array_key_exists('tags', $attach)){
                $tags = $attach['tags'];
                foreach ($tags as $tag) {
                    $tagModel = is_numeric($tag['resource']) ? Tag::findOrFail($tag['resource']) : Tag::where('name',$tag)->firstOrFail();
                    if(!$collection->tags()->find($tagModel->id))
                        $collection->tags()->attach($tagModel);
                }
            }
            //error_log("modifico la $id, $comics[0], $series[0]");
*/



        } else
        if($detach = $request->input('detach')){
            foreach ($detach as $entry) {
                error_log(json_encode($entry));
                switch ($entry['type']) {
                    case 'comic':
                        $collection->comics()->detach($entry['resource']);
                        break;
                    case 'directory':
                        $collection->directories()->detach($entry['resource']);
                        break;
                    case 'series':
                        $collection->series()->detach($entry['resource']);
                        break;
                    case 'tag':
                        $collection->tags()->detach($entry['resource']);
                        break;
                    default:
                        abort(500,'Internal server error');
                }
            }
            /*

            //error_log("tolgo la $comics[0], $series[0]");
            if(array_key_exists('comic', $detach)){
                $comics = $detach['comics'];
                foreach($comics as $comic_id)
                    if($comic = Comic::findOrFail($comic_id))
                        $collection->comics()->detach($comic_id);
            }
            if(array_key_exists('series', $detach)){
                $series = $detach['series'];
                foreach($series as $series_id)
                    if($serie = Series::findOrFail($series_id))
                        $collection->series()->detach($series_id);
            }
            if(array_key_exists('tags', $detach)){
                $tags = $detach['tags'];

                foreach ($tags as $tag) {
                    error_log("detach ".json_encode($tag));
                    $tagModel = is_numeric($tag['resource']) ? Tag::findOrFail($tag['resource']) : Tag::where('name',$tag)->firstOrFail();
                        $collection->tags()->detach($tagModel);
                }
            }
            */

        }

        return response(200);//$attach['comic'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        error_log("delete $id");
        Collection::onlyTrashed()->forceDelete($id);
        return "$id non esiste più";
    }

    public function destroy($id){
        error_log("soft delete $id");
        Collection::destroy($id);
    }

    public function restore($id){
        error_log("collection $id restored");
        Collection::onlyTrashed()->findOrFail($id)->restore();
        return "$id restored";
    }


}
