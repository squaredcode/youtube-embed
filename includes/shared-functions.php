<?php
/**
* Shared Functions
*
* Small utilities shared by a number of other functions
*
* @package	youtube-embed
*/

/**
* Extract a video ID
*
* Function to extract an ID if a full URL has been supplied
*
* @since	2.0
*
* @param	string	$id		Video ID
* @return	string			Extracted ID
*/

function ye_extract_id( $id ) {

	// Convert and trim video ID characters

	$replacement_from = array( '&#8211;', '&#8212;', '&#215;' );
	$replacement_to = array( '--', '---', 'x' );

	$id = trim( strip_tags( str_replace( $replacement_from, $replacement_to, $id ) ) );

	// Check if it's the full URL, as found in address bar

	$video_pos = strpos( $id, 'youtube.com/watch?', 0 );

	if ( $video_pos !== false ) {

		$video_pos = strpos( $id, 'v=', $video_pos + 18 );
		if ( $video_pos === false ) { $video_pos = strpos( $id, 'p=', $video_pos + 18 ); }

		if ( $video_pos !== false ) {

			$video_pos = $video_pos + 2;
			$ampersand_pos = strpos( $id, '&', $video_pos );
			if ( !$ampersand_pos ) {
				$id = substr( $id, $video_pos );
			} else {
				$id = substr( $id, $video_pos, $ampersand_pos - $video_pos );
			}
		}

	} else {

		// Now check to see if it's a full URL, as used in the embed code
		// Need to check both video and playlist formats

		$video_pos = strpos( $id, 'youtube.com/v/' );
		if ($video_pos === false) { $video_pos = strpos( $id, 'youtube.com/p/' ); }

		if ( $video_pos !== false ) {
			$video_pos = $video_pos + 14;
			$qmark_pos = strpos( $id, '?', $video_pos );
			if ( !$qmark_pos ) {
				$id = substr( $id, $video_pos );
			} else {
				$id = substr( $id, $video_pos, $qmark_pos - $video_pos );
			}

		} else {

			// Check if it's a shortened URL

			$video_pos = strpos( $id, 'youtu.be/', 0 );

			if ( $video_pos !== false ) {
				$video_pos = $video_pos + 9;
				$ampersand_pos = strpos( $id, '&', $video_pos );
				if ( !$ampersand_pos ) {
					$id = substr( $id, $video_pos );
				} else {
					$id = substr( $id, $video_pos, $ampersand_pos - $video_pos );
				}
			}
		}
	}

	return $id;
}

/**
* Function to report an error
*
* Display an error message in a standard format
*
* @since	2.0
*
* @param	string	$errorin	Error message
* @return	string				Error output
*/

function ye_error( $errorin ) {

	return '<p style="color: #f00; font-weight: bold;">YouTube Embed: ' . $errorin . "</p>\n";

}

/**
* Convert input to a 1 or 0 equivalent
*
* Function to convert a Yes or No input to an equivalent 1 or 0 output
*
* @since	2.0
*
* @uses		ye_yes_or_no		Convert input to a true or false equivalent
*
* @param	string	$input		Input, usually Yes or No
* @return	string				1, 0 or blank, depending on input
*/

function ye_convert( $input ) {

	$input = ye_yes_or_no( $input );
	$output = '';
	if ( $input === true ) { $output = '1'; }
	if ( $input === false ) { $output = '0'; }

	return $output;
}

/**
* Generate a profile list
*
* Generate FORM options for the current profiles
*
* @since	2.0
*
* @param	string	$current	The current profile number
* @param	string	$total		The total number of profiles
* @param	string	$full_list	Show the full list or just those defined?
*/

function ye_generate_profile_list( $current, $total, $full_list = false ) {

	$loop = 0;
	echo 'Total: ' . $total;
	while ( $loop <= $total ) {

		// Attempt to get profile

		$profiles = ye_get_profile( $loop );

		// If list is undefined, give it a default name

		if ( $profiles[ 'default' ] ) { $list_found = false; } else { $list_found = true; }

		// Output profile information

		if ( ( $list_found ) or ( $full_list ) ) {

			echo '<option value="' . $loop . '"';
			if ( $current == $loop ) { echo " selected='selected'"; }
			echo '>' . __( $profiles[ 'profile_name' ] );
			if ( !$list_found ) { echo ' [undefined]'; }
			echo "</option>\n";

		}

		$loop ++;
	}

	return;
}

