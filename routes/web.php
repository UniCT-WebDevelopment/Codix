<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Author;
use App\Comic;
use App\Directory;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\SeriesController;
use App\Http\Resources\ComicCollection;
use App\Http\Resources\CollectionDetail;
use App\Image;
use App\Libreries\Chain\Chain;
use App\Libreries\Wrapper\Archiver;
use App\Publisher;
use App\Series;
use App\User;
use App\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\Environment\Console;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use wapmorgan\UnifiedArchive\UnifiedArchive;
use App\Http\Resources\DirectoryCollection;

define('require_auth', config('settings.require_authentication'));
define("covers_dir", storage_path('app/public/covers/'));
define("gallery_dir", storage_path('app/public/gallery/'));
define("comics_dir", config('settings.comics_dir'));
define("security_settings", config("settings.security"));
define("scrapers", config('settings.scrapers'));
//$scraper = new Chain(config('settings.scrapers'));

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => security_settings['registration']/* || !Schema::hasTable('users') || App\User::all()->isEmpty()*/]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('c/restore/{id}', 'ComicController@restore');
Route::delete('c/destroy/{id}', 'ComicController@forceDelete');
Route::get('cl/restore/{id}', 'CollectionController@restore');
Route::delete('cl/destroy/{id}', 'CollectionController@forceDelete');
Route::resource('admin', 'AdminController');
Route::resource('user', 'UserController');
Route::resource('rule', 'RuleController');
Route::resource('setting', 'SettingController');

Route::resource('c', 'ComicController');
Route::resource('a', 'AuthorController');
Route::resource('s', 'SeriesController');
Route::resource('p', 'PublisherController');
Route::resource('cl', 'CollectionController');
Route::get('s/{id}', 'SeriesController@show');
Route::get('t', 'TagController@index');

Route::put('users/{id}', function ($id) {
    error_log('ciao');
});


Route::get('library', function(){
    View::addExtension('html','php');
    return view('index');
})->name('library')->middleware( require_auth ? 'auth' : null);


Route::get('ad', function(){
    View::addExtension('html','php');
    return view('admin-portal-index');
})->name('admin-portal')->middleware('auth')->middleware('admin');

Route::get('d', 'ComicController@directories');
Route::get('d/{id}', 'ComicController@directories');
Route::get('g', 'ComicController@defaultCover');
Route::get('g/{comicId}','ComicController@cover');
Route::get('g/{comicId}/{imageId}','ComicController@page');

Route::get('scan', 'AdminController@scan');
Route::get('scanJob', 'AdminController@scanJob');
Route::get('scanJob/{id}', 'AdminController@scanJob');
Route::post('admin/{id}', 'AdminController@store');


Route::get('logout','Auth\LoginController@logout');
Route::get('getToken', 'UserController@getToken')
//->middleware('auth')  //test
;

Route::put('add/rule/{id}', 'UserController@addRule');
Route::put('delete/rule/{id}', 'UserController@deleteRule');

/**
 * Todo
 * 1) proteggere adeguatamente la rotta user
 * 2) dividere view ridotta da view dettagliata
 * 3) rivedere reindirizzamenti del login
 * 4) Decidere come adattare le autorizzazzioni in caso di autenticazione non obbligatoria
 */









Route::get('scanId', 'AdminController@scanId');
Route::get('scanStatus/{id}', 'AdminController@jobStatus');

Route::get('scanning/{id}', 'AdminController@scansione');

