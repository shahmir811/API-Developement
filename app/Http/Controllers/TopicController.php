<?php

namespace App\Http\Controllers;

use App\Post;
use App\Topic;
use Illuminate\Http\Request;
use App\Transformers\TopicTransformer;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
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
              ->parseIncludes(['user', 'posts', 'posts.user', 'posts.likes'])
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

    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->title = $request->get('title', $topic->title); //Take the updated title, otherwise store the old title
        $topic->save();

        return fractal()
              ->item($topic)
              ->parseIncludes(['user'])
              ->transformWith(new TopicTransformer)
              ->toArray();
    }

    public function destroy (Topic $topic)
    {
      $this->authorize('destroy', $topic);

      $topic->posts()->delete();
      $topic->delete();

      return response(null, 204); //Response 204 mean successfull but with no content
    }
}
