<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Track;

class TracksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tracks = Track::all();
        foreach ($tracks as $track) {
            $track->setAttribute('total_time', floor($track->getAttributeValue('total_time') / 1000));
        }

        return response()->json($tracks);
    }

    /**
     * Create Tracks
     * @param $request Request
     * @return \Illuminate\Http\Response
     */
    public function createTracks(Request $request){
        $tracks = array();

        foreach ($request->all() as $track) {
            parse_str($track, $formattedTrack);

            $Track = Track::create($formattedTrack);

            array_push($tracks, $Track);
        }

        return response()->json($tracks);
    }
}