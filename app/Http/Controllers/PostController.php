<?php

namespace App\Http\Controllers;

use App\Post;
use App\Topic;
use Illuminate\Http\Request;
use App\Transformers\PostTransformer;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    public function store(StorePostRequest $request, Topic $topic)
    {
        $post = new Post;
        $post->body = $request->body;
        $post->user()->associate($request->user());

        $topic->posts()->save($post);

        return fractal()
                ->item($post)
                ->parseIncludes(['user'])
                ->transformWith(new PostTransformer)
                ->toArray();

    }

    public function update(UpdatePostRequest $request, Topic $topic, Post $post)
    {
        $this->authorize('update', $post);

        $post->body = $request->get('body', $post->body);
        $post->save();

        return fractal()
                ->item($post)
                ->parseIncludes(['user'])
                ->transformWith(new PostTransformer)
                ->toArray();

    }


}
