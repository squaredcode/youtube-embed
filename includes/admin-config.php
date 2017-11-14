<?php
/**
* Admin Config Functions
*
* Various functions relating to the various administration screens
*
* @package	youtube-embed
*/

/**
* Add Settings link to plugin list
*
* Add a Settings link to the options listed against this plugin
*
* @since	2.0
*
* @param	string  $links	Current links
* @param	string  $file	File in use
* @return   string			Links, now with settings added
*/

function ye_add_settings_link( $links, $file ) {

	static $this_plugin;

	if ( !$this_plugin ) { $this_plugin = plugin_basename( __FILE__ ); }

	if ( strpos( $file, 'youtube-embed.php' ) !== false ) {
		$settings_link = '<a href="admin.php?page=ye-general-options">' . __( 'Settings', 'youtube-embed' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}

add_filter( 'plugin_action_links', 'ye_add_settings_link', 10, 2 );

/**
* Add meta to plugin details
*
* Add options to plugin meta line
*
* @since	2.0
*
* @param	string  $links	Current links
* @param	string  $file	File in use
* @return   string			Links, now with settings added
*/

function ye_set_plugin_meta( $links, $file ) {

	if ( strpos( $file, 'youtube-embed.php' ) !== false ) { $links = array_merge( $links, array( '<a href="https://wordpress.org/support/plugin/youtube-embed">' . __( 'Support', 'youtube-embed' ) . '</a>' ) ); }

	return $links;
}

add_filter( 'plugin_row_meta', 'ye_set_plugin_meta', 10, 2 );

/**
* Admin Screen Initialisation
*
* Set up admin menu and submenu options
*
* @since	2.0
*
* @uses     ye_contextual_help_type    Work out help type
*/

function ye_menu_initialise() {

	// Get level access for menus

	$options = ye_get_general_defaults();

	$menu_access = $options[ 'menu_access' ];

	// Add main admin option

	$menu_icon = 'dashicons-video-alt3';

	add_menu_page( __( 'About YouTube Embed', 'youtube-embed' ), __( 'YouTube Embed', 'youtube-embed' ), $menu_access, 'ye-profile-options', 'ye_profile_options', $menu_icon, 12 );

	// Add profiles sub-menu

	global $ye_profiles_hook;

	$ye_profiles_hook = add_submenu_page( 'ye-profile-options', __( 'YouTube Embed Profiles', 'youtube-embed' ), __( 'Profiles', 'youtube-embed' ), $menu_access, 'ye-profile-options', 'ye_profile_options' );

	add_action( 'load-' . $ye_profiles_hook, 'ye_add_profiles_help' );

	// Add lists sub-menu

	global $ye_lists_hook;

	$ye_lists_hook = add_submenu_page( 'ye-profile-options', __( 'YouTube Embed Lists', 'youtube-embed' ), __( 'Lists', 'youtube-embed' ), $menu_access, 'ye-list-options', 'ye_list_options' );

	add_action( 'load-' . $ye_lists_hook, 'ye_add_lists_help' );

	// Add third party plugins sub-menu

	global $ye_plugins_hook;

	$ye_plugins_hook = add_submenu_page( 'ye-profile-options', __( '3rd Party Plugins', 'youtube-embed' ),  __( '3rd Party Plugins', 'youtube-embed' ), $menu_access, 'ye-plugins-options', 'ye_plugins_options' );

	add_action( 'load-' . $ye_plugins_hook, 'ye_add_plugins_help' );

	// If installed, add link to Video Overlay Ads plugin

	if ( function_exists( 'video_overlay_create_menu' ) ) {

		add_submenu_page( 'ye-profile-options', __( 'Video Overlay Ads', 'youtube-embed' ), __( 'Video Overlay Ads', 'youtube-embed' ), 'administrator', 'ye-video-overlay', 'video_overlay_settings_page' );
	}

	// If installed, add link to Video SEO

	if ( class_exists( 'wpseo_Video_Sitemap' ) ) {

		add_submenu_page( 'ye-profile-options', __( 'Video SEO', 'youtube-embed' ), __( 'Video SEO', 'youtube-embed' ), 'manage_options', 'ye-wpseo-video', 'wpseo_Video_Sitemap::admin_panel' );

	}

	// Add settings sub-menu

	global $ye_options_hook;

	$ye_options_hook = add_submenu_page( 'ye-profile-options', __( 'General Settings', 'youtube-embed' ),  __( 'General Settings', 'youtube-embed' ), $menu_access, 'ye-general-options', 'ye_general_options' );

	add_action( 'load-' . $ye_options_hook, 'ye_add_options_help' );
}

add_action( 'admin_menu', 'ye_menu_initialise' );

/**
* Include general options screen
*
* XHTML options screen to prompt and update some general plugin options
*
* @since	2.0
*/

function ye_general_options() {

	include_once( plugin_dir_path( __FILE__ ) . 'options-general.php' );

}

/**
* Include profile options screen
*
* XHTML options screen to prompt and update profile options
*
* @since	2.0
*/

function ye_profile_options() {

	include_once( plugin_dir_path( __FILE__ ) . 'options-profiles.php' );

}

/**
* Include list options screen
*
* XHTML options screen to prompt and update list options
*
* @since	2.0
*/

function ye_list_options() {

	include_once( plugin_dir_path( __FILE__ ) . 'options-lists.php' );

}

/**
* Include plugins options screen
*
* XHTML options screen to view and install third party plugins
*
* @since	4.3
*/

function ye_plugins_options() {

	include_once( plugin_dir_path( __FILE__ ) . 'options-plugins.php' );

}

/**
* Add Options Help
*
* Add help tab to options screen
*
* @since	2.5
*
* @uses     ye_options_help    Return help text
*/

function ye_add_options_help() {

	global $ye_options_hook;
	$screen = get_current_screen();

	if ( $screen->id != $ye_options_hook ) { return; }

	$screen -> add_help_tab( array( 'id' => 'options-help-tab', 'title'	=> __( 'Help', 'youtube-embed' ), 'content' => youtube_embed_help( 'options' ) ) );

	$screen -> add_help_tab( array( 'id' => 'options-links-tab', 'title'	=> __( 'Links', 'youtube-embed' ), 'content' => youtube_embed_help( 'options', 'links' ) ) );
}

/**
* Add Profiles Help
*
* Add help tab to profiles screen
*
* @since	2.5
*
* @uses     ye_profiles_help    Return help text
*/

function ye_add_profiles_help() {

	global $ye_profiles_hook;
	$screen = get_current_screen();

	if ( $screen->id != $ye_profiles_hook ) { return; }

	$screen -> add_help_tab( array( 'id' => 'profiles-help-tab', 'title'	=> __( 'Help', 'youtube-embed' ), 'content' => youtube_embed_help( 'profiles' ) ) );

	$screen -> add_help_tab( array( 'id' => 'profiles-links-tab', 'title'	=> __( 'Links', 'youtube-embed' ), 'content' => youtube_embed_help( 'profiles', 'links' ) ) );
}

/**
* Add Lists Help
*
* Add help tab to lists screen
*
* @since	2.5
*
* @uses     ye_lists_help    Return help text
*/

function ye_add_lists_help() {

	global $ye_lists_hook;
	$screen = get_current_screen();

	if ( $screen->id != $ye_lists_hook ) { return; }

	$screen -> add_help_tab( array( 'id' => 'lists-help-tab', 'title'	=> __( 'Help', 'youtube-embed' ), 'content' => youtube_embed_help( 'lists' ) ) );

	$screen -> add_help_tab( array( 'id' => 'lists-links-tab', 'title'	=> __( 'Links', 'youtube-embed' ), 'content' => youtube_embed_help( 'lists', 'links' ) ) );
}

/**
* Add Plugins Help
*
* Add help tab to third party plugins screen
*
* @since	4.3
*
* @uses     ye_lists_help    Return help text
*/

function ye_add_plugins_help() {

	global $ye_plugins_hook;
	$screen = get_current_screen();

	if ( $screen->id != $ye_plugins_hook ) { return; }

	$screen -> add_help_tab( array( 'id' => 'lists-help-tab', 'title'	=> __( 'Help', 'youtube-embed' ), 'content' => youtube_embed_help( 'plugins' ) ) );

}

/**
* Help Screens
*
* Generate help screen text
*
* @since	4.1
*
* @param	string	$screen		Which help screen to return text for
* @param	string	$tab		Which tab of the help this is for
* @return	string				Help Text
*/

function youtube_embed_help( $screen, $tab = 'help' ) {

	$text = '';

	if ( $screen == 'options' && $tab == 'help' ) {

		$text .= '<p>' . __( 'This screen allows you to select non-specific options for the YouTube Embed plugin. For the default embedding settings, please select the <a href="admin.php?page=ye-profile-options">Profiles</a> administration option.', 'youtube-embed' ) . '</p>';
	}

	if ( $screen == 'profiles' && $tab == 'help' ) {

		$text .= '<p>' . __( 'This screen allows you to set the options for the default and additional profiles. If you don\'t specify a specific parameter when displaying your YouTube video then the default profile option will be used instead. Additional profiles, which you may name, can be used as well and used as required.', 'youtube-embed' ) . '</p>';
		$text .= '<p>' . __( 'All settings will work whether the Flash or HTML5 player is used, unless one of the following icons is shown, indicating which format the option works with...', 'youtube-embed' ) . '</p>';
		$text .= "<p><img src='" . plugins_url( 'images/flash.png', dirname(__FILE__) ) . "' alt='" . __( 'Flash player', 'youtube-embed' ) . "' width='10px'/> - " . __( 'Flash player', 'youtube-embed' ) . '</br>';
		$text .= "<img src='" . plugins_url( 'images/html5.png', dirname(__FILE__) ) . "' alt='" . __( 'HTML5 player', 'youtube-embed' ) . "' width='10px'/> - " . __( 'HTML5 player', 'youtube-embed' ) . '</br>';
	}

	if ( $screen == 'lists' && $tab == 'help' ) {

		$text .= '<p>' . __( 'This screen allows you to create lists of YouTube videos, which may be named. These lists can then be used in preference to a single video ID.', 'youtube-embed' ) . '</p>';
	}

	if ( $screen == 'plugins' && $tab == 'help' ) {

		$text .= '<p>' . __( 'This screen lists plugins which have all been tested for compatibility with YouTube Embed (and in some cases, specific changes have been made by their authors to ensure compatibility. From here you can view their details and even install the plugins (where hosted at WordPress.org).', 'youtube-embed' ) . '</p>';
		$text .= '<p><strong>' . __( 'NB. Inclusion here does not provide any guarantee for third party software, which includes any support.', 'youtube-embed' ) . '</strong></p>';
		$text .= '<p>' . __( 'Some of these plugins will also appear under the YouTube Admin administration menu, once activated.', 'youtube-embed' ) . '</p>';
	}

	if ( $tab == 'help' && $screen != 'plugins' ) {
		$text .= '<p>' . __( 'Remember to click the Save Changes button at the bottom of the screen for any changes to take effect.', 'youtube-embed' ) . '</p>';
	}

	if ( $tab == 'links' ) {

		$text .= '<p><strong>' . __( 'For more information:', 'youtube-embed' ) . '</strong></p>';
		$text .= '<p><a href="https://wordpress.org/plugins/youtube-embed/">' . __( 'YouTube Embed Plugin Documentation', 'youtube-embed' ) . '</a></p>';

		if ( $screen != 'lists' ) {
			$text .= '<p><a href="https://code.google.com/apis/youtube/player_parameters.html">' . __( 'YouTube Player Documentation', 'youtube-embed' ) . '</a></p>';
		}

		if ( $screen == 'options' ) {

			$text .= '<p><a href="https://github.com/davatron5000/FitVids.js">FitVids.js</a></p>';
			$text .= '<p><a href="https://github.com/davidjbradshaw/iframe-resizer">iFrame Resizer</a></p>';
		}
	}

	return $text;
}

/**
* Set up TinyMCE button
*
* Add filters (assuming user is editing) for TinyMCE
*
* @uses     ye_get_general_defaults    Set default options
*
* @since 	2.0
*/

function youtube_embed_button() {

	// Ensure user is in rich editor and button option is switched on

	if ( get_user_option( 'rich_editing' ) == 'true' ) {

		$options = ye_get_general_defaults();
		if ( $options[ 'editor_button' ] != '' ) {

			// Add filters

			add_filter( 'mce_external_plugins', 'add_youtube_embed_mce_plugin' );
			add_filter( 'mce_buttons', 'register_youtube_embed_button' );
		}
	}
}

add_action( 'admin_init', 'youtube_embed_button' );

/**
* Register new TinyMCE button
*
* Register details of new TinyMCE button
*
* @since	2.0
*
* @param	string	$buttons	TinyMCE button data
* @return	string				TinyMCE button data, but with new YouTube button added
*/

function register_youtube_embed_button( $buttons ) {

	array_push( $buttons, 'mce4_youtube_button' );

	return $buttons;
}

/**
* Register TinyMCE Script
*
* Register JavaScript that will be actioned when the new TinyMCE button is used
*
* @since	2.0
*
* @param	string	$plugin_array	Array of MCE plugin data
* @return	string					Array of MCE plugin data, now with URL of MCE script
*/

function add_youtube_embed_mce_plugin( $plugin_array ) {

	$plugin_array[ 'mce4_youtube_button' ] = plugins_url( 'js/mce4-button.min.js', dirname(__FILE__) );

	return $plugin_array;
}

/**
* Show Admin Messages
*
* Display messages on the administration screen
*
* @since	4.1
*
*/

function youtube_embed_admin_messages() {

	$shortcode_site = get_option( 'youtube_embed_shortcode_site' );

	$shortcode_admin = get_option( 'youtube_embed_shortcode_admin' );

	if ( ( $shortcode_admin != 0 ) or ( $shortcode_site != 0 ) ) {

		$options = ye_get_general_defaults();

		if ( $options[ 'prompt' ] == 1 ) {

			if ( $shortcode_site == 3 ) {
				$message = __( 'For some reason the shortcode <strong>[youtube]</strong> is not working on the main site' );
			}

			$alternative = __( 'An alternative plugin is using the <strong>[youtube]</strong> shortcode' );

			if  ( ( $shortcode_admin == 1 ) or ( $shortcode_site == 1 ) ) {
				$message = $alternative;
			}

			if  ( ( $shortcode_admin == 2 ) or ( $shortcode_site == 2 ) ) {
				$message = __( $alternative . ', possibly the <a href="admin.php?page=jetpack_modules&activated=true">Shortcode Embeds module</a> in Jetpack' );
			}

			echo '<div class="error notice"><p>YouTube Embed: ' . $message . '.</p></div>';
		}
	}

}

add_action( 'admin_notices', 'youtube_embed_admin_messages' );
?>