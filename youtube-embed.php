<?php
/**
Plugin Name: YouTube Embed
Plugin URI: https://github.com/dartiss/youtube-embed
Description: An incredibly fast, simple, yet powerful, method of embedding YouTube videos into your WordPress site.
Version: 5.2
Author: dartiss
Author URI: https://artiss.blog
Text Domain: youtube-embed

@package youtube-embed
 */

define( 'YOUTUBE_EMBED_VERSION', '5.2' );

$functions_dir = plugin_dir_path( __FILE__ ) . 'includes/';

// Include all the various functions.

require_once $functions_dir . 'shared-functions.php';       // Shared routines.

require_once $functions_dir . 'add-scripts.php';            // Add various scripts.

require_once $functions_dir . 'generate-embed-code.php';    // Generate YouTube embed code.

require_once $functions_dir . 'generate-other-code.php';    // Generate download & short URLs & thumbnails.

require_once $functions_dir . 'generate-widgets.php';       // Generate widgets.

require_once $functions_dir . 'api-access.php';             // Fetch video data from YouTube API.

require_once $functions_dir . 'caching.php';                // Data caching functions.

require_once $functions_dir . 'shortcodes.php';             // Shortcodes.

require_once $functions_dir . 'admin-config.php';           // Administration configuration.
