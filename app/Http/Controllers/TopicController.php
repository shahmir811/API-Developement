<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Post;
use Illuminate\Http\Request;
use App\Transformers\TopicTransformer;
use App\Http\Requests\StoreTopicRequest;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TopicController extends Controller
{
    public function index()
    {
      $topics = Topic::latestFirst()->paginate(3);
      $topicCollection = $topics->getCollection();

      return fractal()
            ->collection($topics)
            ->parseIncludes(['user'])
            ->transformWith(new TopicTransformer)
            ->paginateWith(new IlluminatePaginatorAdapter($topics))
            ->toArray();
    }

    public function show(Topic $topic)
    {
        return fractal()
              ->item($topic)
              ->parseIncludes(['user', 'posts', 'posts.user'])
              ->transformWith(new TopicTransformer)
              ->toArray();
    }

    public function store(StoreTopicRequest $request)
    {
        $topic = new Topic;
        $topic->title = $request->title;
        $topic->user()->associate($request->user());

        $post = new Post;
        $post->body = $request->body;
        $post->user()->associate($request->user());

        $topic->save();
        $topic->posts()->save($post);

        return fractal()
              ->item($topic)
            //   ->parseIncludes(['user', 'posts', 'posts.user'])
              ->parseIncludes(['user'])
              ->transformWith(new TopicTransformer)
              ->toArray();

    }

    public function update()
    {

    }

}
