<?php

/**
 * Hide useless menus for Odezenne users
 */
add_action( 'admin_menu', 'my_remove_menu_pages' );
function my_remove_menu_pages() {
    global $user_ID;
 
    if ( !current_user_can( 'administrator' ) ) {
        remove_menu_page('tools.php');
        remove_menu_page('edit-comments.php');
    }
}