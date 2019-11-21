<?php

namespace App\Scrapers;

use App\Cover;
use App\Libreries\Chain\Ring;
use App\Libreries\Wrapper\Archiver;
use Intervention\Image\Facades\Image as Imager;
use wapmorgan\UnifiedArchive\UnifiedArchive;
/*
    define("covers_dir", storage_path('app/public/covers/'));
    define("gallery_dir", storage_path('app/public/gallery/'));
    define("comics_dir", config('settings.comics_dir'));
*/
class LastHope extends Ring
{

    public function __construct($chain)
    {
        parent::__construct($chain);
    }

    protected function handler($comic)
    {


        if(!isset($comic->title) || !config('settings.use_online_data')){
            $comic->title = pathinfo($comic->file_path)['filename'];
            error_log("LastHope provade title: $comic->title to $comic->file_path");
        }
        if((!isset($comic->cover) || !config('settings.use_online_data')) && ($archive = Archiver::open($comic->file_path)) && $cover = $archive->find('/jpg$/'))
        {
            $cover_path = /*covers_dir.*/microtime().'.jpg';
            Imager::make($archive->getFileContent($cover))->resize(300, 400)->/*widen(500)->*/save(covers_dir.$cover_path);
            $cover = new Cover;
            $cover->cover_path = $cover_path;
            $cover->save();
            $comic->cover_id = $cover->id;
        }

        return $comic;
    }
}
