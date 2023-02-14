<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;


class PostFilterController extends Controller
{

    public function __invoke(Request $request){

        $posts = QueryBuilder::for(Post::class)
                ->with('categories', 'tags', 'comments')
                ->allowedFilters([
                    AllowedFilter::exact('categories', 'categories.id'),
                    AllowedFilter::exact('tags', 'tags.id')
                ])
                ->get();

        return new PostResource($posts);
    }

}
