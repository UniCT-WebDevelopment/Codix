<?php

namespace App\Http\Controllers;

use App\ActiveComic;
use App\Author;
use App\Collection;
use App\Comic;
use App\Cover;
use App\Directory;
use App\Http\Resources\Comic as AppComic;
use App\Http\Resources\ComicCollection;
use App\Http\Resources\ComicDetail;
use App\Http\Resources\DirectoryCollection;
use App\Http\Resources\ImageCollection;
use App\Http\Resources\Publisher;
use App\Image;
use App\Libreries\Wrapper\Archiver;
use App\Publisher as AppPublisher;
use App\User;
use App\Tag;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image as Imager;
use wapmorgan\UnifiedArchive\UnifiedArchive;


use App\Policies\ComicPolicy;

class ComicController extends Controller
{

    private $comicPolicy;

    public function __construct()
    {
        if (security_settings['require_authentication']['value'])
            $this->middleware('auth');
        
        $this->comicPolicy = new ComicPolicy();
        /*
         * La policy in questo modo dovrebbe passare un modello comic, ma da esito sempre negativo
         * Si devono chiamare i singoli medoti
         */
        //$this->authorizeResource(Comic::class, 'comic');
    }

    public function index(Request $request)
    {

        if(!$this->comicPolicy->viewAny(Auth::user()))
            abort(403,'Not authorized');

        if($author_id = $request->input('artist')){
            $comics = Author::findOrFail($author_id)->readableComics();
            error_log("request comics of author: $author_id");
        } else
        if($publisher_id = $request->input('publisher')){
            $comics = Publisher::findOrFail($publisher_id)->readableComics();
            error_log("request comics of publisher: $publisher_id");
        } else

/*
            $comics = Auth::user() && Auth::user()->isAdmin() ? Comic::withTrashed() : Auth::user()->readableComics();





        $comics = Auth::user() && Auth::user()->isAdmin() ? Comic::withTrashed() : Comic::where('title','like','%%');
*/
        
            $comics = Comic::readable();
        $q = $request->input('q') ?: '';

        error_log("request comics $q");

        return new ComicCollection(
                $comics->where(function($query) use($q){
                    $query
                    ->where('title','like',"%$q%")

                    ->orWhere('user_tags', 'like', "%$q%")

                    ->orWhereHas('tags', function($query) use ($q){
                        $query->where('tags.name','like',"%$q%");
                    })
                    ->orWhereHas('authors', function($query) use ($q){
                        $query->where('authors.name','like',"%$q%");
                    })
                    ->orWhereHas('publishers', function($query) use($q){
                        $query->where('publishers.name','like',"%$q%");
                    });
                })
                ->paginate(25)
        );
    }

    public function show($id)
    {

        // Non funziona
        // $this->authorize('view', [Comic::findOrFail($id)]);

        if(!$this->comicPolicy->view(Auth::user(), Comic::findOrFail($id)))
            return abort(403,'Not authorized');


        if(!($active = ActiveComic::find($id)) && ($comic = Comic::findOrFail($id)) && ($archive = UnifiedArchive::open($comic->file_path))){
            ActiveComic::create(['id' => $id]);
            $archive->extractFiles(gallery_dir.'/'.$id);
            $images = $archive->getFileNames();
            foreach($images as $i=>$image)
                if(pathinfo($image)['extension'] == 'jpg' || pathinfo($image)['extension'] == 'png')
                    Image::create(['comic_id' => $id, 'image_id' => $i, 'image_path' => /*gallery_dir.'/'.*/$id.'/'.$image]);
        }
        $active = ActiveComic::find($id);
        $active->viewd++;
        $active->save();
        return   ['data' => new ImageCollection($active->images()->get()) /*, 'comic' => new AppComic($active)*/];
    }

