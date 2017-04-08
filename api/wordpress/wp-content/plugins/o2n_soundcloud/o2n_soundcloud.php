<?php
/**
 * Plugin Name: O2N Soundcloud
 * Plugin URI:
 * Description: Activate or disable Souncloud tracks users will be able to listen to on the website.
 * Version: 0.0.1
 * Author: Bram van Osta
 * Author URI: http://ecvdigital.fr/
 * License: GPL2
 */

add_action('admin_menu', 'o2n_soundcloud_menu');

function o2n_soundcloud_menu() {
    add_menu_page('Soundcloud', 'Soundcloud', 'administrator', 'soundcloud_settings', 'o2n_soundcloud_settings', 'dashicons-controls-play');
}

function o2n_soundcloud_settings() {
    //
}