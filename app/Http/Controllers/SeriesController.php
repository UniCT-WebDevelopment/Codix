<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComicCollection;
use App\Http\Resources\SeriesCollection;
use App\Series;
use App\Policies\SeriesPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeriesController extends Controller
{
    private $seriesPolicy;

    public function __construct()
    {
        //$this->authorizeResource(Series::class, 'series');
        $this->seriesPolicy = new SeriesPolicy();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$this->seriesPolicy->viewAny(Auth::user()))
            abort(403,'Not authorized');

        $q = $request->input('q') ?: '';
        error_log("reserch series for $q");

        return new SeriesCollection(
            Series::readable()->where('name','like',"%$q%")->paginate(25)
        );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->seriesPolicy->create(Auth::user()))
            abort(403,'Not authorized');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $series = Series::findOrFail($id);

        if(!$this->seriesPolicy->view(Auth::user(), $series))
            abort(403,'Not authorized');

        return new ComicCollection($series->readableComics()->paginate(25));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
