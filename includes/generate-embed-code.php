<?php
/**
 * Generate embed code
 *
 * Functions calls to generate the required YouTube code
 *
 * @package youtube-embed
 */

/**
 * Generate embed code
 *
 * Generate XHTML compatible YouTube embed code
 *
 * @uses   ye_add_links             Add links under video.
 * @uses   ye_error                 Display an error.
 * @uses   ye_extract_id            Get the video ID.
 * @uses   ye_validate_list         Get the requested lists.
 * @uses   ye_get_api_data          Get the API data.
 * @uses   ye_validate_profile      Get the requested profile.
 * @uses   ye_get_general_defaults  Get general options.
 * @uses   ye_get_profile           Set default profile options.
 *
 * @param  string $array            Array of parameters.
 * @return string                   Code output.
 */
function ye_generate_youtube_code( $array ) {

	// Set defaults then merge with passed array. Finally, split array into individual variables.

	$default = array(
		'id'          => '',
		'width'       => '',
		'height'      => '',
		'fullscreen'  => '',
		'related'     => '',
		'autoplay'    => '',
		'loop'        => '',
		'start'       => '',
		'annotation'  => '',
		'cc'          => '',
		'style'       => '',
		'stop'        => '',
		'disablekb'   => '',
		'ratio'       => '',
		'controls'    => '',
		'profile'     => '',
		'list_style'  => '',
		'template'    => '',
		'color'       => '',
		'responsive'  => '',
		'search'      => '',
		'user'        => '',
		'modest'      => '',
		'playsinline' => '',
		'cc_lang'     => '',
		'language'    => '',
		'lazyload'    => '',
	);

	$array = array_merge( $default, $array );

	extract( $array );

	// Initialisation.

	$start_time     = microtime( true );
	$newline        = "\n";
	$tab            = "\t";
	$cache_suppress = false;

	// Ensure an ID is passed.

	if ( '' == $id ) {
		return ye_error( __( 'No video/playlist ID has been supplied', 'youtube-embed' ) );
	}

	// Get general options.

	$general = ye_get_general_defaults();

	// Find the profile, if one is specified. Otherwise use default.

	if ( '' != $profile ) {
		$profile = ye_validate_profile( $profile, $general['profile_no'] );
	} else {
		$profile = 0;
	}
	$options = ye_get_profile( $profile );

	// If a user look-up or search has been requested, miss out looking up list details and
	// simple assign it as an IFRAME video.

	$playlist_ids = '';
	$embed_type   = '';

	if ( ( 0 == $user ) && ( 0 == $search ) ) {

		// Check if it's a list.

		$list_found = false;
		if ( ( 1 == $general['force_list_type'] && '' != $list_style ) || ( 1 != $general['force_list_type'] ) ) {
			$list = ye_validate_list( $id, $general['list_no'] );
			if ( is_array( $list ) ) {
				$list_found = true;
			}
		}

		// If this isn't a list, extract the ID and work out the type.

		if ( ! $list_found ) {

			// Check if certain parameters are included in the URL.

			$width  = ye_get_url_para( $id, 'w', $width );
			$height = ye_get_url_para( $id, 'h', $height );

			// Extract the ID if a full URL has been specified.

			$id = ye_extract_id( $id );

			// Fetch in video data.

			$api_data   = ye_get_api_data( $id );
			$embed_type = $api_data['type'];

			// If the video is invalid, output an error.

			if ( ! $api_data['valid'] ) {
				$result = $newline . '<!-- YouTube Embed v' . YOUTUBE_EMBED_VERSION . ' -->' . $newline;
				/* translators: %s is replaced with the ID of the YouTube video */
				$result .= sprintf( __( 'The YouTube ID of %s is invalid.', 'youtube-embed' ), $id ) . $newline . '<!-- ' . __( 'End of YouTube Embed code' ) . ' -->' . $newline;
				return $result;
			}
		}

		// This is a list, so build the list appropriately.

		if ( $list_found ) {

			$embed_type = 'v';
			$list_name  = $id;

			// Randomize the video.

			if ( 'random' == $list_style ) {

				shuffle( $list );

				$cache_suppress = true;

			}

			// Extract one video randomly.

			if ( 'single' == $list_style ) {

				$id = $list[ array_rand( $list, 1 ) ];

				$cache_suppress = true;

			} else {

				$id = $list [0];

				// Build the playlist.

				if ( count( $list ) > 1 ) {
					$loop           = 1;
					$playlist_count = count( $list );
					while ( $loop < $playlist_count ) {
						if ( '' != $playlist_ids ) {
							$playlist_ids .= ',';
						}
						$list_id       = ye_extract_id( $list[ $loop ] );
						$playlist_ids .= $list_id;
						$loop ++;
					}
				}
			}

			// Fetch in video data.

			$api_data = ye_get_api_data( $id, '', '', '', $list_name );
		}
	}

	// Correct the ID if a playlist.

	if ( 'pl' == strtolower( substr( $id, 0, 2 ) ) && 34 == strlen( $id ) ) {
		$id = substr( $id, 2 );
	}

	// Get from cache, if required.

	if ( 0 != $general['video_cache'] && get_the_date() !== false && ! $cache_suppress ) {

		// Generate the cache key - it's a combination of ALL the passed parameters, some of the general options, all of the relevant profile options and the playlist, if specified.

		$general_extract = array(
			'metadata'        => $general['metadata'],
			'feed'            => $general['feed'],
			'thumbnail'       => $general['thumbnail'],
			'privacy'         => $general['privacy'],
			'frameborder'     => $general['frameborder'],
			'debug'           => $general['debug'],
			'script'          => $general['script'],
			'force_list_type' => $general['force_list_type'],
		);

		$key = wp_json_encode( $options ) . wp_json_encode( $array ) . wp_json_encode( $general_extract );

		if ( $list_found ) {
			$key .= wp_json_encode( $list );
		}

		// Now fetch the cache.

		$cache = ye_get_transient( $key, true );
		if ( false !== $cache ) {
			return $cache;
		}
	}

	// If this is a feed then display a thumbnail and/or text link to the original video.

	if ( is_feed() ) {
		$result = '';
		if ( ( '' != $playlist_ids ) || ( 0 != $user ) || ( 0 != $search ) ) {
			$result .= '<p>' . __( 'A video list cannot be viewed within this feed - please view the original content', 'youtube-embed' ) . '.</p>' . $newline;
		} else {
			$youtube_url = 'https://www.youtube.com/watch?' . $embed_type . '=' . $id;

			if ( ( 'v' == $embed_type ) && ( 't' != $general['feed'] ) ) {
				$result .= '<p><a href="' . $youtube_url . '"><img src="https://img.youtube.com/vi/' . $id . '/' . $general['thumbnail'] . '.jpg" alt="' . $api_data['title'] . '"></a></p>' . $newline;
			}
			if ( ( 'v' != $general['feed'] ) || ( 'v' != $embed_type ) ) {
				$result .= '<p><a href="' . $youtube_url . '">' . __( 'Click here to view the video on YouTube', 'youtube-embed' ) . '</a>.</p>' . $newline;
			}
		}
		return $result;
	}

	// If responsive output has been requested, check whether the width should be fixed.

	$fixed = '';
	if ( '' == $responsive ) {
		$responsive = $options['dynamic'];
		$fixed      = $options['fixed'];
	} else {
		if ( '' != $width ) {
			$fixed = 1;
		}
	}

	// Only set width and height from defaults if both are missing.

	if ( ( '' == $width ) && ( '' == $height ) ) {
		$width  = $options['width'];
		$height = $options['height'];
	}

	// If height or width is missing, calculate missing parameter using ratio.

	if ( ( ( '' == $width ) || ( '' == $height ) ) && ( ( '' != $width ) || ( '' != $height ) ) ) {
		$new_sizes = ye_calculate_video_size( $width, $height, $options['width'], $options['height'], $ratio );
		$width     = $new_sizes['width'];
		$height    = $new_sizes['height'];
	}

	// If values have not been passed, use the default values.

	if ( '' == $fullscreen ) {
		$fullscreen = $options['fullscreen'];
	}
	if ( '' == $related ) {
		$related = $options['related'];
	}
	if ( '' == $autoplay ) {
		$autoplay = $options['autoplay'];
	}
	if ( '' == $loop ) {
		$loop = $options['loop'];
	}
	if ( '' == $annotation ) {
		$annotation = $options['annotation'];
	}
	if ( '' == $cc ) {
		$cc = $options['cc'];
	}
	if ( '' == $cc_lang ) {
		$cc_lang = $options['cc_lang'];
	}
	if ( '' == $language ) {
		$language = $options['language'];
	}
	if ( '' == $disablekb ) {
		$disablekb = $options['disablekb'];
	}
	if ( '' == $controls ) {
		$controls = $options['controls'];
	}
	if ( '' == $style ) {
		$style = $options['style'];
	}
	if ( '' == $color ) {
		$color = $options['color'];
	}
	if ( '' == $modest ) {
		$modest = $options['modest'];
	}
	if ( '' == $playsinline ) {
		$playsinline = $options['playsinline'];
	}
	if ( '' == $lazyload ) {
		$lazyload = $general['lazyload'];
	}

	// And for those not passed, simply assign the defaults to variables.

	$metadata = $general['metadata'];
	$debug    = $general['debug'];
	$privacy  = $general['privacy'];

	if ( '' == $start ) {
		$start = '0';
	}
	if ( '' == $stop ) {
		$stop = '0';
	}

	$class = 'youtube-player';

	// Build the required template.
	// If no video tag is found, set to the default.

	if ( '' == $template ) {
		$template = $options['template'];
	} else {
		$template = htmlspecialchars_decode( $template, ENT_QUOTES );
	}
	if ( false === strpos( $template, '%video%' ) ) {
		$template = '%video%';
	}

	// Set frameborder options.

	if ( isset( $general['frameborder'] ) && 1 != $general['frameborder'] ) {
		$frameborder = 'frameborder="0" ';
	} else {
		$frameborder = '';
	}

	// Generate parameters to add to URL but only if they differ from the default.

	$paras = '';

	if ( 1 == $modest ) {
		$paras .= '&modestbranding=1';
	}
	if ( 1 != $fullscreen ) {
		$paras .= '&fs=0';
	}
	if ( 1 != $related ) {
		$paras .= '&rel=0';
	}
	if ( 1 == $autoplay ) {
		$paras .= '&autoplay=1';
	}
	if ( 1 == $loop ) {
		$paras .= '&loop=1';
	}
	if ( 1 != $annotation ) {
		$paras .= '&iv_load_policy=3';
	}
	if ( '' != $cc ) {
		$paras .= '&cc_load_policy=' . $cc;
	}
	if ( '' != $cc_lang ) {
		$paras .= '&cc_lang_pref=' . $cc_lang;
	}
	if ( 1 == $disablekb ) {
		$paras .= '&disablekb=1';
	}
	if ( 1 != $controls ) {
		$paras .= '&controls=' . $controls;
	}
	if ( 'red' != strtolower( $color ) ) {
		$paras .= '&color=' . strtolower( $color );
	}
	if ( 1 == $playsinline ) {
		$paras .= '&playsinline=1';
	}
	if ( '' != $language ) {
		$paras .= '&hl=' . $language;
	}
	if ( 0 != $start ) {
		$paras .= '&start=' . $start;
	}
	if ( 0 != $stop ) {
		$paras .= '&end=' . $stop;
	}

	// If the loop parameter is being used, make this a single video playlist.

	if ( 1 == $loop && '' == $playlist_ids ) {
		$playlist_ids = $id;
	}

	// If not a playlist, add the playlist parameter.

	if ( '' != $playlist_ids ) {
		$paras .= '&playlist=' . $playlist_ids;
	}

	// Generate DIVs to wrap around video.

	$ttab   = $tab;
	$result = '<div class="youtube-embed';

	if ( 1 == $responsive ) {
		$result .= ' ye-container';
	}
	$result .= '"';
	if ( 0 != $metadata ) {
		$result .= ' itemprop="video" itemscope itemtype="https://schema.org/VideoObject"';
	}
	$result .= '>' . $newline;
	if ( ( 1 == $responsive ) && ( 1 == $fixed ) ) {
		$result = '<div style="width: ' . $width . 'px; max-width: 100%">' . $newline . $tab . $result;
		$ttab  .= $tab;
	}

	// Add Metadata.

	if ( 0 != $metadata ) {

		if ( true === $api_data['restricted'] ) {
			$friendly = 'false';
		} else {
			if ( false === $api_data['restricted'] ) {
				$friendly = 'true';
			} else {
				$friendly = '';
			}
		}

		$result .= $ttab . '<meta itemprop="url" content="https://www.youtube.com/' . $embed_type . '/' . $id . '" />' . $newline;

		if ( '' != $api_data['title'] ) {
			$result .= $ttab . '<meta itemprop="name" content="' . $api_data['title'] . '" />' . $newline;
		}

		if ( '' != $api_data['description'] ) {
			$result .= $ttab . '<meta itemprop="description" content="' . $api_data['description'] . '" />' . $newline;
		}
		if ( '' != $api_data['published'] ) {
			$result .= $ttab . '<meta itemprop="uploadDate" content="' . $api_data['published'] . '" />' . $newline;
		}
		if ( '' != $api_data['thumb_default'] ) {
			$result .= $ttab . '<meta itemprop="thumbnailUrl" content="' . $api_data['thumb_default'] . '" />' . $newline;
		}

		$result .= $ttab . '<meta itemprop="embedUrl" content="https://www.youtube.com/embed/' . $id . '" />' . $newline;
		$result .= $ttab . '<meta itemprop="height" content="' . $height . '" />' . $newline;
		$result .= $ttab . '<meta itemprop="width" content="' . $width . '" />' . $newline;

		if ( '' != $friendly ) {
			$result .= $ttab . '<meta itemprop="isFamilyFriendly" content="' . $friendly . '" />' . $newline;
		}
	}

	// Work out, depending on privacy settings, the main address to use.

	if ( 2 == $privacy ) {
		$do_not_track = ye_do_not_track();
		if ( $do_not_track ) {
			$privacy = 1;
		} else {
			$privacy = 0;
		}
	}

	if ( 1 == $privacy ) {
		$url_privacy = 'youtube-nocookie.com';
	} else {
		$url_privacy = 'youtube.com';
	}

	// Generate the first part of the embed URL along with the ID section.

	$embed_url = 'https://www.' . $url_privacy . '/embed';
	$id_paras  = '/' . $id;

	// If a playlist, user or download build the ID appropriately.

	if ( ( 'p' == $embed_type ) || ( 0 != $user ) || ( 0 != $search ) ) {

		$list_type = '';
		if ( 'p' == $embed_type ) {
			$list_type = 'playlist';
		}
		if ( 0 != $user ) {
			$list_type = 'user_uploads';
		}
		if ( 0 != $search ) {
			$list_type = 'search';
			$id        = urlencode( $id );
		}

		$id_paras = '?listType=' . $list_type . '&list=';
		if ( ( 'p' == $embed_type ) && ( 'pl' != strtolower( substr( $id, 0, 2 ) ) ) ) {
			$id_paras .= 'PL';
		}
		$id_paras .= $id;
	}

	// Combine URL parts together.

	$embed_url .= $id_paras;
	if ( ( ! strpos( $embed_url, '?' ) ) && ( '' != $paras ) ) {
		$paras = '?' . substr( $paras, 1 );
	}
	$embed_url .= $paras;

	// Check length of URL to ensure it doesn't exceed 2000 characters.

	if ( strlen( $embed_url ) > 2000 ) {
		return ye_error( __( 'The maximum URL length has been exceeded. Please reduce your parameter and/or playlist.', 'youtube-embed' ) );
	}

	// Add IFRAME embed code.

	if ( 'p' == $embed_type ) {
		$playlist_para = 'p/';
	} else {
		$playlist_para = '';
	}
	$result .= $ttab . '<iframe ' . $frameborder . 'style="border: 0;' . $style . '" class="' . $class . '" width="' . $width . '" height="' . $height . '" src="' . $embed_url . '"';
	if ( 1 == $lazyload ) {
		$result .= ' loading="lazy"';
	}
	if ( 1 == $fullscreen ) {
		$result .= ' allowfullscreen';
	}
	$result .= '></iframe>' . $newline;

	// Now apply the template to the result.

	$end_tag = '';
	if ( ( 1 == $responsive ) && ( 1 == $fixed ) ) {
		$end_tag .= $tab . '</div>' . $newline . '</div>' . $newline;
	} else {
		$end_tag .= '</div>' . $newline;
	}
	$result = str_replace( '%video%', $result . $end_tag, $template );

	// Add the download link, if required.

	if ( ( 1 == $options['download'] ) && ( 'v' == $embed_type ) ) {
		$result .= '<div style="' . $options['download_style'] . '" class="aye_download">' . $newline . $tab . '<a href="' . ye_generate_download_code( $id ) . '">' . $options['download_text'] . '</a>' . $newline . '</div>' . $newline;
	}

	// Now add a commented header and trailer.

	if ( 1 == $debug ) {
		$result  = '<!-- YouTube Embed v' . YOUTUBE_EMBED_VERSION . ' -->' . $newline . $result;
		$runtime = round( microtime( true ) - $start_time, 5 );
		$result .= '<!-- End of YouTube Embed code. Generated in ' . $runtime . ' seconds -->' . $newline;
	}

	$result = $newline . $result;

	// Save the cache.

	if ( 0 != $general['video_cache'] && false !== get_the_date() && ! $cache_suppress ) {

		$cache = $general['video_cache'] * HOUR_IN_SECONDS;

		ye_set_transient( $key, $result, $cache, true );

	}

	return $result;
}

