<?php
/**
* Third Party Plugins Page
*
* Screen for allowing users to view and install third party plugins
*
* @package	youtube-embed
* @since	4.3
*/
?>
<?php add_thickbox(); ?>
<div class="wrap">
<h1><?php _e( 'YouTube Embed 3rd Party Plugins', 'youtube-embed' ); ?></h1>

<form method="post">

<table class="form-table">

<?php

// Turn Off The Lights

?><tr>
<th scope="row"><label for="ye_plugins_turnoff"><?php _e( 'Turn Off The Lights', 'youtube-embed' ); ?></th>
<td><a href="https://www.stefanvd.net/project/turnoffthelights.htm"><?php _e( 'Install Turn Out The Lights on your browser', 'youtube-embed' ); ?></a></label>
<p class="description"><?php _e( 'A browser extension that, with a single click on the lamp button, will fade the page dark, with the exception of the embedded video. By clicking on it again, the page will return to normal.', 'youtube-embed' ); ?></p>
</td></tr><?php

// a3 lazy load

ye_plugin_status( 'a3-lazy-load', __( 'a3 Lazy Load', 'youtube-embed' ), __( 'Speed up your site and enhance frontend user\'s visual experience. Fully updated to support YouTube Embed.', 'youtube-embed' ), 'a3_lazy_load' );

// Video Overlay Ads

ye_plugin_status( 'wpseo-video', __( 'Video SEO', 'youtube-embed' ), __( 'Premium plugin from Yoast to add your video to Google and other search engines.', 'youtube-embed' ), 'video_seo', 'video-seo.php', 'https://yoast.com/wordpress/plugins/video-seo/' );

// Video Overlay Ads

ye_plugin_status( 'video-overlay-ads', __( 'WordPress Video Overlay Ads', 'youtube-embed' ), __( 'This plugin creates an lightbox area over Youtube video embeds. You can insert all kinds of html content including banner ads, texts, polls, and add any kind of html and javascript code you like.', 'youtube-embed' ), 'video_overlay_ads', 'main.php' );

// YouTube Channel Gallery

ye_plugin_status( 'youtube-channel-gallery', __( 'YouTube Channel Gallery', 'youtube-embed' ), __( 'Adds a widget to show a YouTube video and a gallery of thumbnails for a YouTube channel.', 'youtube-embed' ), 'channel-gallery' );

// YouTube subscribe Button

ye_plugin_status( 'youtube-subscribe-button', __( 'YouTube Subscribe Button', 'youtube-embed' ), __( 'Adds a YouTube subscribe button to your blog so people can subscribe to your YouTube channel without leaving your site.', 'youtube-embed' ), 'subscribe-button' );
?>

</table></form>

</div>

<?php
/**
* Output plugin status
*
* Show details of current plugin installation
*
* @since	4.3
*
* @param	$plugin_name	string		The name of the plugin, as in the WordPress directory
* @param	$title			string		The title of the plugin
* @param	$description	string		The plugin description
* @param	$id				string		A unique ID for the form field
* @param	$code_name		string		The filename of the main plugin code (optional)
* @param	$link_url		string		URL to plugin, if not hosted on WordPress (optional)
*/

function ye_plugin_status( $plugin_name, $title, $description, $id, $code_name = '', $url = '' ) {

	$status = ye_check_plugin( $plugin_name, $code_name );

	echo '<tr><th scope="row"><label for="ye_plugins_' . $id . '">' . $title . '</th>';
	echo '<td><input disabled="disabled" type="checkbox" name="ye_plugins_' . $id . '" value="1"';
	if ( $status == 2) { echo ' checked="checked"'; }
	echo '/>';

	if ( $status == 0 ) {
		$text = __( 'Install the plugin', 'youtube-embed' );
	} else {
		if ( $status == 1 ) {
			$text = __( 'Plugin installed but not active', 'youtube-embed' );
		} else {
			$text = __( 'Plugin installed and active', 'youtube-embed' );
		}
	}

	echo '<a href="';
	if ( $url == '' ) {
		echo admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . $plugin_name . '&TB_iframe=true&width=772&height=565' );
	} else {
		echo $url . '?TB_iframe=true&width=772&height=565';
	}
	echo '" class="thickbox">' . $text . '</a>';

	echo '</label><p class="description">' . $description . '</p></td></tr>';
}

/**
* Check if plugin exists
*
* Check status of plugin - if it installed and, if so, is it active?
*
* @since	4.3
*
* @param	$plugin_dir		string		The directory of the plugin
* @param	$plugin_name	string		The name of the plugin (optional)
* @return	$status			string		The status of the plugin (0=not installed / 1=installed, not active / 2 = installed, active
*/

function ye_check_plugin( $plugin_dir, $plugin_name = '' ) {

	if ( $plugin_name == '' ) { $plugin_name = $plugin_dir . '.php'; }

	$plugins = get_plugins( '/' . $plugin_dir );
	if ( $plugins ) {
		if ( is_plugin_active( $plugin_dir . '/' . $plugin_name ) ) {
			$status = 2;
		} else {
			$status = 1;
		}
	} else {
		$status = 0;
	}
	return $status;
}
?>