<?php

namespace App;

use App\Topic;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'username', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function avatar()
    {
        return 'http://www.gravatar.com/avatar/' . md5($this->email) . '?s=45&d=mm';
    }

    public function ownsTopic(Topic $topic)
    {
      //return $this->id === $topic->user_id; //Can use anyone    
      return $this->id === $topic->user->id;
    }

}
