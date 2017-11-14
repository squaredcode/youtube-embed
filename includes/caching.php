<?php
/**
* Transient Functions
*
* Functions to save cache data and housekeep it
*
* @package	youtube-embed
*/

/**
* Save a transient
*
* Generate a transient name and save it
*
* @since	5.0
*
* @param	string	$name	The name of the transient
* @param	string	$data	The data to store
* @param	string	$cache	How long to cache the data for
* @param	string	$hash	Whether to hash the name (true or false)
* @return	string			True or false, indicating success
*/

function ye_set_transient( $name, $data, $cache, $hash = false ) {

	if ( $hash ) { $name = hash( 'ripemd128', $name ); }

	$result = set_transient( 'youtubeembed_' . $name, $data, $cache);

	return $result;
}

/**
* Get a transient
*
* Generate a transient name and fetch it
*
* @since	5.0
*
* @param	string	$name	The name of the transient
* @param	string	$hash	Whether to hash the name (true or false)
* @return	string			The transient result or false, if failed
*/

function ye_get_transient( $name, $hash = false ) {

	if ( $hash ) { $name = hash( 'ripemd128', $name ); }

	$result = get_transient( 'youtubeembed_' . $name );

	return $result;
}

/**
* Set up the scheduler
*
* Set up the scheduler for midnight to run the housekeeping
*
* @since	5.0
*/

function ye_set_up_scheduler() {

	if ( !wp_next_scheduled( 'housekeep_ye_transients' ) ) {
		wp_schedule_event( strtotime( '00:00' ) , 'daily', 'housekeep_ye_transients' );
	}
}

add_action( 'init', 'ye_set_up_scheduler' );

/**
* Housekeep the transients
*
* Remove any expired transients, relevant to this plugin
*
* @since	5.0
*/

function ye_housekeep_transients() {

	$sql = "
		DELETE
			a, b
		FROM
			{$wpdb->options} a, {$wpdb->options} b
		WHERE
			a.option_name LIKE '%_transient_youtubeembed_%' AND
			a.option_name NOT LIKE '%_transient_timeout_youtubeembed_%' AND
			b.option_name = CONCAT(
				'_transient_timeout_',
				SUBSTRING(
					a.option_name,
					CHAR_LENGTH('_transient_') + 1
				)
			)
		AND b.option_value < UNIX_TIMESTAMP()
	";

	$clean = $wpdb -> query( $sql );

	return;
}

add_action( 'ye_housekeep_transients', 'ye_clean_transients' );
?>