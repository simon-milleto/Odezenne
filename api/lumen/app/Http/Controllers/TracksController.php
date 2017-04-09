<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Track;
use App\Services\SoundcloudService;


class TracksController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * @param $soundcloud SoundcloudService
     * @return \Illuminate\Http\Response
     */
    public function createTracks(Request $request)
    {
        $tracks = $request->all();
        $createdTracks = array();
        $existingTracks = Track::all();
        $soundcloud = new SoundcloudService('aeb5b3f63ac0518f8362010439a77ca1');

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
                'artwork_url' => $trackInformation['artwork_url'],
                'track_url' => $trackInformation['permalink_url'],
                'stream_url' => $trackInformation['stream_url'],
                'total_time' => floor($trackInformation['duration'] / 1000),
                'status' => $formattedTrack['status']
            );

            $Track = Track::create($formattedTrackInformation);
            array_push($createdTracks, $Track);
        }

        return response()->json($createdTracks);
    }
}
