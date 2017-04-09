<?php

namespace App\Services;

use Illuminate\Http\Request;

class SoundcloudService
{
    private $_clientId;

    private $_soundcloudApiEndpoint;

    private $_curlOptions;

    /**
     * Class constructor
     * @param $clientId string Soundcloud Client Id
     */
    function __construct($clientId)
    {
        $this->_clientId = $clientId;
        $this->_soundcloudApiEndpoint = 'https://api.soundcloud.com/';
        $this->_setDefaultCurlOptions();
    }

    protected function _setDefaultCurlOptions()
    {
        $this->_curlOptions = array(
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => ''
        );
    }

    protected function _constructUrl($path) {
        return $path.'?format=json&client_id='.$this->_clientId;
    }

    protected function _request($path, $curlOptions = array())
    {
        $options = $this->_curlOptions;
        $options += $curlOptions;

        $curl = curl_init($path);
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        curl_close($curl);

        return substr($response, strpos($response, "\r\n\r\n")+4);
    }

    /**
     * Display a listing of the resource.
     * @param $trackId string Id of the track to fetch
     * @return \Illuminate\Http\Response
     */
    public function getTrackInformation($trackId)
    {
        $baseUrl = $this->_soundcloudApiEndpoint.'tracks/'.$trackId;
        $formattedUrl = $this->_constructUrl($baseUrl);

        $data = $this->_request($formattedUrl);

        return json_decode($data, true);
    }
}
