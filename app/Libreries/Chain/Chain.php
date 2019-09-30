<?php

namespace App\Libreries\Chain;

class Chain extends Ring
{
    public function __construct($chain)
    {
        parent::__construct($chain);
    }

    protected function handler($request)
    {
        return $request;
    }
}
