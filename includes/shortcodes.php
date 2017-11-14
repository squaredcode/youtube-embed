<?php
/**
* Shortcodes
*
* Define the various shortcodes
*
* @package	youtube-embed
* @since	2.0
*/

/**
* Default Video shortcode
*
* Main [youtube] shortcode to display video
*
* @since	2.0
*
* @uses		ye_video_shortcode			Action the shortcode parameters
*
* @param    string		$paras			Shortcode parameters
* @param	string		$content		Shortcode content
* @return   string						YouTube embed code
*/

function ye_video_shortcode_default( $paras = '', $content = '' ) {

	return do_shortcode( ye_video_shortcode( $paras, $content ) );

}

add_shortcode( 'youtube', 'ye_video_shortcode_default' );

/**
* Alternative Video shortcode 1
*
* 1st alternative shortcode to display video
*
* @since	2.0
*
* @uses		ye_video_shortcode			Action the shortcode parameters
*
* @param    string		$paras			Shortcode parameters
* @param	string		$content		Shortcode content
* @return   string						YouTube embed code
*/

function ye_video_shortcode_alt( $paras = '', $content = '' ) {

	return do_shortcode( ye_video_shortcode( $paras, $content, '', true ) );

}

$shortcode = ye_get_shortcode();

if ( isset( $shortcode ) && $shortcode != '' ) { add_shortcode( $shortcode, 'ye_video_shortcode_alt' ); }

/**
* Video shortcode
*
* Use shortcode parameters to embed a YouTube video or playlist
*
* @since	2.0
*
* @uses		ye_get_embed_type			Get the embed type
* @uses		ye_set_autohide			Get the autohide parameter
* @uses     ye_get_general_defaults    Set default options
* @uses		ye_generate_youtube_code	Generate the embed code
*
* @param    string		$paras			Shortcode parameters
* @param	string		$content		Shortcode content
* @param	string		$alt_shortcode	The number of the alternative shortcode used
* @return   string						YouTube embed code
*/

