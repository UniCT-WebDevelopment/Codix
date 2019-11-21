<?php

namespace App\Scrapers;

use App\Author;
use \App\Comic as Comic;
use App\Libreries\Chain\Ring;
use wapmorgan\UnifiedArchive\UnifiedArchive;
use Illuminate\Support\Facades\Schema;

use App\Libreries\Wrapper\Archiver as Archiver;
use App\Publisher;
use App\Series;


class LocalScraper extends Ring
{
    public function __construct($chain)
    {
        parent::__construct($chain);
    }

    protected function handler($file, $dir = null)
    {

        if($archive = Archiver::open($file))
        {
            $comic = new Comic;
            $comic->file_path = $file;
            $comic->save();

            //parent::Stop();

            if
            (   //Il file esterno esclude il file interno
                (file_exists($metaFile = pathinfo($file)['dirname'].'/'.pathinfo($file)['filename'].'-METAINFO.json') && ($metainfo = json_decode(file_get_contents($metaFile))))
                ||
                (($metaFile = $archive->find('/METAINFO.json$/')) && ($metainfo = json_decode($archive->getFileContent($metaFile))))
            )
            {
                //$comic->title = $metainfo->title;

                $keys = get_object_vars($metainfo);
                $user_tags = null;

                foreach($keys as $key=>$value)
                    if(!is_null($value))
                    switch($key){
                        case "series":
                            $series_id = (Series::firstOrCreate(['name' => $value]))->id;
                            $comic->series()->sync($series_id);
                            error_log("LocalScraper want attach comic $comic->id to series $series_id");
                            break;
                        case "author":
                            $author_id = (Author::firstOrCreate(['name' => $value]))->id;
                            $comic->authors()->sync($author_id);
                            error_log("LocalScraper want attach comic $comic->id to author $author_id");
                            break;
                        case "publisher":
                            $publisher_id = (Publisher::firstOrCreate(['name' => $value]))->id;
                            $comic->publishers()->sync($publisher_id);
                            error_log("LocalScraper want attach comic $comic->id to publisher $publisher_id");
                            break;
                        case "collection":
                            break;
                        default:
                            if(Schema::hasColumn($comic->getTable(), $key))
                            {
                                if(!isset($comic->$key))
                                {
                                    $comic->$key = $value;  //Settiamo direttamente nella entry
                                }
                            }else{
                                $user_tags[$key] = $value;
                            }
                            break;
                    }

                if(!is_null($user_tags))
                    $comic->user_tags = json_encode($user_tags);

            }

            return $comic;
        }else {
            parent::Stop();
            return null;
        }

    }
}
