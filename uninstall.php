<?php
/*
* If the uniinstall file is not called from the wordpress output
*/
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit ();
}
	/*
	* Clearing data from the options table
	*/
	delete_option( 'crrq_wp_slug_post_type_custom_language' );
?>