/**
 * Validate a supplied profile name
 *
 * Returns a profile number for a supplied name
 *
 * @param  string $name    The name of the profile to find.
 * @param  string $number  The number of profiles available.
 * @return string          The profile number (defaults to 0).
 */
function ye_validate_profile( $name, $number ) {

	$profile = 0;
	$name    = strtolower( $name );

	if ( ( '' != $name ) && ( 'default' != $name ) ) {

		// Loop around, fetching in profile names.

		$loop = 1;
		while ( ( $loop <= $number ) && ( 0 == $profile ) ) {
			if ( ( $name == $loop ) || ( 'Profile ' . $loop == $name ) ) {
				$profile = $loop;
			} else {
				$profiles = ye_get_profile( $loop );
				$profname = strtolower( $profiles['profile_name'] );
				if ( $profname == $name ) {
					$profile = $loop;
				}
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
 * @param  string $name    The name of the list to find.
 * @param  string $number  The number of lists available.
 * @return string          The list (defaults to blank).
 */
function ye_validate_list( $name, $number ) {

	$list = '';

	// If the parameter contains commas, assume to be a comma seperated list and move into an array.

	if ( strpos( $name, ',' ) !== false ) {
		$list = explode( ',', $name );
	} else {

		// No comma, so check if this is a named list.

		$name = strtolower( $name );

		if ( '' != $name ) {

			// Loop around, fetching in profile names.

			$loop = 1;
			while ( ( $loop <= $number ) && ( '' == $list ) ) {
				$listfiles = ye_get_list( $loop );
				if ( is_array( $listfiles ) ) {
					if ( ( strval( $loop ) == $name ) || ( 'List ' . $loop == $name ) ) {
						$list = $listfiles['list'];
					} else {
						$listname = strtolower( $listfiles['list_name'] );
						if ( $listname == $name ) {
							$list = $listfiles['list'];
						}
					}
				}
				$loop ++;
			}
		}
		if ( '' != $list ) {
			$list = explode( "\n", $list );
		}
	}
	return $list;
}

/**
 * Get URL parameters
 *
 * Extract a requested parameter from a URL
 *
 * @param  string $id       The ID of the video.
 * @param  string $para     The parameter to extract.
 * @param  string $current  The current parameter value.
 * @return string           The parameter value.
 */
function ye_get_url_para( $id, $para, $current ) {

	// Look for an ampersand.

	$start_pos = false;
	if ( false !== strpos( $id, '&' . $para . '=' ) ) {
		$start_pos = strpos( $id, '&' . $para . '=' ) + 6 + strlen( $para );
	}

	// If a parameter was found, look for the end of it.

	if ( false !== $start_pos ) {
		$end_pos = strpos( $id, '&', $start_pos + 1 );
		if ( ! $end_pos ) {
			$end_pos = strlen( $id );
		}

		// Extract the parameter and return it.

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
 * @return string  True or false.
 */
function ye_do_not_track() {

	if ( isset( $_SERVER['HTTP_DNT'] ) ) {
		if ( 1 == $_SERVER['HTTP_DNT'] ) {
			return true;
		}
	} else {
		if ( function_exists( 'getallheaders' ) ) {
			foreach ( getallheaders() as $key => $value ) {
				if ( ( 'dnt' == strtolower( $key ) ) && ( 1 == $value ) ) {
					return true;
				}
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
 * @param  string $width           Current width.
 * @param  string $height          Current height.
 * @param  string $default_width   The default width.
 * @param  string $default_height  The default height.
 * @param  string $ratio           User supplied ratio.
 * @return array                   Array of new width and height.
 */
function ye_calculate_video_size( $width, $height, $default_width, $default_height, $ratio ) {

	$ratio_to_use = '';
	$new_sizes    = array();

	// If a ratio has been specified by the user, extract it.

	if ( '' != $ratio ) {
		$pos = strpos( $ratio, ':', 0 );
		if ( false !== $pos ) {
			$ratio_l = substr( $ratio, 0, $pos );
			$ratio_r = substr( $ratio, $pos + 1 );
			if ( ( is_numeric( $ratio_l ) ) && ( is_numeric( $ratio_r ) ) ) {
				$ratio_to_use = $ratio_l / $ratio_r;
			}
		}
	}

	// If no, or invalid, ratio supplied, calculate from the default video dimensions.

	if ( '' == $ratio_to_use ) {
		$ratio_to_use = $default_width / $default_height;
	}

	// Complete the missing width or height using the ratio.

	if ( '' == $width ) {
		$width = round( $height * $ratio_to_use, 0 );
	}
	if ( '' == $height ) {
		$height = round( $width / $ratio_to_use, 0 );
	}

	$new_sizes['width']  = $width;
	$new_sizes['height'] = $height;

	return $new_sizes;
}