function ye_video_shortcode( $paras = '', $content = '', $callback = '', $alt_shortcode = false ) {

	extract( shortcode_atts( array( 'width' => '', 'height' => '', 'fullscreen' => '', 'related' => '', 'autoplay' => '', 'loop' => '', 'start' => '', 'info' => '', 'annotation' => '', 'cc' => '', 'style' => '', 'stop' => '', 'disablekb' => '', 'ratio' => '', 'autohide' => '', 'controls' => '', 'profile' => '', 'id' => '', 'url' => '', 'rel' => '', 'fs' => '', 	'cc_load_policy' => '', 'iv_load_policy' => '', 'showinfo' => '', 'youtubeurl' => '', 'template' => '', 'list' => '', 'color' => '', 'theme' => '', 'dynamic' => '', 'responsive' => '', 'h' => '', 'w' => '', 'search' => '', 'user' => '', 'modest' => '', 'playsinline' => '', 'html5' => '' ), $paras ) );

	// If no profile specified and an alternative shortcode used, get that shortcodes default profile

	if ( ( $profile == '' ) && ( $alt_shortcode ) ) {

		// Get general options

		$options = ye_get_general_defaults();
		$profile = $options[ 'alt_profile' ];
	}

	// If an alternative field is set, use it

	if ( ( $id != '' ) && ( $content == '' ) ) { $content = $id; }
	if ( ( $url != '' ) && ( $content == '' ) ) { $content = $url; }
	if ( ( $youtubeurl != '' ) && ( $content == '' ) ) { $content = $youtubeurl; }

	if ( ( $h != '' ) && ( $height == '' ) ) { $height = $h; }
	if ( ( $w != '' ) && ( $width == '' ) ) { $width = $w; }

	if ( ( $rel != '' ) && ( $related == '' ) ) { $related = $rel;}
	if ( ( $fs != '' ) && ( $fullscreen == '' ) ) { $fullscreen = $fs;}
	if ( ( $cc_load_policy != '' ) && ( $cc == '' ) ) { $cc = $cc_load_policy;}
	if ( ( $iv_load_policy != '' ) && ( $annotation == '' ) ) { $annotation = $iv_load_policy;}
	if ( ( $showinfo != '' ) && ( $info == '' ) ) { $info = $showinfo;}

	// If ID was not passed in the content and the first parameter is set, assume that to be the ID

	if ( ( $content == '' ) && ( $paras[ 0 ] != '' ) ) {
		$content = $paras[ 0 ];
		if  ( (substr( $content, 0, 1 ) == ":" ) or ( substr( $content, 0, 1 ) == "=" ) ) { $content = substr( $content, 1 ); }

		if ( array_key_exists( 1, $paras ) ) {
			if ( $paras[ 1 ] != '' ) { $width = $paras[ 1 ]; }
		}
		if ( array_key_exists( 2, $paras ) ) {
			if ( $paras[ 2 ] != '' ) { $height = $paras[ 2 ]; }
		}
	}

	// If no responsive parameter specified use the deprecated dynamic parameter instead

	if ( $responsive == '' ) { $responsive = $dynamic; }

	// Create YouTube code

	$array = array(
					'id' => $content,
					'width' => $width,
					'height' => $height,
					'fullscreen' => ye_convert( $fullscreen ),
					'related' => ye_convert( $related ),
					'autoplay' => ye_convert( $autoplay ),
					'loop' => ye_convert( $loop ),
					'start' => $start,
					'info' => ye_convert( $info ),
					'annotation' => ye_convert_3( $annotation ),
					'cc' => ye_convert( $cc ),
					'style' => $style,
					'stop' => $stop,
					'disablekb' => ye_convert( $disablekb ),
					'ratio' => $ratio,
					'autohide' => ye_set_autohide( $autohide ),
					'controls' => $controls,
					'profile' => $profile,
					'list_style' => $list,
					'template' => $template,
					'color' => $color,
					'theme' => $theme,
					'responsive' => ye_convert( $responsive ),
					'search' => ye_convert( $search ),
					'user' => ye_convert( $user ),
					'modest' => ye_convert( $modest ),
					'playsinline' => ye_convert( $playsinline ),
					'html5' => ye_convert( $html5 )
					);

	$youtube_code = ye_generate_youtube_code( $array );

    return apply_filters( 'a3_lazy_load_html', do_shortcode( $youtube_code ) );
}

/**
* Return a thumbnail URL
*
* Shortcode to return the URL for a thumbnail
*
* @since	2.0
*
* @uses		ye_generate_thumbnail_code	Generate the thumbnail code
*
* @param    string		$paras			Shortcode parameters
* @param	string		$content		Shortcode content
* @return   string						YouTube thumbnail code
*/

function ye_thumbnail_sc( $paras = '', $content = '' ) {

	extract( shortcode_atts( array( 'style' => '', 'class' => '', 'rel' => '', 'target' => '', 'width' => '', 'height' => '', 'alt' => '', 'version' => '', 'nolink' => '' ), $paras ) );

	$array = array(
					'id' => $content,
					'style' => $style,
					'class' => $class,
					'rel' => $rel,
					'target' => $target,
					'width' => $width,
					'height' => $height,
					'alt' => $alt,
					'version' => $version,
					'nolink' => $nolink
					);

	return do_shortcode( ye_generate_thumbnail_code( $array ) );

}

add_shortcode( 'youtube_thumb', 'ye_thumbnail_sc' );

/**
* Video Information Shortcode
*
* Shortcode to return video information
*
* @since	5.0
*
* @uses		ye_generate_vinfo_code		Generate the video information code
*
* @param    string		$paras			Shortcode parameters
* @param	string		$content		Shortcode content
* @return   string						Video information code
*/

