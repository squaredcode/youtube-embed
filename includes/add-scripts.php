<?php
/**
* Add Scripts
*
* Add JS and CSS to the main theme and to admin
*
* @package	youtube-embed
*/

/**
* Plugin initialisation
*
* Perform various actions upon initialisation of the plugin
*
* @since	2.5.5
*/

/**
* Upgrade checks
*
* Check if plugin has upgraded and, if so, perform further actions
*
* @since	5.0.1
*/

$version = get_option( 'youtube_embed_version' );

if ( $version != youtube_embed_version ) {

	// Set up default option values (if not already set)

	$options = ye_set_general_defaults();
	ye_set_shortcode();
	ye_set_list( $options[ 'list_no' ] );
	ye_set_profile( $options[ 'profile_no' ] );

	// Update saved version number

	update_option( 'youtube_embed_version', youtube_embed_version );
}

/**
* Admin initialisation
*
* Switch on shortcodes in widgets, if required
*
* @since	5.0.1
*/

function ye_admin_init() {

	$options = get_option( 'youtube_embed_general' );

	if ( $options[ 'widgets' ] == 1 ) { add_filter( 'widget_text', 'do_shortcode' ); }
}

add_action( 'admin_init', 'ye_admin_init' );

/**
* Add scripts to theme
*
* Add styles and scripts to the main theme
*
* @since		2.4
*/

function ye_main_scripts() {

	wp_register_style( 'ye_dynamic', plugins_url( 'css/main.min.css', dirname(__FILE__) ) );

	wp_enqueue_style( 'ye_dynamic' );

}

add_action( 'wp_enqueue_scripts', 'ye_main_scripts' );

/**
* Add CSS to admin
*
* Add stylesheets to the admin screens
*
* @since	2.0
*/

function ye_admin_css() {

	wp_enqueue_style( 'tinymce_button', plugins_url( 'css/admin.min.css', dirname(__FILE__) ));

}

add_action( 'admin_print_styles', 'ye_admin_css' );

/**
* Add option to Admin Bar
*
* Add link to YouTube Embed profile options to Admin Bar.
* With help from http://technerdia.com/1140_wordpress-admin-bar.html
*
* @uses     ye_set_general_default     Set default options
*
* @since	2.5
*/

function ye_admin_bar_render( $meta = TRUE ) {

	if ( !is_admin() ) {

		$options = ye_get_general_defaults();

		if ( $options[ 'admin_bar' ] != '' ) {

			global $wp_admin_bar;

			if ( !is_user_logged_in() ) { return; }
			if ( !is_admin_bar_showing() ) { return; }
			if ( !current_user_can( $options[ 'menu_access' ] ) ) { return; }

			$wp_admin_bar -> add_menu( array(
				'id' => 'aye-menu',
				'title' => __( 'YouTube Embed', 'youtube-embed' ) ) );

			$wp_admin_bar -> add_menu( array(
				'parent' => 'aye-menu',
				'id' => 'aye-options',
				'title' => __( 'Options', 'youtube-embed' ),
				'href' => admin_url( 'admin.php?page=ye-general-options' ),
				'meta' => array( 'target' => '_blank' ) ) );

			$wp_admin_bar -> add_menu( array(
				'parent' => 'aye-menu',
				'id' => 'aye-profile',
				'title' => __( 'Profiles', 'youtube-embed' ),
				'href' => admin_url( 'admin.php?page=ye-profile-options' ),
				'meta' => array( 'target' => '_blank' ) ) );

			$wp_admin_bar -> add_menu( array(
				'parent' => 'aye-menu',
				'id' => 'aye-lists',
				'title' => __( 'Lists', 'youtube-embed' ),
				'href' => admin_url( 'admin.php?page=ye-list-options' ),
				'meta' => array( 'target' => '_blank' ) ) );
		}
	}
}

add_action( 'admin_bar_menu', 'ye_admin_bar_render', 99 );

/**
* Add to site header
*
* Perform main site head processing
*
* @uses     youtube_embed_head_checks	Perform the actual checks
*
* @since	4.1
*/

function youtube_embed_add_to_head() {

	youtube_embed_shortcode_checks( 'site' );

	$options = ye_get_general_defaults();

	if ( $options[ 'script' ] == 'f' ) {

		$tab = "\t";
		$newline = "\n";

		echo '<script type="text/javascript" src="' . plugins_url( 'js/jquery.fitvids.js', dirname(__FILE__) ) . '"></script>' . $newline;

		echo '<script>' . $newline;
		echo $tab . '$(".youtube-embed").fitVids();' . $newline;
		echo '</script>' . $newline;

	}

	if ( $options[ 'script' ] == 'i' ) {
		wp_enqueue_script( 'youtube-embed-iframe-resizer', plugins_url( 'js/iframeResizer.min.js', dirname(__FILE__) ) );
	}
}

add_action( 'wp_head', 'youtube_embed_add_to_head' );

/**
* Admin Head Checks
*
* Perform admin head processing
*
* @uses     youtube_embed_head_checks	Perform the actual checks
*
* @since	4.1
*/

function youtube_embed_admin_head_checks() {

	youtube_embed_shortcode_checks( 'admin' );

}


add_action( 'admin_head', 'youtube_embed_admin_head_checks' );

/**
* Shortcode Checking
*
* Check if the shortcode is in use by another plugin. If so, note it
* in the options.
*
* @param	string	$source		Where the checks are coming from
*
* @since	4.1
*/

function youtube_embed_shortcode_checks( $source ) {

	global $shortcode_tags;
	if ( isset( $shortcode_tags[ 'youtube' ] ) ) {
		$shortcode_usage = $shortcode_tags[ 'youtube' ];
	} else {
		$shortcode_usage = '';
	}

	$shortcode = 1;

	// All is fine

	if ( substr( $shortcode_usage, 0, 19 ) == 'ye_video_shortcode_' ) {

		$shortcode = 0;

	} else {

		// Jetpack is overriding

		if ( $shortcode_usage == 'youtube_shortcode' ) {

			$shortcode = 2;

		} else {

			// If the shortcode is empty, it's fine in admin but elsewhere
			// is another fail.

			if ( $shortcode_usage == '' ) {

				if ( $source == 'admin' )  {
					$shortcode = 0;
				} else {
					$shortcode = 3;
				}
			}
		}
	}

	update_option( 'youtube_embed_shortcode_' . $source, $shortcode );
}
?>
