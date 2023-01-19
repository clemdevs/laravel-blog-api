<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;


class PostFilterController extends Controller
{

    public function __invoke(Request $request){

        if($request->has('categories') || $request->has('tags'))
        $posts = QueryBuilder::for(Post::class)
        ->with(['categories', 'tags'])
        ->allowedFilters([
            AllowedFilter::exact('categories', 'categories.id'),
            AllowedFilter::exact('tags', 'tags.id')
        ])
        ->get();


        return $posts;
    }

}
