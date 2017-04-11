<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Track;
use App\Settings;
use App\Services\SoundcloudService;


class TracksController extends Controller
{
    /**
     * Returns a list of all active tracks
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tracks = Track::where('status', 1)->inRandomOrder()->get();

        return response()->json($tracks);
    }

    /**
     * Create Tracks
     * @param $request Request
     * @return \Illuminate\Http\Response
     */
    public function createTracks(Request $request)
    {
        $tracks = $request->all();
        $createdTracks = array();
        $existingTracks = Track::all();
        $soundcloudClientId = Settings::where('label', 'soundcloud_client_id')->limit(1)->pluck('value');
        $soundcloud = new SoundcloudService($soundcloudClientId[0]);

        foreach ($tracks as $trackKey => $track) {
            foreach ($existingTracks as $existingTrackKey => $existingTrack) {
                parse_str($track, $formattedTrack);
                if ($existingTrack->getAttribute('track_id') === intval($formattedTrack['track_id'])) {
                    $existingTrack->setAttribute('status', 1);
                    $existingTrack->save();
                    unset($tracks[$trackKey]);
                    unset($existingTracks[$existingTrackKey]);
                } else {
                    $existingTrack->setAttribute('status', 0);
                    $existingTrack->save();
                }
            }
        }

        foreach ($tracks as $track) {
            parse_str($track, $formattedTrack);
            $trackInformation = $soundcloud->getTrackInformation(intval($formattedTrack['track_id']));

            $formattedTrackInformation = array(
                'track_id' => $trackInformation['id'],
                'title' => $trackInformation['title'],
                'artist' => $trackInformation['user']['username'],
                'artwork_url' => $trackInformation['artwork_url'],
                'track_url' => $trackInformation['permalink_url'],
                'stream_url' => $trackInformation['stream_url'],
                'status' => true
            );

            var_dump($formattedTrackInformation);

            $Track = Track::create($formattedTrackInformation);
            array_push($createdTracks, $Track);
        }

        return response()->json($createdTracks);
    }
}
