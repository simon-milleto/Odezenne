<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = ['track_id', 'title', 'artwork_url', 'track_url', 'stream_url', 'total_time', 'status', 'updated_at', 'created_at'];
}