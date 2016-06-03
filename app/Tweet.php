<?php

namespace herysepty;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = [
        'id_tweet', 'username', 'tweet','date_tweet',
    ];
}
