<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostFilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Post::query()
            ->when($request->has('categories'), function($query) use ($request){
                $query->withWhereHas('categories', function($query) use ($request){
                    $categories = explode(',', $request->input('categories'));
                    $query->whereIn('id', $categories);
                });
            })

            ->when($request->has('tags'), function($query) use ($request){
                $query->withwhereHas('tags', function($query) use ($request){
                    $tags = explode(',', $request->input('tags'));
                    $query->whereIn('id', $tags);
                });
            })

            ->when($request->has('search'), function($query) use ($request){
                $query->where('title', 'LIKE', '%'.$request->search.'%');
            })

            ->get();
    }
}
