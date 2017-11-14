<?php
/**
* Generate Download Code
*
* Create code to allow a YouTube video to be downloaded
*
* @package	youtube-embed
* @since	2.0
*
* @param    string	$id					YouTube video ID
* @return	string						Download HTML
*/

function ye_generate_download_code( $id ) {

	return ye_generate_vinfo_code( $id, '%download%' );

}

/**
* Generate video short URL
*
* Create a short URL to a YouTube video
*
* @package	YouTube-Embed
* @since	2.0
*
* @uses		ye_extract_id				Extract an ID from a string
* @uses		ye_get_api_data				Get API data
* @uses		ye_error					Display an error
*
* @param    string	$id					YouTube video ID
* @return	string	$youtube_code		Code
*/

function ye_generate_shorturl_code( $id ) {

	return ye_generate_vinfo_code( $id, '%shorturl%' );

}

/**
* Generate Thumbnail Code
*
* Generate XHTML compatible YouTube video thumbnail
*
* @package	YouTube-Embed
* @since	2.0
*
* @uses		ye_extract_id				Extract an ID from a string
* @uses		ye_get_api_data				Get API data
* @uses		ye_error					Display an error
*
* @param    string	$array				Array of parameters
* @return	string	$youtube_code		Code
*/

function ye_generate_thumbnail_code( $array ) {

	// Set defaults then merge with passed array. Finally, split array into individual variables

	$default = array( 'id' => '', 'style' => '', 'class' => '', 'rel' => '', 'target' => '', 'width' => '', 'height' => '', 'alt' => '', 'version' => '', 'nolink' => false );

	$array = array_merge( $default, $array );

	extract( $array );

	// Create the relevant version name to find it in the API array

	$version = strtolower( $version );
	if ( ( $version != 'default' ) && ( $version != 'medium' ) && ( $version != 'high' ) && ( $version != 'standard' ) && ( $version != 'maxres' ) ) { $version = 'default'; }

	// Now create the required code

	if ( $alt == '' ) { $alt = '%title%'; }
	if ( !$nolink ) {
		$youtube_code = '<a href="https://www.youtube.com/watch?v=' . $id . '"';
		if ( $style != '' ) { $youtube_code .= ' style="' . $style . '"'; }
		if ( $class != '' ) { $youtube_code .= ' class="' . $class . '"'; }
		if ( $rel != '' ) { $youtube_code .= ' rel="' . $rel . '"'; }
		if ( $target != '' ) { $youtube_code .= ' target="' . $target . '"'; }
		$youtube_code .= '>';
	}
	$youtube_code .= '<img src="%thumb_' . $version . '%"';
	if ( $width != '' ) { $youtube_code .= ' width="' . $width . '"'; }
	if ( $height != '' ) { $youtube_code .= ' height="' . $height . '"'; }
	$youtube_code .= ' alt="' . $alt . '"/>';
	if ( !$nolink ) { $youtube_code .= '</a>'; }

	$youtube_code = ye_generate_vinfo_code( $id, $youtube_code) ;

	return $youtube_code;
}

/**
* Generate Video Information
*
* Output video information
*
* @since	5.0
*
* @uses		ye_extract_id				Extract an ID from a string
* @uses		ye_get_api_data				Get API data
* @uses		ye_error					Display an error
*
* @param    string	$id					Video ID
* @param	string	$text				The text containing the information requirements
* @return	string	$output				The resulting output
*/

function ye_generate_vinfo_code( $id, $text ) {

	if ( $id == '' ) {
		return ye_error(  __( 'No YouTube ID was specified.', 'youtube-embed' ) );
	}

	// Extract the ID if a full URL has been specified

	$id = ye_extract_id( $id );

	// Get the data from the API

	$data = ye_get_api_data( $id );

	// Exit out with an error if the ID was not valid

	if ( !$data[ 'valid' ] ) {
		return ye_error( sprintf( __( 'The YouTube ID of %s is invalid.', 'youtube-embed' ), $id ) );
	}

	// Now replace any tags in the text with the relevant information

	$output = $text;

	$output = str_replace( '%title%', $data[ 'title' ], $output );
	$output = str_replace( '%description%', $data[ 'description' ], $output );
	$output = str_replace( '%url%', 'https://www.youtube.com/watch?v=' . $id, $output );
	$output = str_replace( '%shorturl%', 'https://youtu.be/' . $id, $output );
	$output = str_replace( '%download%', 'http://keepvid.com/?url=https://www.youtube.com/watch?v=' . $id, $output );

	$output = str_replace( '%thumb_default%', $data[ 'thumb_default' ], $output );
	$output = str_replace( '%thumb_medium%', $data[ 'thumb_medium' ], $output );
	$output = str_replace( '%thumb_high%', $data[ 'thumb_high' ], $output );
	$output = str_replace( '%thumb_standard%', $data[ 'thumb_standard' ], $output );
	$output = str_replace( '%thumb_maxres%', $data[ 'thumb_maxres' ], $output );

	return $output;
}
?>
