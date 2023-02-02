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

    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        //TODO: here is bad practice also. The batter way is get posts and paginate it on database level.
        return PostResource::collection(Post::all()->orderBy('created_at', 'desc')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request, ImageUploadService $imageUpload)
    {

        $result = Post::create($request->validated());
        if ($request->has('tags')) {
            $tags = collect($request->input('tags'));
            $formatted_tag = $tags->map(fn ($item) => ['name' => $item]);
            $result->tags()->createMany($formatted_tag);
        }
        if ($request->has('categories_id')) {
            $categories = explode(',', implode(',', $request->input('categories_id')));
            $result->categories()->attach($categories);
        }
        $imageUpload::uploadAnImage($request, $result, 'image_url', $request->getMethod());

        return new PostResource($result);
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
        return new PostResource($post->load('tags', 'categories', 'comments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\ImageUploadService  $imageUploadService
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post, ImageUploadService $imageUpload)
    {
        //TODO: all syncs here are not right. Because if i need to sync for example categories but need to remove all. This will not sync it.
        $data = $request->validated();
        // if (isset($data['categories_id'])) {
            $post->categories()->sync($data['categories_id'] ?? []);
        // }
        // if (isset($data['tags'])) {
            $post->tags()->sync($request->input('tags',[]));
        // }
        if (isset($data['image'])) {
            $imageUpload::uploadAnImage($request, $post, 'image_url', $request->getMethod());
        }
        $post->update($data);
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageUploadService  $imageUploadService
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, ImageUploadService $imageUploadService)
    {
        $file = public_path('images/') . $imageUploadService::getImageName($post, 'image_url');
        File::delete($file);
        $post->delete();
        return response()->noContent();
    }
}
