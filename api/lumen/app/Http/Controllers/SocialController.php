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

    /**
     * Returns a list of youtube videos
     *
     * @return \Illuminate\Http\Response
     */
    public function youtubeFeed()
    {
        $videos = [];

        $api_key = Settings::where('label', 'youtube_api_key')->limit(1)->pluck('value')[0];
        $max_results = Settings::where('label', 'youtube_max_results')->limit(1)->pluck('value')[0];

        //search channels of user
        $json = file_get_contents('https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=alo2zen&key=' . $api_key);
        $channels = json_decode($json);
        $playlist_id = $channels->items[0]->contentDetails->relatedPlaylists->uploads;

        //search playlist items of upload channel
        $json = file_get_contents('https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&maxResults=' . $max_results . '&playlistId=' . $playlist_id . '&key=' . $api_key);
        $items = json_decode($json)->items;

        foreach ($items as $item) {
            // search videos of the playlist items of the upload channel
            $json = file_get_contents('https://www.googleapis.com/youtube/v3/videos?part=id,snippet,contentDetails,status&id=' . $item->contentDetails->videoId . '&maxResults=' . $max_results . '&key=' . $api_key);
            $video = json_decode($json);
            array_push($videos, $video);
        }

        return response()->json($videos);
    }
}
