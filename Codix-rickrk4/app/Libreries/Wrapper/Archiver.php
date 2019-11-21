<?php

namespace App\Libreries\Wrapper;

use wapmorgan\UnifiedArchive\UnifiedArchive;

class Archiver extends UnifiedArchive
{

    protected function __construct($fileName, $type)
    {
        parent::__construct($fileName, $type);
    }


    public static function open($fileName)
    {
        self::checkRequirements();

        if (!file_exists($fileName) || !is_readable($fileName) || !parent::canOpenType($type = parent::detectArchiveType($fileName)))
            return null;
/*
            //throw new Exception('Could not open file: '.$fileName);

        $type = parent::detectArchiveType($fileName);
        if (!parent::canOpenType($type)) {
            //return 'mannaggina! non conosco questo formato!';
            return null;
        }
*/
        return new self($fileName, $type);
    }

    public function getArchive(){
        return $this->archive;
    }

    public function find($condition)
    {
        $files = parent::getFileNames();
        foreach($files as $file)
            if(preg_match($condition, $file))
                return $file;
    }

}
