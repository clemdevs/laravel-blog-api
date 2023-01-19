<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $tags = Tag::all();
            return new TagResource($tags);
        }
        catch(\Exception $e){
            return response($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTagRequest  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTagRequest $request, Tag $tag)
    {
        try{
            $result = $tag->create($request->validated());
            return new TagResource($result);
        }
        catch(\Exception $e){
            return response($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return new TagResource(Tag::with('posts')->find($tag->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTagRequest  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        try{
            $tag->update($request->validated());
            return new TagResource($tag->with('posts')->findOrFail($tag)->get());
        }
        catch(\Exception $e){
            return response($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        try{
            $tag->delete();
            return response()->noContent();
        }
        catch(\Exception $e){
            return response($e->getMessage());
        }
    }
}
