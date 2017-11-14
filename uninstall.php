<?php
/**
* Uninstaller
*
* Uninstall the plugin by removing any options from the database
*
* @package	youtube-embed
* @since	2.0
*/

// If the uninstall was not called by WordPress, exit

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit(); }

// Read the general options (will tell us how many profile and list options there should be

$options = get_option( 'youtube_embed_general' );

// If the general options existed, delete it!

if ( is_array( $options ) ) {

	delete_option( 'youtube_embed_general' );

	// If the number of profiles field exists, delete each one in turn

	if ( isset( $options[ 'profile_no' ] ) ) {
		$loop = 0;
		while ( $loop <= $options[ 'profile_no' ] ) {
			delete_option( 'youtube_embed_profile' . $loop );
			$loop ++;
		}
	}

	// If the number of lists field exists, delete each one in turn

	if ( isset( $options[ 'list_no' ] ) ) {
		$loop = 1;
		while ( $loop <= $options[ 'list_no' ] ) {
			delete_option( 'youtube_embed_list' . $loop );
			$loop ++;
		}
	}
}

// Delete all other options

delete_option( 'youtube_embed_general' );
delete_option( 'youtube_embed_shortcode' );
delete_option( 'youtube_embed_shortcode_admin' );
delete_option( 'youtube_embed_shortcode_site' );
delete_option( 'youtube_embed_version' );

// Remove any transient data

global $wpdb;
$sql = "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_youtubeembed_%' OR option_name LIKE '_transient_timeout_youtubeembed_%'";
$wipe = $wpdb -> query( $sql );
?>
