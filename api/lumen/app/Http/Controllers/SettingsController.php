<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings;


class SettingsController extends Controller
{
    /**
     * Returns a list of all the settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Settings::all();

        return response()->json($settings);
    }

    /**
     * Returns the Soundcloud Client Id
     *
     * @return string
     */
    public function getSoundcloudClientId()
    {
        $soundcloudClientId = Settings::where('label', 'soundcloud_client_id')->limit(1)->pluck('value');

        return response()->json($soundcloudClientId[0]);
    }

    /**
     * Sets the Soundcloud Client Id
     *
     * @param $request Request
     * @return \Illuminate\Http\Response
     */
    public function setSoundcloudClientId(Request $request)
    {
        $soundcloudClientId = Settings::firstOrCreate(['label' => 'soundcloud_client_id']);

        $soundcloudClientId->setAttribute('value', $request->all()['value']);
        $soundcloudClientId->save();

        return response()->json($soundcloudClientId);
    }
}
