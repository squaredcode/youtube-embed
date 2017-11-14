<?php
/**
* API Access
*
* Function to access YouTube API for video data
*
* @package	youtube-embed
*
* @since	5.0
*
* @param	string	$video_id		The ID of the video
* @param	string	$api_key		Allows an alternative API key to be specified
* @param	string	$no_cache		Suppresses caching
* @param	string	$cache_time		If the calling function already has the cache time, send it along, otherwise it is fetched
* @param	string	$playlist		If a playlist, this is the name
* @return	array					Array of video information
*/

function ye_get_api_data( $video_id, $api_key = '', $no_cache = false, $cache_time = '', $playlist = '' ) {

	// Check for cached data. If exists, return that

	if ( $playlist == '' ) {
		$key = 'api_' . $video_id;
	} else {
		$key = 'api_' . $playlist;
	}

	$cache = ye_get_transient( $key );

	if ( $cache !== false ) { return $cache; }

	// Calculate the default video type

	$valid = false;
	$type = '';
	if ( strlen( $video_id ) == 11 ) {
		$type = 'v';
		if ( preg_match('/^[a-zA-Z0-9_-]{11}$/', $video_id) == 0 ) {
			$valid = false;
		} else {
			$valid = true;
		}
	} else {
		if ( ( strtolower( substr( $video_id, 0, 2 ) ) == 'pl' && strlen( $video_id ) == 34 ) or ( strlen( $video_id ) == 16 ) or ( $playlist != '' ) ) {
			$type = 'p';
			if ( $playlist == '' && preg_match( '/[^A-Za-z0-9_-]/', $video_id ) == 1 ) {
				$valid = false;
			} else {
				$valid = true;
			}
		}
	}

	// Set the default values

	$return_data = array(
						'restricted' => '',
						'title' => get_the_title(),
						'description' => get_the_title(),
						'published' => get_the_date( 'c' ),
						'valid' => $valid,
						'type' => $type,
						'api' => false,
						'thumb_default' => '',
						'thumb_medium' => '',
						'thumb_high' => '',
						'thumb_standard' => '',
						'thumb_maxres' => ''
						);

	// Set the default thumbnail URLs

	if ( ( $type == 'v' ) or ( $playlist != '' ) ) {

		$return_data[ 'thumb_default' ] = 'https://i.ytimg.com/vi/' . $video_id . '/default.jpg';
		$return_data[ 'thumb_medium' ] = 'https://i.ytimg.com/vi/' . $video_id . '/mqdefault.jpg';
		$return_data[ 'thumb_high' ] = 'https://i.ytimg.com/vi/' . $video_id . '/hqdefault.jpg';
		$return_data[ 'thumb_standard' ] = 'https://i.ytimg.com/vi/' . $video_id . '/sddefault.jpg';
		$return_data[ 'thumb_maxres' ] = 'https://i.ytimg.com/vi/' . $video_id . '/maxresdefault.jpg';

	}

	// If a user defined playlist, go no further

	if ( $playlist != '' ) { return $return_data; }

	// Get the API key

	if ( $api_key == '' ) {
		$options = ye_get_general_defaults();
		$api_key = $options[ 'api' ];
		if ( $api_key == '' ) { return $return_data; }
	}

	// Get the API results and convert from JSON to a PHP array

	$url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id=' . $video_id . '&key=' . $api_key;
	$api_data = json_decode( @file_get_contents( $url ), true );
	if ( $api_data == '' ) { return $return_data; }

	if ( !isset( $api_data[ 'items' ][ '0' ] ) ) {

		$url = 'https://www.googleapis.com/youtube/v3/playlists?part=snippet,contentDetails&id=' . $video_id . '&key=' . $api_key;
		$api_data = json_decode( file_get_contents( $url ), true );
		if ( $api_data == '' ) { return $return_data; }

		if ( isset( $api_data[ 'items' ][ '0' ] ) ) {
			$id_found = true;
			$return_data[ 'type' ] = 'p';
		} else {
			$id_found = false;
		}
	} else {
		$id_found = true;
		$return_data[ 'type' ] = 'v';
	}

	$return_data[ 'api' ] = true; // Show that API data was used

	// Extract out the API data

	if ( $id_found ) {

		$return_data[ 'valid' ] = true;

		// Strip off the first two dimensions of the array

		if ( isset( $api_data[ 'items' ][ '0' ] ) ) { $api_data = $api_data[ 'items' ][ '0' ]; }

		// If set, assign the appropriate API data to the return array

		if ( isset( $api_data[ 'snippet' ][ 'publishedAt' ] ) ) { $return_data[ 'published' ] = $api_data[ 'snippet' ][ 'publishedAt' ]; }

		if ( isset( $api_data[ 'snippet' ][ 'title' ] ) ) { $return_data[ 'title' ] = htmlspecialchars( $api_data[ 'snippet' ][ 'title' ] ); }

		if ( isset( $api_data[ 'snippet' ][ 'description' ] ) ) { $return_data[ 'description' ] = htmlspecialchars( $api_data[ 'snippet' ][ 'description' ] ); }

		if ( isset( $api_data[ 'contentDetails' ][ 'contentRating' ][ 'ytRating' ] ) ) { $return_data[ 'restricted' ] = true; } else { $return_data[ 'restricted' ] = false; }

		if ( isset( $api_data[ 'snippet' ][ 'thumbnails' ][ 'default' ][ 'url' ] ) ) { $return_data[ 'thumb_default' ] = $api_data[ 'snippet' ][ 'thumbnails' ][ 'default' ][ 'url' ]; }
		if ( isset( $api_data[ 'snippet' ][ 'thumbnails' ][ 'medium' ][ 'url' ] ) ) { $return_data[ 'thumb_medium' ] = $api_data[ 'snippet' ][ 'thumbnails' ][ 'medium' ][ 'url' ]; }
		if ( isset( $api_data[ 'snippet' ][ 'thumbnails' ][ 'high' ][ 'url' ] ) ) { $return_data[ 'thumb_high' ] = $api_data[ 'snippet' ][ 'thumbnails' ][ 'high' ][ 'url' ]; }
		if ( isset( $api_data[ 'snippet' ][ 'thumbnails' ][ 'standard' ][ 'url' ] ) ) { $return_data[ 'thumb_standard' ] = $api_data[ 'snippet' ][ 'thumbnails' ][ 'standard' ][ 'url' ]; }
		if ( isset( $api_data[ 'snippet' ][ 'thumbnails' ][ 'maxres' ][ 'url' ] ) ) { $return_data[ 'thumb_maxres' ] = $api_data[ 'snippet' ][ 'thumbnails' ][ 'maxres' ][ 'url' ]; }


	} else {

		$return_data[ 'valid' ] = false;

	}

	// If the description is missing or blank, set it to the title

	if ( !isset( $return_data[ 'description' ] ) or trim( $return_data[ 'description' ] ) == '' ) { $return_data[ 'description' ] = $return_data[ 'title' ]; }

	// Save the non-cached data (if required), but only if the API retrival was successful

	$options = ye_get_general_defaults();

	if ( $options[ 'api_cache' ] != 0 && $id_found ) {

		$cache = $options[ 'api_cache' ] * 60 * 60 * 24;

		ye_set_transient( $key, $return_data, $cache );

	}

	return $return_data;
}
?>
