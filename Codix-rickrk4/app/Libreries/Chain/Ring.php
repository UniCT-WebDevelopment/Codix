<?php

namespace App\Libreries\Chain;

abstract class Ring
{

    protected $nextRing;
    private $stop = false;

    protected function Stop(){
        $this->stop = true;
    }

    public function __construct($chain = null)
    {
        if($class = array_shift($chain))
            $this->nextRing = new $class($chain);

    }

    public function call($input)
    {
        $result = $this->handler($input);
        if($this->stop || is_null($this->nextRing)) return $result;
        return $this->nextRing->call($result);
    }

    abstract protected function handler($request);

}
