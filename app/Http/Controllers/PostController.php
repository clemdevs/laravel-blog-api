<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ImageUpload;
use Illuminate\Http\Request;

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
    public function index()
    {
        $posts = Post::with('tags', 'categories', 'comments')->paginate();
        return PostResource::collection($posts);
    }


    /**
     * Undocumented function
     *
     * @param StorePostRequest $postRequest
     * @param ImageUpload $imageUpload
     * @return void
     */
    public function store(StorePostRequest $postRequest, ImageUpload $imageUpload)
    {
        //validate the posts
        $validatedPostData = $postRequest->validated();

        if (!empty($validatedPostData['image'])) {
            $relativePath = $imageUpload::setImage($validatedPostData['image']);
            $validatedPostData['image_url'] = $relativePath;
        }

        $post = Post::create($validatedPostData);

        if (!empty($validatedPostData['tags'])) {
            $tags_names = explode(',', $validatedPostData['tags']);
            $tags_names = array_map('trim', $tags_names);

            foreach ($tags_names as $tag_names) {
                Tag::firstOrCreate(['name' => $tag_names]);
            }

            $tags = Tag::whereIn('name', $tags_names)->pluck('id')->toArray();

            $post->tags()->sync($tags);
        };


        if (!empty($validatedPostData['categories'])) {

            $categories = explode(',', $validatedPostData['categories']);
            $categories = array_map('trim', $categories);

            $categories_data = Category::whereIn('name', $categories)->pluck('id')->toArray();

            $post->categories()->sync($categories_data);
        }

        return new PostResource($post->load('categories', 'tags'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post->load('tags', 'categories', 'approvedComments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\ImageUploadService  $imageUploadService
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post, ImageUpload $imageUpload)
    {
        $data = $request->validated();

        if (!empty($data['categories_id'])) {
            $categories_input = explode(',', implode(',', $data['categories_id']));
            $categories_input = array_map('trim', $categories_input);
            $category = Category::whereIn('name', $categories_input)->pluck('id')->toArray();
            $post->categories()->sync($category ?? []);
        }

        if (!empty($data['tags'])) {
            $tags_ids = explode(',', $data['tags']);
            $tags_ids = array_map('trim', $tags_ids);
            $tags = Tag::whereIn('name', $tags_ids)->pluck('id')->toArray();
            $post->tags()->sync($tags ?? []);
        }

        if (!empty($data['image'])) {
            $relative_path = $imageUpload::setImage($data['image']);
            $data['image_url'] = $relative_path;
        }

        $post->update($data);

        return new PostResource($post->load('categories', 'tags'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageUploadService  $imageUploadService
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, ImageUpload $imageUpload)
    {
        $imageUpload::deleteImage($post['image_url']);
        $post->delete();
        return response()->noContent();
    }
}
