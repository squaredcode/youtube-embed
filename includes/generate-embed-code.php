<?php
/**
* Generate embed code
*
* Functions calls to generate the required YouTube code
*
* @package	youtube-embed
*/

/**
* Generate embed code
*
* Generate XHTML compatible YouTube embed code
*
* @since	2.0
*
* @uses		ye_add_links				Add links under video
* @uses		ye_error				    Display an error
* @uses		ye_extract_id			    Get the video ID
* @uses		ye_validate_list		    Get the requested lists
* @uses		ye_get_api_data			    Get the API data
* @uses		ye_validate_profile		Get the requested profile
* @uses		ye_get_general_defaults	Get general options
* @uses		ye_get_profile				Set default profile options
*
* @param	string		$array			Array of parameters
* @return	string						Code output
*/

function ye_generate_youtube_code( $array )  {

	// Set defaults then merge with passed array. Finally, split array into individual variables

	$default = array( 'id' => '', 'width' => '', 'height' => '', 'fullscreen' => '', 'related' => '', 'autoplay' => '', 'loop' => '', 'start' => '', 'info' => '', 'annotation' => '', 'cc' => '', 'style' => '', 'stop' => '', 'disablekb' => '', 'ratio' => '', 'autohide' => '', 'controls' => '', 'profile' => '', 'list_style' => '', 'template' => '', 'color' => '', 'theme' => '', 'responsive' => '', 'search' => '', 'user' => '', 'modest' => '', 'playsinline' => '', 'html5' => '' );

	$array = array_merge( $default, $array );

	extract( $array );

	// Initialisation

	$start_time = microtime( true );
    $newline = "\n";
    $tab = "\t";
	$cache_suppress = false;

	// Ensure an ID is passed

	if ( $id == '' ) { return ye_error( __( 'No video/playlist ID has been supplied', 'youtube-embed' ) ); }

	// Get general options

	$general = ye_get_general_defaults();

	// Find the profile, if one is specified. Otherwise use default

	if ( $profile != '' ) {
		$profile = ye_validate_profile( $profile, $general[ 'profile_no' ] );
	} else {
		$profile = 0;
	}
	$options = ye_get_profile( $profile );

	// If a user look-up or search has been requested, miss out looking up list details and
	// simple assign it as an IFRAME video

	$playlist_ids = '';
	$embed_type = '';

	if ( ( $user == 0 ) && ( $search == 0 ) ) {

		// Check if it's a list

		$list_found = false;
		if ( ( $general[ 'force_list_type' ] == 1 &&  $list_style != '' ) or ( $general[ 'force_list_type' ] != 1 ) ) {
			$list = ye_validate_list( $id, $general[ 'list_no' ] );
			if ( is_array( $list ) ) { $list_found = true; }
		}

		// If this isn't a list, extract the ID and work out the type

		if ( !$list_found ) {

			// Check if certain parameters are included in the URL

			$width = ye_get_url_para( $id, 'w', $width );
			$height = ye_get_url_para( $id, 'h', $height );

			// Extract the ID if a full URL has been specified

			$id = ye_extract_id( $id );

			// Fetch in video data

			$api_data = ye_get_api_data( $id );
			$embed_type = $api_data[ 'type' ];

			// If the video is invalid, output an error

			if ( !$api_data[ 'valid' ] ) {
				$result = $newline . '<!-- YouTube Embed v' . youtube_embed_version . ' -->' . $newline;
				$result .= sprintf( __( 'The YouTube ID of %s is invalid.', 'youtube-embed' ), $id ) . $newline . '<!-- ' . __( 'End of YouTube Embed code' ) . ' -->' . $newline;
				return $result;
			}

		}

		// This is a list, so build the list appropriately

		if ( $list_found ) {

			$embed_type = 'v';
			$list_name = $id;

			// Randomize the video

			if ( $list_style == 'random' ) {

				shuffle( $list );

				$cache_suppress = true;

			}

			// Extract one video randomly

			if ( $list_style == 'single' ) {

				$id = $list[ array_rand( $list, 1 ) ];

				$cache_suppress = true;

			} else {

				$id = $list [ 0 ];

				// Build the playlist

				if ( count( $list ) > 1 ) {
					$loop = 1;
					while ( $loop < count( $list ) ) {
						if ( $playlist_ids != '' ) { $playlist_ids .= ','; }
						$list_id = ye_extract_id( $list[ $loop ] );
						$playlist_ids .= $list_id;
						$loop ++;
					}
				}
			}

			// Fetch in video data

			$api_data = ye_get_api_data( $id, '', '', '', $list_name );
		}
	}

    // Correct the ID if a playlist

    if ( strtolower( substr( $id, 0, 2 ) ) == 'pl' && strlen( $id ) == 34 ) { $id = substr( $id, 2 ); }

	// Get from cache, if required

	if ( $general[ 'video_cache' ] != 0 && get_the_date() !== false && !$cache_suppress ) {

		// Generate the cache key - it's a combination of ALL the passed parameters, some of the general options, all of the relevant profile options and the playlist, if specified

		$general_extract = array( 'metadata' => $general[ 'metadata' ], 'feed' => $general[ 'feed' ], 'thumbnail' => $general[ 'thumbnail' ], 'privacy' => $general[ 'privacy' ], 'frameborder' => $general[ 'frameborder' ], 'language' => $general[ 'language' ], 'debug' => $general[ 'debug' ], 'script' => $general[ 'script' ], 'force_list_type' => $general[ 'force_list_type' ] );

		$key = serialize( $options ) . serialize( $array ) . serialize ( $general_extract );

		if ( $list_found ) { $key .= serialize( $list ); }

		// Now fetch the cache

		$cache = ye_get_transient( $key, true );
		if ( $cache !== false ) { return $cache; }
	}

	// If this is a feed then display a thumbnail and/or text link to the original video

	if ( is_feed () ) {
		$result = '';
		if ( ( $playlist_ids != '' ) or ( $user != 0 ) or ( $search != 0 ) ) {
			$result .= '<p>'.__( 'A video list cannot be viewed within this feed - please view the original content', 'youtube-embed' ) . '.</p>' . $newline;
		} else {
			$youtube_url = 'https://www.youtube.com/watch?' . $embed_type . '=' . $id;
			if ( ( $embed_type == 'v' ) && ( $general[ 'feed' ] != 't' ) ) { $result .= '<p><a href="' . $youtube_url . '"><img src="https://img.youtube.com/vi/' . $id . '/' . $general[ 'thumbnail' ] . '.jpg" alt="' . $api_data[ 'title' ] . '"></a></p>' . $newline; }
			if ( ( $general ['feed'] != 'v' ) or ( $embed_type != 'v' ) ) { $result .= '<p><a href="' . $youtube_url . '">' . __( 'Click here to view the video on YouTube', 'youtube-embed' ) . '</a>.</p>' . $newline; }
		}
		return $result;
	}

	// If responsive output has been requested, check whether the width should be fixed

	$fixed = '';
	if ( $responsive == '' ) {
		$responsive = $options[ 'dynamic' ];
		$fixed = $options[ 'fixed' ];
	} else {
		if ( $width != '' ) { $fixed = 1; }
	}

	// Only set width and height from defaults if both are missing

	if ( ( $width == '' ) && ( $height == '' ) ) {
		$width = $options[ 'width' ];
		$height = $options[ 'height' ];
	}

	// If height or width is missing, calculate missing parameter using ratio

	if ( ( ( $width == '' ) or ( $height == '' ) ) && ( ( $width != '' ) or ( $height != '' ) ) ) {
		$new_sizes = ye_calculate_video_size( $width, $height, $options[ 'width' ], $options[ 'height' ], $ratio);
		$width = $new_sizes[ 'width' ];
		$height = $new_sizes[ 'height' ];
	}

	// If values have not been pressed, use the default values

	if ( $fullscreen == '' ) { $fullscreen = $options[ 'fullscreen' ]; }
	if ( $related == '' ) { $related = $options[ 'related' ]; }
	if ( $autoplay == '' ) { $autoplay = $options[ 'autoplay' ]; }
	if ( $loop == '' ) { $loop = $options[ 'loop' ]; }
	if ( $info == '' ) { $info = $options[ 'info' ]; }
	if ( $annotation == '' ) { $annotation = $options[ 'annotation' ]; }
	if ( $cc == '' ) { $cc = $options[ 'cc' ]; }
	if ( $disablekb == '' ) { $disablekb = $options[ 'disablekb' ]; }
	if ( $autohide == '' ) { $autohide = $options[ 'autohide' ]; }
	if ( $controls == '' ) { $controls = $options[ 'controls' ]; }
	if ( $style == '' ) { $style = $options[ 'style' ]; }
	if ( $color == '' ) { $color = $options[ 'color' ]; }
	if ( $theme == '' ) { $theme = $options[ 'theme' ]; }
    if ( $modest == '' ) { $modest = $options[ 'modest' ]; }
	if ( $playsinline == '' ) { $playsinline = $options[ 'playsinline' ]; }
	if ( $html5 == '' ) { $html5 = $options[ 'html5' ]; }
	if ( $theme == '' ) { $theme = $options[ 'theme' ]; }

	// And for those not passed, simply assign the defaults to variables

	$language = $general[ 'language'];
	$metadata = $general[ 'metadata' ];
	$debug = $general[ 'debug' ];
	$wmode = $options[ 'wmode' ];
	$privacy = $general[ 'privacy' ];

	if ( $start == '' ) { $start = '0'; }
	if ( $stop == '' ) { $stop = '0'; }

	$class = 'youtube-player';

	// Build the required template
	// If no video tag is found, set to the default

	if ( $template == '' ) {
		$template = $options[ 'template' ];
	} else {
		$template = htmlspecialchars_decode( $template, ENT_QUOTES );
	}
	if ( strpos( $template, '%video%' ) === false ) { $template = '%video%'; }

	// Set validation options

	if ( isset( $general[ 'frameborder' ] ) && $general[ 'frameborder' ] != 1 ) {
		$frameborder = 'frameborder="0" ';
		$amp = '&';
	} else {
		$frameborder = '';
		$amp = '&amp;';
	}

	// Generate parameters to add to URL but only if they differ from the default

	$paras = '';

	if ( $modest == 1 ) { $paras .= $amp . 'modestbranding=1'; }
	if ( $fullscreen != 1 ) { $paras .= $amp . 'fs=0'; }
	if ( $related != 1 ) { $paras .= $amp . 'rel=0'; }
	if ( $autoplay == 1 ) { $paras .= $amp . 'autoplay=1'; }
	if ( $loop == 1 ) { $paras .= $amp . 'loop=1'; }
	if ( $info != 1 ) { $paras .= $amp . 'showinfo=0'; }
	if ( $annotation != 1 ) { $paras .= $amp . 'iv_load_policy=3'; }
	if ( $cc != '' ) { $paras .= $amp . 'cc_load_policy=' . $cc; }
	if ( $disablekb == 1 ) { $paras .= $amp . 'disablekb=1'; }
	if ( $autohide != 2 ) { $paras .= $amp . 'autohide=' . $autohide; }
	if ( $controls != 1 ) { $paras .= $amp . 'controls=' . $controls; }
	if ( strtolower( $color ) != 'red' ) { $paras .= $amp . 'color=' . strtolower( $color ); }
	if ( strtolower( $theme ) != 'dark' ) { $paras .= $amp . 'theme=' . strtolower( $theme ); }
	if ( $wmode != 'window' ) { $paras .= $amp . 'wmode=' . $wmode; }
	if ( $playsinline == 1 ) { $paras .= $amp . 'playsinline=1'; }
	if ( $html5 == 1 ) { $paras .= $amp . 'html5=1'; }
	if ( $language != '' ) { $paras .= $amp . 'hl=' . $language; }
	if ( $start != 0 ) { $paras .= $amp . 'start=' . $start; }
	if ( $stop != 0 ) { $paras .= $amp . 'end=' . $stop; }

	// If not a playlist, add the playlist parameter

	if ( ( $playlist_ids != '' ) && ( $playlist_ids != $id ) ) { $paras .= $amp . 'playlist=' . $playlist_ids; }

	// Generate DIVs to wrap around video

	$ttab = $tab;
	$result = '<div class="youtube-embed';
	if ( $responsive == 1 ) { $result .= ' ye-container'; }
	$result .= '"';
	if ( $metadata != 0 ) { $result .= ' itemprop="video" itemscope itemtype="https://schema.org/VideoObject"'; }
	$result .= '>' . $newline;
	if ( ( $responsive == 1 ) && ( $fixed == 1) ) {
		$result = '<div style="width: ' . $width . 'px; max-width: 100%">' . $newline . $tab . $result;
		$ttab .= $tab;
	}

    // Add Metadata

    if ( $metadata != 0 ) {

		if ( $api_data[ 'restricted' ] === true ) {
			$friendly = 'false';
		} else {
			if ( $api_data[ 'restricted' ] === false ) {
				$friendly = 'true';
			} else {
				$friendly = '';
			}
		}

        $result .= $ttab . '<meta itemprop="url" content="https://www.youtube.com/' . $embed_type . '/' . $id . '" />' . $newline;
        if ( $api_data[ 'title' ] != '' ) { $result .= $ttab . '<meta itemprop="name" content="' . $api_data[ 'title' ] . '" />' . $newline; }
        if ( $api_data[ 'description' ] != '' ) { $result .= $ttab . '<meta itemprop="description" content="' . $api_data[ 'description' ] . '" />' . $newline; }
        if ( $api_data[ 'published' ] != '' ) { $result .= $ttab . '<meta itemprop="uploadDate" content="' . $api_data[ 'published' ] . '" />' . $newline; }
        if ( $api_data[ 'thumb_default' ] != '' ) { $result .= $ttab . '<meta itemprop="thumbnailUrl" content="' . $api_data[  'thumb_default' ] . '" />' . $newline; }
        $result .= $ttab . '<meta itemprop="embedUrl" content="https://www.youtube.com/embed/' . $id . '" />' . $newline;
        $result .= $ttab . '<meta itemprop="height" content="' . $height . '" />' . $newline;
        $result .= $ttab . '<meta itemprop="width" content="' . $width . '" />' . $newline;
		if ( $friendly != '' ) { $result .= $ttab . '<meta itemprop="isFamilyFriendly" content="' . $friendly . '" />' . $newline; }
    }

	// Work out, depending on privacy settings, the main address to use

	if ( $privacy  == 2 ) {
		$do_not_track = ye_do_not_track();
		if ( $do_not_track ) { $privacy = 1; } else { $privacy = 0; }
	}

	if ( $privacy == 1 )  { $url_privacy = 'youtube-nocookie.com'; } else { $url_privacy = 'youtube.com'; }

	// Generate the first part of the embed URL along with the ID section

	$embed_url = 'https://www.' . $url_privacy . '/embed';
    $id_paras = '/' . $id;

	// If a playlist, user or download build the ID appropriately

	if ( ( $embed_type == 'p' ) or ( $user != 0 ) or ( $search != 0 ) ) {

		$list_type = '';
		if ( $embed_type == 'p' ) { $list_type = 'playlist'; }
		if ( $user != 0 ) { $list_type = 'user_uploads'; }
		if ( $search != 0 ) { $list_type = 'search'; $id = urlencode( $id ); }

		$id_paras = '?listType=' . $list_type . '&list=';
		if ( ( $embed_type == 'p' ) && ( strtolower( substr ( $id, 0, 2 ) ) != 'pl' ) ) { $id_paras .= 'PL'; }
		$id_paras .= $id;
	}

	// Combine URL parts together

	$embed_url .= $id_paras;
	if ( ( !strpos( $embed_url, '?' ) ) && ( $paras != '' ) ) { $paras = '?' . substr( $paras, 1 ); }
	$embed_url .= $paras;

	// Check length of URL to ensure it doesn't exceed 2000 characters

	if ( strlen( $embed_url ) > 2000 ) { return ye_error( __( 'The maximum URL length has been exceeded. Please reduce your parameter and/or playlist.', 'youtube-embed' ) ); }

	// Add IFRAME embed code

	if ( $embed_type == "p" ) { $playlist_para = "p/"; } else { $playlist_para = ''; }
	$result .= $ttab . '<iframe ' . $frameborder . 'style="border: 0;' . $style . '" class="' . $class . '" width="' . $width . '" height="' . $height . '" src="' . $embed_url . '"';
	if ( $fullscreen == 1 ) { $result .= ' allowfullscreen'; }
	$result .= ' ></iframe>' . $newline;

	// Now apply the template to the result

	$end_tag = '';
	if ( ( $responsive == 1 ) && ( $fixed == 1 ) ) {
		$end_tag .= $tab . '</div>' . $newline . '</div>' . $newline;
	} else {
		$end_tag .= '</div>' . $newline;
	}
	$result = str_replace( '%video%', $result . $end_tag, $template );

	// Add the download link, if required

	if ( ( $options[ 'download' ] == 1 ) && ( $embed_type == 'v' ) ) {
		$result .= '<div style="' . $options[ 'download_style' ] . '" class="aye_download">' . $newline . $tab . '<a href="' . ye_generate_download_code( $id ) . "\">" . $options[ 'download_text' ] . '</a>' . $newline . '</div>' . $newline;
	}

	// Now add a commented header and trailer

	if ( $debug == 1 ) {
		$result = '<!-- YouTube Embed v' . youtube_embed_version . ' -->' . $newline . $result;
		$runtime = round( microtime( true ) - $start_time, 5 );
		$result .= '<!-- End of YouTube Embed code. Generated in ' . $runtime . ' seconds -->' . $newline;
	}

	$result = $newline . $result;

	// Save the cache

	if ( $general[ 'video_cache' ] != 0 && get_the_date() !== false && !$cache_suppress ) {

		$cache = $general[ 'video_cache' ] * 60 * 60;

		ye_set_transient( $key, $result, $cache, true );

	}

	return $result;
}

