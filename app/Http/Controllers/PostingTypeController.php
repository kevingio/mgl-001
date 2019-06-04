<?php

namespace App\Http\Controllers;

use App\Models\PostingType;
use Illuminate\Http\Request;

class PostingTypeController extends Controller
{

    public function __construct(PostingType $postingType)
    {
        $this->postingType = $postingType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postingTypes = $this->postingType->get();
        return response()->json([
            'error' => false,
            'items' => $postingTypes
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PostingType  $postingType
     * @return \Illuminate\Http\Response
     */
    public function show(PostingType $postingType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PostingType  $postingType
     * @return \Illuminate\Http\Response
     */
    public function edit(PostingType $postingType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PostingType  $postingType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostingType $postingType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PostingType  $postingType
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostingType $postingType)
    {
        //
    }
}
