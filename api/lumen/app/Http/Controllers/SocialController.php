<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings;

use \TwitterAPIExchange;
use \Instagram;

session_start();

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

    $oauth_access_token = Settings::where('label', 'twitter_oauth_access_token')->limit(1)->pluck('value')[0];
    $oauth_access_token_secret = Settings::where('label', 'twitter_oauth_access_token_secret')->limit(1)->pluck('value')[0];
    $consumer_key = Settings::where('label', 'twitter_consumer_key')->limit(1)->pluck('value')[0];
    $consumer_secret = Settings::where('label', 'twitter_consumer_secret')->limit(1)->pluck('value')[0];
    $user_name = Settings::where('label', 'twitter_user_name')->limit(1)->pluck('value')[0];

    $settings = array(
      'oauth_access_token' => $oauth_access_token,
      'oauth_access_token_secret' => $oauth_access_token_secret,
      'consumer_key' => $consumer_key,
      'consumer_secret' => $consumer_secret,
    );

    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $requestMethod = 'GET';
    $getfield = "?screen_name={$user_name}&count={$count}&include_rts=false";

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

    $oauth_access_token = Settings::where('label', 'twitter_oauth_access_token')->limit(1)->pluck('value')[0];
    $oauth_access_token_secret = Settings::where('label', 'twitter_oauth_access_token_secret')->limit(1)->pluck('value')[0];
    $consumer_key = Settings::where('label', 'twitter_consumer_key')->limit(1)->pluck('value')[0];
    $consumer_secret = Settings::where('label', 'twitter_consumer_secret')->limit(1)->pluck('value')[0];

    $settings = array(
      'oauth_access_token' => $oauth_access_token,
      'oauth_access_token_secret' => $oauth_access_token_secret,
      'consumer_key' => $consumer_key,
      'consumer_secret' => $consumer_secret,
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
        $username = Settings::where('label', 'youtube_username')->limit(1)->pluck('value')[0];

        if (empty($max_results) || $max_results <= 0) {
            $max_results = 10;
        }

        //search channels of user
        $json = file_get_contents('https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=' . $username . '&key=' . $api_key);
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

    /**
     * Returns a list of instagram naked pictures
     *
     * @return \Illuminate\Http\Response
     */
    public function instagramFeed()
    {
        $token = Settings::where('label', 'instagram_token')->limit(1)->pluck('value')[0];
        $url = "https://api.instagram.com/v1/users/self/?access_token=$token";
        $max_results = Settings::where('label', 'instagram_max_results')->limit(1)->pluck('value')[0];

        $images = [];

        $curl_connection = curl_init($url);
        curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);

        //Data are stored in $data
        $data = json_decode(curl_exec($curl_connection), true);
        curl_close($curl_connection);

        $userId = $data['data']['id'];

        $json_profile = file_get_contents("https://api.instagram.com/v1/users/$userId/?access_token=$token");
        $json = file_get_contents("https://api.instagram.com/v1/users/$userId/media/recent/?access_token=" . $token . "&$max_results");
        $a_json_profile = json_decode($json_profile, true);
        $a_json = json_decode($json, true);

        $i = 0;
        foreach ($a_json['data'] as $key => $value) {
            if ($i < $max_results) {
            $images[$i]['post_url'] = $value['link'];
            $images[$i]['images_url'] = $value['images']['standard_resolution']['url'];
            $images[$i]['alt'] = $value['caption']['text'];

            $i++;
            }
        }

        return response()->json($images);
    }
}
