<?php
Class Soundcloud
{
    /**
     * Soundcloud client id
     * @var string
     */
    private $_clientId;

    /**
     * Get all tracks or not
     * @var boolean
     */
    private $_enableAllTracks;

    /**
     * Curl options
     * @var array
     */
    private $_curlOptions;

    /**
     * Soundcloud API url
     * @var string
     */
    private $_apiEndpoint;

    /**
     * Class constructor
     * @access public
     */
    function __construct()
    {
        if (empty(get_option( 'soundcloud_options' )['client_id'])) {
            throw new Services_Soundcloud_Missing_Client_Id_Exception();
        }
        $this->_clientId = get_option( 'soundcloud_options' )['client_id'];
        $this->_enableAllTracks = get_option( 'soundcloud_options' )['enable_all_tracks'];
        $this->_setDefaultCurlOptions();
        $this->_apiEndpoint = 'https://api.soundcloud.com/';
    }

    protected function _setDefaultCurlOptions() {
        $this->_curlOptions = array(
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => ''
        );
    }

    protected function _constructUrl($path) {
        return $path.'?format=json&client_id='.$this->_clientId;
    }

    protected function _request($path, $curlOptions = array()) {
        $options = $this->_curlOptions;
        $options += $curlOptions;

        // Get cURL resource
        $curl = curl_init($path);
        // Set cURL options
        curl_setopt_array($curl, $options);
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        return substr($resp, strpos($resp, "\r\n\r\n")+4);
    }

    /**
     * Send a GET HTTP request
     * @param $track_id string Id of the track to get information about
     * @return mixed
     */
    function getTrack($track_id)
    {
        $baseUrl = $this->_apiEndpoint.'tracks/'.$track_id;
        $formattedUrl = $this->_constructUrl($baseUrl);

        $data = $this->_request($formattedUrl);

        return $data;
    }

    function getAllTracks($user_id)
    {
        $baseUrl = $this->_apiEndpoint.'users/'.$user_id.'/tracks';
        $formattedUrl = $this->_constructUrl($baseUrl);

        $data = $this->_request($formattedUrl);

        return json_decode($data, true);
    }
}

class Services_Soundcloud_Missing_Client_Id_Exception extends Exception {
    /**
     * Default message.
     * @var string
     */
    protected $message = 'All requests must include a client ID. Please set the client ID in the Soundcloud Page in Wordpress';
}