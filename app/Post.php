<?php

namespace App;

use App\Topic;
use App\User;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Orderable;

    protected $fillable = ['body'];

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function topic()
    {
      return $this->belongsTo(Topic::class);
    }
}
