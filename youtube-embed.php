<?php
/**
 * YouTube Embed
 *
 * @package           youtube-embed
 * @author            YouTube Embed
 * @license           GPL-2.0-or-later
 *
 * Plugin Name:       YouTube Embed
 * Plugin URI:        https://wordpress.org/plugins/youtube-embed/
 * Description:       🎥 An incredibly fast, simple, yet powerful, method of embedding YouTube videos into your WordPress site.
 * Version:           5.3.1
 * Requires at least: 4.6
 * Requires PHP:      7.4
 * Author:            YouTube Embed
 * Author URI:        https://wordpress.org/support/users/squared/
 * Text Domain:       youtube-embed
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

define( 'YOUTUBE_EMBED_VERSION', '5.3.1' );

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