/**
* Function to get shortcode options
*
* Return shortcode options. It's in a seperate function in case any further shared
* functionality needs to be added.
*
* @since	4.2
*
* @return   strings		Alternative shortcode name
*/

function ye_get_shortcode() {

	return get_option( 'youtube_embed_shortcode' );

}

/**
* Function to set Shortcode option
*
* Looks up shortcode option - if it's not set, assign a default
*
* @since	2.0
*
* @return   string		Alternative Shortcode
*/

function ye_set_shortcode() {

	$shortcode = get_option( 'youtube_embed_shortcode' );

	// If an array, transform to new format

	if ( is_array( $shortcode ) ) {
		$shortcode = $shortcode[ 1 ];
		update_option( 'youtube_embed_shortcode', $shortcode );
	}

	// If setting doesn't exist, set defaults

	if ( $shortcode == '' ) {
		$shortcode = 'youtube_video';
		update_option( 'youtube_embed_shortcode', $shortcode );
	}

	return $shortcode;
}

/**
* Function to get general YouTube options
*
* Return options. It's in a seperate function in case any further shared
* functionality needs to be added.
*
* @since	4.2
*
* @return   strings		Options array
*/

function ye_get_general_defaults() {

	return get_option( 'youtube_embed_general' );

}

/**
* Function to set general YouTube options
*
* Looks up options. If none exist, or some are missing, set default values
*
* @since	2.0
*
* @return	string		Options array
*/

function ye_set_general_defaults() {

	$options = get_option( 'youtube_embed_general' );

	// If options don't exist, create an empty array

	if ( !is_array( $options ) ) { $options = array(); }

	// Because of upgrading, check each option - if not set, apply default

	$default_array = array(
						   'editor_button' => 1,
						   'admin_bar' => 1,
						   'profile_no' => 5,
						   'list_no' => 5,
						   'alt_profile' => 0,
						   'metadata' => 1,
						   'feed' => 'b',
						   'thumbnail' => 2,
						   'privacy' => 0,
						   'frameborder' => 1,
						   'widgets' => 0,
						   'menu_access' => 'list_users',
						   'language' => '',
						   'debug' => 1,
						   'script' => '',
						   'prompt' => 1,
						   'force_list_type' => 0,
						   'api' => '',
						   'api_cache' => 7,
						   'video_cache' => 24
						   );

	// If a new user switch the list option on. Otherwise, an existing user will default to off

	if ( !is_array( $options )) { $default_array[ 'list' ] = 1; }

	// Merge existing and default options - any missing from existing will take the default settings

	$new_options = array_merge( $default_array, $options );

	// Check if API is invalid (some people are finding a value left over from past install). If so, clear it down

	if ( strlen( $new_options[ 'api'] ) == 1 ) { $new_options[ 'api' ] = ''; }

	// Update the options, if changed, and return the result

	if ( $options != $new_options ) { update_option( 'youtube_embed_general', $new_options ); }

	return $new_options;
}

/**
* Function to get profile options
*
* Return profiles. It's in a seperate function in case any further shared
* functionality needs to be added.
*
* @since	4.2
*
* @param	string		$profile	Profile number
* @return   strings					Profile array
*/

function ye_get_profile( $profile ) {

	$options = get_option( 'youtube_embed_profile' . $profile );

	// If there is no profile set up, set to default values and save

	if ( !is_array( $options ) ) {
		$options = ye_set_profile_defaults( $profile );
		$options[ 'default' ] = true;
	} else {
		$options[ 'default' ] = false;
	}

	// Remove added slashes from template XHTML

	if ( isset( $options[ 'template' ] ) ) { $options[ 'template' ] = stripslashes( $options[ 'template' ] );	}

	return $options;
}

/**
* Function to set YouTube profile options
*
* Looks up profile options, based on passed number of profiles.
* If none exist, or some are missing, set default values
*
* @since	2.0
*
* @param    string	$profiles	Number of profiles
* @return   string				Options array
*/