/**
* Validate a supplied profile name
*
* Returns a profile number for a supplied name
*
* @since	2.0
*
* @param	string		$name		The name of the profile to find
* @param	string		$number		The number of profiles available
* @return	string					The profile number (defaults to 0)
*/

function ye_validate_profile( $name, $number ) {

	$profile = 0;
	$name = strtolower( $name );

	if ( ( $name != '' ) && ( $name != 'default' ) ) {

		// Loop around, fetching in profile names

		$loop = 1;
		while ( ( $loop <= $number ) && ( $profile == 0 ) ) {
			if ( ( $name == $loop ) or ( $name == 'Profile ' . $loop ) ) {
				$profile = $loop;
			} else {
				$profiles = ye_get_profile( $loop );
				$profname = strtolower( $profiles[ 'profile_name' ] );
				if ( $profname == $name ) { $profile = $loop; }
			}
			$loop ++;
		}
	}
	return $profile;
}

/**
* Validate a supplied list name
*
* Returns a list for a supplied list number or name name - blank if not a valid list
*
* @since	2.0
*
* @param	string		$name		The name of the list to find
* @param	string		$number		The number of lists available
* @return	string					The list (defaults to blank)
*/

function ye_validate_list( $name, $number ) {

	$list = '';

	// If the parameter contains commas, assume to be a comma seperated list and move into an array

	if ( strpos( $name, ',' ) !== false ) {
		$list = explode( ',', $name );
	} else {

		// No comma, so check if this is a named list

		$name = strtolower( $name );

		if ( $name != '' ) {

			// Loop around, fetching in profile names

			$loop = 1;
			while ( ( $loop <= $number ) && ( $list == '' ) ) {
				$listfiles = ye_get_list( $loop );
				if ( is_array( $listfiles) ) {
					if ( ( $name == strval( $loop ) ) or ( $name == 'List ' . $loop ) ) {
						$list = $listfiles[ 'list' ];
					} else {
						$listname = strtolower( $listfiles[ 'list_name' ] );
						if ( $listname == $name ) { $list = $listfiles[ 'list' ]; }
					}
				}
				$loop ++;
			}
		}
		if ( $list != '' ) { $list = explode( "\n", $list ); }
	}
	return $list;
}