function ye_vinfo_sc( $paras = '', $content = '' ) {

	extract( shortcode_atts( array( 'id' => '' ), $paras ) );

	return do_shortcode( ye_generate_vinfo_code( $id, $content ) );

}

add_shortcode( 'vinfo', 'ye_vinfo_sc' );

/**
* Short URL shortcode
*
* Generate a short URL for a YouTube video
*
* @since	2.0
*
* @uses		ye_generate_shorturl_code   Generate the code
*
* @param    string		$paras			Shortcode parameters
* @param	string		$content		Shortcode content
* @return   string						YouTube short URL code
*/

function ye_shorturl_sc( $paras = '', $content = '' ) {

	extract( shortcode_atts( array( 'id' => '' ), $paras ) );

	return do_shortcode( ye_generate_shorturl_code( $id ) );

}

add_shortcode( 'youtube_url', 'ye_shorturl_sc' );

/**
* Download shortcode
*
* Generate a short URL for a YouTube video
*
* @since	2.0
*
* @uses		ye_generate_download_code	Generate the download code
*
* @param    string		$paras			Shortcode parameters
* @param	string		$content		Shortcode content
* @return   string						YouTube download link
*/

function ye_video_download( $paras = '', $content = '' ) {

	extract( shortcode_atts( array( 'id' => '' ), $paras ) );

	if ( $id == '' ) { return do_shortcode( ye_error( __ ( 'No YouTube ID was found.', 'youtube-embed' ) ) ); }

	// Extract the ID if a full URL has been specified

	$id = ye_extract_id( $id );

	// Extract the API data

	$data = ye_get_api_data( $id );

	if ( $data[ 'type' ] != 'v' or !$data[ 'valid' ] ) {

		return do_shortcode( ye_error( sprintf( __( 'The YouTube ID of %s is invalid.', 'youtube-embed' ), $id ) ) );

	}

	// Get the download code

	$link = ye_generate_download_code( $id );

	// Now return the HTML

	return do_shortcode( $link );
}

add_shortcode( 'download_video', 'ye_video_download' );

/**
* Convert input to a 1 or 3 equivalent
*
* Function to convert a Yes or No input to an equivalent 1 or 3 output
*
* @since	2.0
*
* @uses		ye_yes_or_no		Convert input to a true or false equivalent
*
* @param	string	$input		Input, usually Yes or No
* @return   string				1, 3 or blank, depending on input
*/

function ye_convert_3( $input ) {

	$input = ye_yes_or_no( $input );
	$output = '';
	if ( $input === true ) { $output = '1'; }
	if ( $input === false ) { $output = '3'; }

	return $output;
}

/**
* Convert input to True or False (1.0)
*
* Return true or false, depending on the input. Possible inputs are Yes, No, 0, 1, True,
* False, On, Off
*
* @since	2.0
*
* @param	string	$input		Value passed for checking
* @return	string				Blank string or boolean true, false
*/

function ye_yes_or_no( $input = '' ) {

	$input = strtolower( $input );
	if ( ( $input === true ) or ( $input == 'true' ) or ( $input == '1' ) or ( $input == 'yes' ) or ( $input == 'on' ) ) { return true; }
	if ( ( $input === false ) or ( $input == 'false' ) or ( $input == '0' ) or ( $input == 'no' ) or ( $input == 'off' ) ) { return false; }

	return;
}

/**
* Convert autohide parameter
*
* Convert autohide text value to a numeric equivalent
*
* @since	2.0
*
* @param	string	$autohide	Autohide parameter value
* @return	string				Autohide numeric equivalent
*/

function ye_set_autohide( $autohide ) {

	$autohide = strtolower( $autohide );
	if ( $autohide == 'no' )	{ $autohide = '0'; }
	if ( $autohide == 'yes' )	{ $autohide = '1'; }
	if ( $autohide == 'fade' )	{ $autohide = '2'; }

	return $autohide;
}
?>