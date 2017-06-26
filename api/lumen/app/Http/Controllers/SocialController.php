<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings;


class SocialController extends Controller
{
    /**
     * Returns a list of all active tracks
     *
     * @return \Illuminate\Http\Response
     */
    public function twitterFeed()
    {
        $tweets = '';

        return response()->json('test');
    }

}