    public function update(Request $request, $id)
    {

        if( is_null(Auth::user()) || Auth::user()->cant('update', $comic = Comic::findOrFail($id)))
            abort(403,'Not authorized');
      
        error_log("update comic $id");
        $comic = Comic::findOrFail($id);
        $payload = $request->all();

        if( array_key_exists('toUpdate', $payload) && !is_null($body = $payload['toUpdate'])){
            error_log("body: ".json_encode($body));
            $comic->update($body);
        }
/*
        if( array_key_exists('attach', $payload) && !is_null($attach = $payload['attach'])){
            error_log("attach to $id");

            if(array_key_exists('authors', $attach) && !is_null($artists = $attach['authors']))
                foreach($artists as $artist){
                    error_log("attach to $id author $artist");
                    $author = is_string($artist) ? Author::firstOrCreate(['name' => $artist]) : Author::findOrFail($artist);
                    $comic->authors()->attach($author);
                }

            if(array_key_exists('publishers', $attach) && !is_null($publishers = $attach['publishers']))
                foreach($publishers as $publisher){
                    error_log("attach to $id publisher $publisher");
                    $publisher = is_string($publisher) ? AppPublisher::firstOrCreate(['name' => $publisher]) : AppPublisher::findOrFail($publisher);
                    $comic->publishers()->attach($publisher);
                }
        }

        if(array_key_exists('detach', $payload) && !is_null($detach = $payload['detach'])){
            if(array_key_exists('authors', $detach) && !is_null($artists = $detach['authors']))
                foreach($artists as $artist){
                    $comic->authors()->detach($artist);
                    error_log("detach to $id artist $artist");
                }
            if(array_key_exists('publishers', $detach) && !is_null($publishers = $detach['publishers']))
                foreach($publishers as $publisher){
                    $comic->publishers()->detach($publisher);
                    error_log("detach to $id publisher $publisher");
                }
        }

        //return response($this->edit($id), 200);
*/
        $payload = (($request->all()));
        error_log(json_encode($payload));
        if(array_key_exists('attach', $payload) && !is_null($attachs = $payload['attach']))
        {
            foreach ($attachs as $attach){
                switch ($attach['type']) {
                    case 'author':
                        $author = is_string($attach['resource']) ? Author::firstOrCreate(['name' => $attach['resource']]) : Author::findOrFail($attach['resource']);
                        $comic->authors()->attach($author);
                        break;
                    case 'publisher':
                        $publisher = is_string($attach['resource']) ? AppPublisher::firstOrCreate(['name' => $attach['resource']]) : Publisher::findOrFail($attach['resource']);
                        $comic->publishers()->attach($publisher);
                        break;
                    case 'tag':
                        $tag = is_string($attach['resource']) ? Tag::firstOrCreate(['name' => $attach['resource']]) : Tag::findOrFail($attach['resource']);
                        $comic->tags()->attach($tag);
                        break;
                    default:
                        abort(500, 'resource type not found');
                        break;
                }
            }
        }

        if(array_key_exists('detach', $payload) && !is_null($detachs = $payload['detach'])) 
        {
            foreach ($detachs as $detach){ 
                switch ($detach['type']) {
                    case 'author':
                        $author = is_string($detach['resource']) ? Author::firstOrCreate(['name' => $detach['resource']]) : Author::findOrFail($detach['resource']);
                        $comic->authors()->detach($author);
                        break;
                    case 'publisher':
                        $publisher = is_string($detach['resource']) ? AppPublisher::firstOrCreate(['name' => $detach['resource']]) : AppPublisher::findOrFail($detach['resource']);
                        $comic->publishers()->detach($publisher);
                        break;
                    case 'tag':
                        $tag = is_string($detach['resource']) ? Tag::firstOrCreate(['name' => $detach['resource']]) : Tag::findOrFail($detach['resource']);
                        $comic->tags()->detach($tag);
                        break;
                    default:
                        abort(500, 'resource type not found');
                        break;
                }
            }
        }

    }

    public function edit(User $user, $id){
        return new ComicDetail( Auth::user() && Auth::user()->isAdmin() ? Comic::withTrashed()->findOrFail($id) : Comic::findOrFail($id));
    }

    public function destroy($id){
        error_log("$id soft deleted");
        Comic::destroy($id);
    }

    public function restore($id){
        error_log("restore $id");
        Comic::onlyTrashed()->findOrFail($id)->restore();
    }

    public function forceDelete($id) {
        error_log("$id non esiste più");
        Comic::onlyTrashed()->findOrFail($id)->forceDelete();
    }

    public function defaultCover() {
        return Imager::make(public_path('media/covers.png'))->response();
    }

    public function cover($id)
    {
        return Imager::make(covers_dir.'/'.Cover::findOrFail($id)->cover_path)->response();
    }

    public function page($comicId, $imageId)
    {
        return Imager::make(gallery_dir.'/'.ActiveComic::findOrFail($comicId)->images()->where('image_id',$imageId)->firstOrFail()->image_path)->response();
    }

    public function directories(Request $request, $id = null)
    {
        $dir = is_null($id) ? Directory::where('name', '/')->firstOrFail() : Directory::findOrFail($id);
        
        if(!$this->comicPolicy->viewAny(Auth::user()))
            abort(403,'Not authorized');
        
        
        error_log("request directory: $dir->id");
        return new DirectoryCollection($dir->readableChildren()->paginate(25));

    }

}