Route::get('test', function (Request $request) {
    error_log('Aggiorno il filesystem');
    config(['filesystems.disks.comics.root' => comics_dir]);
    if($fp = fopen(base_path() .'/config/filesystem.php' , 'w')){
        fwrite($fp, '<?php return ' . var_export(config('filesystem'), true) . ';');
        fclose($fp);
    }

    return Storage::disk('comics')->directories();
    return config('filesystems.disks.comics.root');

    return Storage::disk('comics')->allFiles();
    return json_decode(Comic::find(2)->readableBy(Auth::user()));

    return Collection::readable()->get();

    return Comic::notReadableIds();

    return new CollectionDetail(Collection::find(1)->readableChildren()->paginate(25));

    return new CollectionDetail(DB::table('collectionables')->where('collection_id',1)->get());


/*
select `id` as `id`, `collectionables`.`collection_id` as `pivot_collection_id`, `collectionables`.`collectionable_id` as `pivot_collectionable_id`, `collectionables`.`collectionable_type` as `pivot_collectionable_type`
*/

        $collection = Collection::find(1);

        $rename = [ 'id as id','name as name','collectionables.collection_id as pivot_collection_id','collectionables.collectionable_id as pivot_collectionable_id','collectionables.collectionable_type as pivot_collectionable_type' ];




        $series = $collection->series()->select($rename)->addSelect(DB::raw("'s' As 'type'"));

        $comics = $collection->comics()->select(['id','title As name'])->addSelect(DB::raw("'c' As 'type'"));
        //return $comics->union($dirs)->toSql();
        return $comics->union($series)->get();


        return DB::select($comics->union($dirs)->toSql(), $comics->union($dirs)->getBindings());

        echo $dirs->get();
        echo '<br>';
        echo $comics->get();

        echo $comics->union($dirs)->get();



    return Collection::find(1)->readableChildren()->get();

    return Collection::readable()->get();

    DB::table('readables')->delete(21);
    return null;

    //return Directory::find(1)->readableDirectories()->select(['id','name As name','cover_id', 'directory_id'])->get();
    return new DirectoryCollection(Directory::find(1)->readableChildren()->get());

    $dirs = Directory::find(1)->readableDirectories()->select(['id','name AS name','cover_id'])->addSelect(DB::raw("'d' As 'type'"));
    $comics = Directory::find(1)->readableComics()->select(['id','title As name','cover_id'])->addSelect(DB::raw("'c' As 'type'"));

    return $comics->union($dirs)->get();

    $dir = Directory::find(1);

    //return $dir->readableComics()->select(['id','title As name','cover_id'])->addSelect(DB::raw("'c' As 'type'"))->get();
    $query = $dir->comics()->whereNotIn('id', Comic::notReadableIds())->select(['id','title As name','cover_id'])->addSelect(DB::raw("'c' As 'type'"));

    //return $query->get();
    return
    $dir->readableDirectories()->select(['id','name AS name','cover_id'])->addSelect(DB::raw("'d' As 'type'"))->mergeBindings($query)

    /*where(function($query){
            $query
            ->where(function($query){
                $query->whereNotIn('id', Comic::notReadableIds());
            })
            //->orWhere('type','<>','c')
            ;
        })
        */->get();



    //return Comic::notReadableIds();
    return Directory::find(1)->readableChildren()->get();//->comics()->whereNotIn('id', Comic::notReadableIds())->get();

    return new DirectoryCollection(Directory::find(1)->readableChildren()->paginate(25));



    return Comic::select(['id','title'])->addSelect(DB::raw("'c' As 'type'"))->union(Directory::select(['id','name As title'])->addSelect(DB::raw("'d' As 'type'")))->get();
    return null;
    $blackIds = DB::table('readables')->where('readable_type','comic')->where('allow',false)->select('readable_id AS id')->get()->map(function($id){return $id->id;});
    //return $blackIds;
    return Comic::whereNotIn('id', $blackIds)->get();










    return null;

    $dir = Directory::find(1);
    return $dir->comics()->get();
    return $dir->readableDirectories()->get();
    $blackDirIds = Auth::user()->directories()->wherePivot('allow', false)->select('directories.id')->get();
    return $blackDirIds;
    return Directory::all();
    return Directory::whereNotIn('id', Auth::user()->directories()->wherePivot('allow', false)->select('directories.id')->get())->get();
    return Comic::notReadableIds();

    $user = Auth::user();
    $comic = Comic::find(15);

    DB::table('readables')->delete(17);

    //$user->comics()->attach(15, array('allow' => true));


    return $user->comics()->withPivot('allow')->get();

    return Comic::readableIds();
    return $user->readableComics()->get();
    return Comic::all();

    //return Series::find(2)->comics()->get();

    //return Comic::find(11)->series()->get();

    return json_decode(Series::find(1)->users()->withPivot('allow')->select('allow')->find($user->id)->allow == 1);



    //return Comic::notReadableIds();

    return Auth::user()->readableComics()->get();

    return Comic::whereNotIn('id', Comic::notReadableIds())->get();

    return Comic::notReadableIds();

    $blackIds = Auth::user()->blackComicIds();

    return Comic::whereNotIn('id', $blackIds)->get();

    return Comic::select('id')->get()->reject(function($comic){
        return $comic->readableBy(Auth::user());
    });

    return Comic::where('title','like','%%')->get()->reject(function($comic){
        return !$comic->readableBy(Auth::user());
    });

    return Auth::user()->readableComics()->get();


    return Directory::find(1)->readableComics(Auth::user())->get();


    $var = Comic::find(2)->series()->join('readables','series.id','=','readable_id')->where([['readable_type','series'],['user_id', 2]])->select('allow')->first();
    return $var;
    return json_encode ( $var->pivot->allow == 1 );

    return Comic::find(2)->series()->get()->map(function($series){
        return $series->users()->withPivot('allow')->where('user_id', 2)->select('allow')->first();
    });
    /*
    ->whereHas('users', function($query){
        $query->where('users.id',2);
    })->get();
*/


    return null;

    return Auth::user()->readableComics()
        ->where(function($query){
            $query
            ->where('title', 'like', "%prova%")
            ->orWhere('title','like','%dylandog%');
        })

        ->get();





    return Auth::user()->comics()->where('title','like', "%%")->get();







    return Auth::user()->readableComics()->get();
    return json_encode(
        Comic::find(1)->readableBy(Auth::user())
    );

    return Directory::find(1)->readableComics(Auth::user())->get();
    return User::find(1)->readableComics()->get();
    return security_settings['allow_user_registration']['value'];
    //return config('settings.comics_dir');
    return Storage::disk('comics')->files('');
    return null;
    $series_id = (Series::firstOrCreate(['name' => 'Prova']))->id;
    return $series_id;
    $string = "[Prova]The Legend of Zelda - Twilight Princess.cbz";
    $pos = strpos($string, '[');
    error_log($pos);
    if(strpos($string, '[') == 0)
        return substr(strstr($string, ']', true), 1);

    return null;

    $query = DB::table('active_comics')->where('updated_at', '<=' , Carbon::now()->subDay());
    $query = DB::table('active_comics');
            foreach($query->get() as $old_comic){
                Image::where('comic_id', $old_comic->id)->delete();
                File::deleteDirectory(gallery_dir.$old_comic->id);
                error_log("cancellare".gallery_dir."/$old_comic->id");
            }

            $query->delete();
            error_log("old comics deleted");
    return null;

    //$query = DB::table('active_comics')->where('updated_at', '<=' , Carbon::now()->subDay())->delete();

    //just for test
    $query = DB::table('active_comics');

    foreach($query->get() as $old_comic){
        File::deleteDirectory(gallery_dir.$old_comic->id);
        error_log("cancellare ".gallery_dir.$old_comic->id);
        Image::where('comic_id', $old_comic->id)->delete();
    }
    //return DB::table('active_comics')->get();
    return DB::table('active_comics')->where('updated_at', '<=' , Carbon::now()->subDay())->get();
    return Carbon::now()->subDay().'<br>'.Carbon::now();
    return new ComicCollection(Publisher::findOrFail(1)->comics()->get());
    return Comic::find(1)->authors()->get();
    return Comic::whereHas('authors', function($query){
        $query->where('authors.name','=','Artibani');
    })->get();

    return Comic::with(['authors' => function($query){
        $query->where('authors.name','=','Artibani');
    }])->get();
    //return Comic::join('author_comic', 'comics.id','=','author_comic.comic_id')->join('authors','author_id','=','authors.id')->get();

    if($request->input('q'))
        return $request->input('q');
    else
        return 'nessun input passato';
    return $request->all();

    $file = "/home/riccardo/comics/Asterix e i Goti-METAINFO.json";
    if(file_exists($metaFile = $file) && ($metainfo = json_decode(file_get_contents($metaFile))))
    $keys = get_object_vars($metainfo);
    foreach ( $keys as $key => $value){
        echo "$key $value";
    }

});
/*
*/
