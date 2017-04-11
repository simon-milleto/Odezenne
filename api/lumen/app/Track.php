<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = ['track_id', 'title', 'artist', 'artwork_url', 'track_url', 'stream_url', 'status'];
}