/**
* Get URL parameters
*
* Extract a requested parameter from a URL
*
* @since	2.0
*
* @param	string		$id			The ID of the video
* @param	string		$para		The parameter to extract
* @param	string		$current	The current parameter value
* @return	string					The parameter value
*/

function ye_get_url_para( $id, $para, $current ) {

	// Look for an ampersand

	$start_pos = false;
	if ( strpos( $id, '&' . $para . '=' ) !== false ) {	$start_pos = strpos( $id, '&' . $para . '=' ) + 6 + strlen( $para ); }

	// If a parameter was found, look for the end of it

	if ( $start_pos !== false ) {
		$end_pos = strpos( $id, '&', $start_pos + 1 );
		if ( !$end_pos ) { $end_pos = strlen( $id ); }

		// Extract the parameter and return it

		$current = substr( $id, $start_pos, $end_pos - $start_pos );
	}

	return $current;
}

/**
* Is Do Not Track active?
*
* Function to return whether Do Not Track is active in the current
* browser
*
* @since	2.6
*
* @return			    string	True or false
*/

function ye_do_not_track() {

	if ( isset( $_SERVER[ 'HTTP_DNT' ] ) ) {
		if ( $_SERVER[ 'HTTP_DNT' ] == 1 ) { return true; }
	} else {
		if ( function_exists( 'getallheaders' ) ) {
			foreach ( getallheaders() as $key => $value ) {
				if ( ( strtolower( $key ) === 'dnt' ) && ( $value == 1 ) ) { return true; }
			}
		}
	}
	return false;
}

