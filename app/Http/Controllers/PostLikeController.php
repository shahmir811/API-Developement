<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use App\Topic;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function store(Request $request, Topic $topic, Post $post)
    {
        $this->authorize('like', $post); //If this is my post, I cannot like it.

        if($request->user()->hasLikedPost($post))
        {
            return response(null, 409); //Response 409 means there is a conflict in storing or updating record
        }

        $like = new Like;
        $like->user()->associate($request->user());

        $post->likes()->save($like);

        return response(null, 204);
    }
}
