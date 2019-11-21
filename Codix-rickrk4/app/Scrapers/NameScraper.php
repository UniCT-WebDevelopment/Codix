<?php

namespace App\Scrapers;

use App\Libreries\Chain\Ring;
use App\Series;

class NameScraper extends Ring
{
    public function __construct($chain)
    {
        parent::__construct($chain);
    }

    protected function handler($comic)
    {
        $file = pathinfo($comic->file_path)['basename'];
        if($file[0] == '[')
        {
            $series = substr(strstr($file, ']', true), 1);
            $series_id = (Series::firstOrCreate(['name' => $series]))->id;

            error_log("NameScraper attach comic $comic->id to series $series");
            $comic->series()->sync($series_id);
        }

        return $comic;
    }
}
