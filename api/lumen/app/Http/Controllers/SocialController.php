<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings;

use \TwitterAPIExchange;

class SocialController extends Controller
{
  /**
  * Returns a list of odezenne tweets
  *
  * @param  Request  $request
  * @return \Illuminate\Http\Response
  */
  public function twitterFeed(Request $request)
  {
    $count = $request->input('count') ? $request->input('count') : 5;

    $settings = array(
      'oauth_access_token' => env('OAUTH_ACCESS_TOKEN'),
      'oauth_access_token_secret' => env('OAUTH_ACCESS_TOKEN_SECRET'),
      'consumer_key' => env('CONSUMER_KEY'),
      'consumer_secret' => env('CONSUMER_SECRET'),
    );

    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $requestMethod = 'GET';
    $getfield = "?user_id=264754437&count={$count}&include_rts=false";

    $twitter = new TwitterAPIExchange($settings);
    $tweets = $twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

    $tweets = json_decode($tweets);

    foreach ($tweets as &$tweet) {
      $url = 'https://publish.twitter.com/oembed';
      $getfield= "url=https://twitter.com/{$tweet->user->screen_name}/status/{$tweet->id}&omit_script=true";
      $html = $twitter->setGetfield($getfield)
      ->buildOauth($url, $requestMethod)
      ->performRequest();
      $tweet->html = json_decode($html)->html;
    }

    return response()->json($tweets);
  }

  /**
  * Returns a list of odezenne fan's tweets
  *
  * @param  Request  $request
  * @return \Illuminate\Http\Response
  */
  public function fanTweets(Request $request)
  {
    $count = $request->input('count') ? $request->input('count') : 5;

    $settings = array(
      'oauth_access_token' => env('OAUTH_ACCESS_TOKEN'),
      'oauth_access_token_secret' => env('OAUTH_ACCESS_TOKEN_SECRET'),
      'consumer_key' => env('CONSUMER_KEY'),
      'consumer_secret' => env('CONSUMER_SECRET'),
    );

    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $requestMethod = 'GET';
    $getfield = "?q=%23odezenne&result_type=mixed&count={$count}";

    $twitter = new TwitterAPIExchange($settings);
    $tweets = $twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

    $tweets = json_decode($tweets);

    foreach ($tweets->statuses as &$tweet) {
      $url = 'https://publish.twitter.com/oembed';
      $getfield= "url=https://twitter.com/{$tweet->user->screen_name}/status/{$tweet->id}&omit_script=true";
      $html = $twitter->setGetfield($getfield)
      ->buildOauth($url, $requestMethod)
      ->performRequest();
      $tweet->html = json_decode($html)->html;
    }

    return response()->json($tweets->statuses);
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