function ye_set_profile( $profiles ) {

	$profile = 0;
	while ( $profile <= $profiles ) {

		$options = get_option( 'youtube_embed_profile' . $profile );

		// If the profile doesn't exist, don't assign anything to it

		if ( is_array( $options ) ) {

			// Because of upgrading, check each option - if not set, apply default

			$default_array = ye_set_profile_defaults( $profile );

			// Merge the two arrays

			$new_options = array_merge( $default_array, $options );

			// Because I've changed the variable name from name to profile_name, convert any with the old name

			if ( isset( $new_options[ 'name' ] ) ) {
				$new_options[ 'profile_name' ] = $new_options[ 'name' ];
				unset( $new_options[ 'name' ] );
			}

			// Update the options, if changed, and return the result

			if ( $options != $new_options ) { update_option( 'youtube_embed_profile' . $profile, $new_options ); }

			// Remove added slashes from template XHTML

			$options[ 'template' ] = stripslashes( $options[ 'template' ] );

		}

		$profile++;

	}

	return $options;
}

/**
* Function to set initial profile default options
*
* Sets default values for a profile
*
* @since	4.2
*
* @return   array					Profile array
*/

function ye_set_profile_defaults( $profile ) {

	// Set profile name

	if ( $profile == 0 ) {
		$profname = 'Default';
	} else {
		$profname = 'Profile ' . $profile;
	}

	// Work out default dimensions

	$width = 0;
	if ( isset( $content_width ) ) { $width = $content_width; }
	if ( ( $width == 0 ) or ( $width == '' ) ) { $width = 560; }
	$height = 25 + round( ( $width / 16 ) * 9, 0 );

	// Set default array

	$default = array(
					'profile_name' => $profname,
					'width' => $width,
					'height' => $height,
					'fullscreen' => 1,
					'template' => '%video%',
					'autoplay' => '',
					'start' => 0,
					'loop' => '',
					'cc' => '',
					'annotation' => 1,
					'related' => 1,
					'info' => 1,
					'stop' => 0,
					'disablekb' => '',
					'autohide' => 2,
					'controls' => 1,
					'wmode' => 'window',
					'style' => '',
					'color' => 'red',
					'theme' => 'dark',
					'modest' => '',
					'dynamic' => '',
					'fixed' => '',
					'download' => '',
					'download_style' => '',
					'download_text' => 'Click here to download the video',
					'playsinline' => '',
					'html5' => ''
					);

	return $default;

}

/**
* Function to get list options
*
* Return lists. It's in a seperate function in case any further shared
* functionality needs to be added.
*
* @since	4.2
*
* @param	string		$list		List number
* @return   strings					Options array
*/

function ye_get_list( $list ) {

	$options = get_option( 'youtube_embed_list' . $list );

	// If there is no list set up, set to default values and save

	if ( !is_array( $options ) ) {
		$options = ye_set_list_defaults( $list );
		$options[ 'default' ] = true;
	} else {
		$options[ 'default' ] = false;
	}

	return $options;
}

/**
* Function to set default list options
*
* Looks up list options, based on passed number of lists.
* If any options are missing, set default values
*
* @since	2.0
*
* @param    string	$list		Number of lists
*/

function ye_set_list( $lists ) {

	$list = 1;
	while ( $list <= $lists ) {

		$options = get_option( 'youtube_embed_list' . $list );
		
		// Only process if array exists

		if ( is_array( $options ) ) {

			// Because of upgrading, check each option - if not set, apply default

			$default_array = ye_set_list_defaults( $list );

			// Merge the two arrays

			$new_options = array_merge( $default_array, $options );

			// Because I've changed the variable name from name to list_name, convert any with the old name

			if ( isset( $new_options[ 'name' ] ) ) {
				$new_options[ 'list_name' ] = $new_options[ 'name' ];
				unset( $new_options[ 'name' ] );
			}

			// Update the options, if changed, and return the result

			if ( $options != $new_options ) { update_option( 'youtube_embed_list' . $list, $new_options ); }

		}

		$list++;
	}

	return;
}


/**
* Function to set initial list default options
*
* Sets default values for a list
*
* @since	4.2
*
* @param	string	$list			List number
* @return   array					List array
*/

function ye_set_list_defaults( $list ) {

	$default = array(
				   'list_name' => 'List ' . $list,
				   'list' => ''
					);

	return $default;
}

/**
* Output timing
*
* Used by the author for testing purposes
*
* @since	4.1
*
* @param    string	$checkpoint		The last time
* @param	string	$name			The name of the checkpoint
* @return   string					New checkpoint
*/

function ye_timer( $checkpoint, $name ) {

	$timing = ( microtime( true ) - $checkpoint );

	echo '<p>' . $name . ': ' . ( $timing * 1000000 ) . ' microseconds.</p>';

	return microtime( true );

}
?>
