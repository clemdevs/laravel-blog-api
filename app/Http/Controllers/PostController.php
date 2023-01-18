<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PostController extends Controller
{

    public function __construct(){
        $this->authorizeResource(Post::class, 'post');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request, Post $post)
    {
        try{
            return PostResource::collection(Post::with(['categories', 'tags'])->orderBy('created_at', 'desc')->paginate());
        }
        catch(\Exception $e){
            return response($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Post  $post
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request, Post $post, ImageUploadService $imageUpload)
    {
        try{
            $result = $post->create($request->validated());
            if($request->has('tags')){
                $tags = collect($request->input('tags'));
                $formatted_tag = $tags->map(fn($item) => ['name' => $item]);
                $result->tags()->createMany($formatted_tag);
            }
            if($request->has('categories_id')){
                $categories = explode(',', implode(',',$request->input('categories_id')));
                $result->categories()->attach($categories);
            }
            $imageUpload::uploadAnImage($request, $result, 'image_url', $request->getMethod());


            return new PostResource($result);

        }
        catch(\Exception $e){
            return response($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post, ImageUploadService $imageUpload)
    {
        try{
            $post->update($request->validated());
            $post->categories()->sync($request->input('category_id'));
            $post->tags()->sync($request->input('tags'));
            $imageUpload::uploadAnImage($request, $post, 'image_url', $request->getMethod());

            return new PostResource($post);
        }
        catch(\Exception $e){
            return response($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        try{
            $file = public_path('images/').base64_decode(Str::after($post->image_url, 'images/'));
            File::delete($file);
            $post->delete();
            return response()->noContent();
        }
        catch(\Exception $e){
            return response($e->getMessage());
        }
    }
}
