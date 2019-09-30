<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Http\Resources\TagCollection;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q') ?: '';
    	return new TagCollection(Tag::where('name','like',"%$q%")->get());
    }
}