/**
* Calculate video size
*
* Calculate the video size using a ratio
*
* @since	4.2
*
* @param	string	$width			Current width
* @param	string	$height			Current height
* @param	string	$default_width	The default width
* @param	string	$default_height	The default height
* @param	string	$ratio			User supplied ratio
* @return	array					Array of new width and height
*/

function ye_calculate_video_size( $width, $height, $default_width, $default_height, $ratio ) {

	$ratio_to_use = '';

	// If a ratio has been specified by the user, extract it

	if ( $ratio != '' ) {
		$pos = strpos( $ratio, ':', 0 );
		if ( $pos !== false ) {
			$ratio_l = substr( $ratio, 0, $pos );
			$ratio_r = substr( $ratio, $pos + 1 );
			if ( ( is_numeric( $ratio_l ) ) && ( is_numeric( $ratio_r ) ) ) { $ratio_to_use = $ratio_l / $ratio_r; }
		}
	}

	// If no, or invalid, ratio supplied, calculate from the default video dimensions

	if ( $ratio_to_use == '' ) { $ratio_to_use = $default_width / $default_height; }

	// Complete the missing width or height using the ratio

	if ( $width == '' ) { $width = round( $height * $ratio_to_use, 0); }
	if ( $height == '' ) { $height = round( $width / $ratio_to_use, 0); }

	$new_sizes[ 'width' ] = $width;
	$new_sizes[ 'height' ] = $height;

	return $new_sizes;
}
?>
