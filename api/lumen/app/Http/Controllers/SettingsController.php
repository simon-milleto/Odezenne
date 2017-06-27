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
     * Sets the API settings
     *
     * @param $request Request
     * @return \Illuminate\Http\Response
     */
    public function setSettings(Request $request)
    {
        foreach ($request->all() as $label => $value) {
            Settings::updateOrCreate(
                ['label' => $label],
                ['value' => $value]
            );
        }
        

        return response()->json(array('valid' => true));
    }
}
