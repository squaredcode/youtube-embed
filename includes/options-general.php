<?php
/**
 * General options page
 *
 * Screen for generic options
 *
 * @package youtube-embed
 */

?>
<div class="wrap">
<h1><?php _e( 'YouTube Embed Settings', 'youtube-embed' ); ?></h1>

<?php

// If options have been updated on screen, update the database.

if ( ( ! empty( $_POST ) ) && ( check_admin_referer( 'youtube-embed-general', 'youtube_embed_general_nonce' ) ) ) {

	// If the number of profiles or lists have changed check that they all have
	// correct values assigned (if at all).

	$options = ye_get_general_defaults();
	if ( isset( $options['list_no'] ) && isset( $_POST['youtube_embed_list_no'] ) && $options['list_no'] != $_POST['youtube_embed_list_no'] ) {
		ye_set_list( sanitize_text_field( $_POST['youtube_embed_list_no'] ) );
	}
	if ( isset( $options['profile_no'] ) && isset( $_POST['youtube_embed_list_no'] ) && $options['profile_no'] != $_POST['youtube_embed_list_no'] ) {
		ye_set_profile( sanitize_text_field( $_POST['youtube_embed_profile_no'] ) );
	}

	// Update options.

	if ( isset( $_POST['youtube_embed_admin_bar'] ) ) {
		$options['admin_bar'] = sanitize_text_field( $_POST['youtube_embed_admin_bar'] );
	} else {
		$options['admin_bar'] = '';
	}

	$options['profile_no']     = sanitize_text_field( $_POST['youtube_embed_profile_no'] );
	$options['list_no']        = sanitize_text_field( $_POST['youtube_embed_list_no'] );
	$options['alt_profile']    = sanitize_text_field( $_POST['youtube_embed_alt_profile'] );
	$options['feed']           = sanitize_text_field( $_POST['youtube_embed_feed'] );
	$options['thumbnail']      = sanitize_text_field( $_POST['youtube_embed_thumbnail'] );
	$options['privacy']        = sanitize_text_field( $_POST['youtube_embed_privacy'] );
	$options['menu_access']    = sanitize_text_field( $_POST['youtube_embed_menu_access'] );
	$options['script']         = sanitize_text_field( $_POST['youtube_embed_script'] );
	$options['standard_video'] = sanitize_text_field( $_POST['youtube_embed_video'] );
	$options['playlist_video'] = sanitize_text_field( $_POST['youtube_embed_playlist'] );
	$options['lazyload']       = sanitize_text_field( $_POST['youtube_embed_lazyload'] );	

	$options['api_cache'] = sanitize_text_field( $_POST['youtube_embed_api_cache'] );
	if ( ! is_numeric( $options['api_cache'] ) ) {
		$options['api_cache'] = 0;
	}
	$options['video_cache'] = sanitize_text_field( $_POST['youtube_embed_video_cache'] );
	if ( ! is_numeric( $options['video_cache'] ) ) {
		$options['video_cache'] = 0;
	}

	if ( isset( $_POST['youtube_embed_metadata'] ) ) {
		$options['metadata'] = sanitize_text_field( $_POST['youtube_embed_metadata'] );
	} else {
		$options['metadata'] = '';
	}
	if ( isset( $_POST['youtube_embed_frameborder'] ) ) {
		$options['frameborder'] = sanitize_text_field( $_POST['youtube_embed_frameborder'] );
	} else {
		$options['frameborder'] = '';
	}
	if ( isset( $_POST['youtube_embed_widgets'] ) ) {
		$options['widgets'] = sanitize_text_field( $_POST['youtube_embed_widgets'] );
	} else {
		$options['widgets'] = '';
	}
	if ( isset( $_POST['youtube_embed_debug'] ) ) {
		$options['debug'] = sanitize_text_field( $_POST['youtube_embed_debug'] );
	} else {
		$options['debug'] = '';
	}
	if ( isset( $_POST['youtube_embed_prompt'] ) ) {
		$options['prompt'] = sanitize_text_field( $_POST['youtube_embed_prompt'] );
	} else {
		$options['prompt'] = '';
	}
	if ( isset( $_POST['youtube_embed_list'] ) ) {
		$options['force_list_type'] = sanitize_text_field( $_POST['youtube_embed_list'] );
	} else {
		$options['force_list_type'] = '';
	}
	if ( isset( $_POST['youtube_embed_lazyload'] ) ) {
		$options['lazyload'] = sanitize_text_field( $_POST['youtube_embed_lazyload'] );
	} else {
		$options['lazyload'] = '';
	}

	// If the number of profiles or lists is less than zero, put it to 0.

	if ( $options['profile_no'] < 0 ) {
		$options['profile_no'] = 0;
	}
	if ( $options['list_no'] < 0 ) {
		$options['list_no'] = 0;
	}

	// Test the API key.

	$api_key   = sanitize_text_field( $_POST['youtube_embed_api'] );
	$api_valid = true;
	if ( '' != $api_key ) {
		$api_test = ye_get_api_data( 'jNQXAC9IVRw', $api_key, true );
		if ( ! $api_test['api'] ) {
			$api_valid = false;
		}
	}

	if ( ! $api_valid ) {
		$update_message = __( 'API key is invalid or API is unavailable.', 'youtube-embed' );
		$update_class   = 'error';
	} else {
		$options['api'] = $api_key;
		$update_message = __( 'Settings Saved.', 'youtube-embed' );
		$update_class   = 'updated';
	}

	// Update the options.

	update_option( 'youtube_embed_general', $options );

	// Update the alternative shortcodes.

	$shortcode = sanitize_text_field( $_POST['youtube_embed_shortcode'] );
	$shortcode = trim( $shortcode, '[]' );

	update_option( 'youtube_embed_shortcode', $shortcode );

	echo '<div class="' . $update_class . ' fade"><p><strong>' . $update_message . "</strong></p></div>\n";
}

// Get options.

$options   = ye_get_general_defaults();
$shortcode = ye_get_shortcode();
?>

<p><?php _e( 'These are the general settings for YouTube Embed. Please select <a href="admin.php?page=ye-profile-options">Profiles</a> for default embedding settings.', 'youtube-embed' ); ?></p>

<form method="post" action="<?php echo get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=ye-general-options'; ?>">

<table class="form-table">

<tr>
<th scope="row"><?php _e( 'API Key', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_api"><input type="text" size="50" name="youtube_embed_api" value="<?php echo esc_attr( $options['api'] ); ?>"/></label>
<p class="description"><?php _e( 'Please see the instructions for details on creating your own API key.', 'youtube-embed' ); ?></p></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Embedding', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Add Metadata -->

<tr>
<th scope="row"><label for="youtube_embed_metadata"><?php _e( 'Add Metadata', 'youtube-embed' ); ?></label></th>
<td><input type="checkbox" name="youtube_embed_metadata" value="1" <?php checked( $options['metadata'], '1' ); ?>/>
<?php _e( 'Allow rich metadata to be added to code', 'youtube-embed' ); ?></td>
</tr>

<!-- Feed -->

<tr>
<th scope="row"><?php _e( 'Feed', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_feed"><select name="youtube_embed_feed">
<option value="t"
<?php
if ( 't' == $options['feed'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Text link', 'youtube-embed' ); ?>
</option>
<option value="v"
<?php
if ( 'v' == $options['feed'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Thumbnail', 'youtube-embed' ); ?>
</option>
<option value="b"
<?php
if ( 'b' == $options['feed'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Thumbnail &amp; Text Link', 'youtube-embed' ); ?>
</option>
</select></label>
<p class="description"><?php _e( 'Videos cannot be embedded in feeds. Select how you wish them to be shown instead.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Feed Thumbnail -->

<tr>
<th scope="row">&nbsp;&nbsp;&nbsp;&nbsp;<?php _e( 'Thumbnail to use', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_thumbnail"><select name="youtube_embed_thumbnail">
<option value="default"
<?php
if ( 'default' == $options['thumbnail'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Default', 'youtube-embed' ); ?>
</option>
<option value="hqdefault"
<?php
if ( 'hqdefault' == $options['thumbnail'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Default (HQ)', 'youtube-embed' ); ?>
</option>
<option value="1"
<?php
if ( '1' == $options['thumbnail'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Start', 'youtube-embed' ); ?>
</option>
<option value="2"
<?php
if ( '2' == $options['thumbnail'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Middle', 'youtube-embed' ); ?>
</option>
<option value="3"
<?php
if ( '3' == $options['thumbnail'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'End', 'youtube-embed' ); ?>
</option>
</select></label>
<p class="description"><?php _e( 'Choose which thumbnail to use.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Content Resizing Scripts -->

<tr>
<th scope="row"><?php _e( 'Content Resizing Script', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_script"><select name="youtube_embed_script">
<option value=""
<?php
if ( '' == $options['script'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'None', 'youtube-embed' ); ?>
</option>
<option value="f"
<?php
if ( 'f' == $options['script'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'FitVids.js', 'youtube-embed' ); ?>
</option>
<option value="i"
<?php
if ( 'i' == $options['script'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'iFrame Resizer', 'youtube-embed' ); ?>
</option>
</select></label>
<p class="description"><?php _e( 'Use a third party content resizing script?', 'youtube-embed' ); ?></p></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Shortcodes', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Shortcodes in Widgets -->

<tr>
<th scope="row"><?php _e( 'Allow shortcodes in widgets', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_widgets"><input type="checkbox" name="youtube_embed_widgets" value="1" <?php checked( $options['widgets'], '1' ); ?>/>
<?php _e( 'Allow shortcodes to be used in widgets', 'youtube-embed' ); ?></label>
<p class="description"><?php _e( 'This will apply to <strong>all</strong> widgets.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Alternative Shortcode -->

<tr>
<th scope="row"><?php _e( 'Alternative Shortcode', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_shortcode"><input type="text" size="30" name="youtube_embed_shortcode" value="<?php echo esc_attr( $shortcode ); ?>"/></label>
<p class="description"><?php _e( 'An alternative shortcode to use.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Alternative Shortcode Profile -->

<tr>
<th scope="row">&nbsp;&nbsp;&nbsp;&nbsp;<?php _e( 'Profile to use', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_alt_profile"><select name="youtube_embed_alt_profile">
<?php ye_generate_profile_list( $options['alt_profile'], $options['profile_no'] ); ?>
</select></label></td>
</tr>

<!-- Shortcode Re-Use Prompt -->

<tr>
<th scope="row"><?php _e( 'Show Shortcode Re-use Prompt', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_prompt"><input type="checkbox" name="youtube_embed_prompt" value="1" <?php checked( $options['prompt'], '1' ); ?>/>
<?php _e( 'Show a prompt if the main shortcode is being re-used by another plugin', 'youtube-embed' ); ?></label></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Administration Options', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Admin Bar -->

<tr>
<th scope="row"><?php _e( 'Show in Admin Bar', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_admin_bar"><input type="checkbox" name="youtube_embed_admin_bar" value="1" <?php checked( $options['admin_bar'], '1' ); ?>/>
<?php _e( 'Add link to options screen to Admin Bar', 'youtube-embed' ); ?></label></td>
</tr>

<!-- Menu Screen Access -->

<tr>
<th scope="row"><?php _e( 'Menu Screen Access', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_menu_access"><select name="youtube_embed_menu_access">
<option value="list_users"
<?php
if ( 'list_users' == $options['menu_access'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Administrator', 'youtube-embed' ); ?>
</option>
<option value="edit_pages"
<?php
if ( 'edit_pages' == $options['menu_access'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Editor', 'youtube-embed' ); ?>
</option>
<option value="publish_posts"
<?php
if ( 'edit_pages' == $options['menu_access'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Author', 'youtube-embed' ); ?>
</option>
<option value="edit_posts"
<?php
if ( 'edit_posts' == $options['menu_access'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Contributor', 'youtube-embed' ); ?>
</option>
</select></label>
<p class="description"><?php _e( 'Specify the user access required for the menu screens.', 'youtube-embed' ); ?></p></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Profile &amp; List Sizes', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Number of Profiles -->

<tr>
<th scope="row"><?php _e( 'Number of Profiles', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_profile_no"><input type="text" size="2" maxlength="2" name="youtube_embed_profile_no" value="<?php echo esc_attr( $options['profile_no'] ); ?>"/> <?php _e( 'Maximum number of profiles.', 'youtube-embed' ); ?></label></td>
</tr>

<!-- Number of Lists -->

<tr>
<th scope="row"><?php _e( 'Number of Lists', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_list_no"><input type="text" size="2" maxlength="2" name="youtube_embed_list_no" value="<?php echo esc_attr( $options['list_no'] ); ?>"/> <?php _e( 'Maximum number of lists.', 'youtube-embed' ); ?></label></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Performance', 'youtube-embed' ); ?></h3><table class="form-table">


<!-- Force list specification -->

<tr>
<th scope="row"><?php _e( 'Force list specification', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_list"><input type="checkbox" name="youtube_embed_list" value="1" <?php checked( $options['force_list_type'], '1' ); ?>/>
<?php _e( 'Force users to specify a list type', 'youtube-embed' ); ?></label>
<p class="description"><?php _e( 'By switching this on, a list type must be specified for a list to be valid. This improves performance as use of a list doesn\'t then need to be verified.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Video cache -->

<tr>
<th scope="row"><?php _e( 'Video Cache', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_video_cache"><input type="text" size="2" maxlength="2" name="youtube_embed_video_cache" value="<?php echo esc_attr( $options['video_cache'] ); ?>"/> <?php _e( 'days', 'youtube-embed' ); ?></label>
<p class="description"><?php _e( 'How long to cache the video output. Set to 0 to switch off.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- API cache -->

<tr>
<th scope="row"><?php _e( 'API Cache', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_api_cache"><input type="text" size="2" maxlength="2" name="youtube_embed_api_cache" value="<?php echo esc_attr( $options['api_cache'] ); ?>"/> <?php _e( 'hours', 'youtube-embed' ); ?></label>
<p class="description"><?php _e( 'How long to cache the API data. Set to 0 to switch off.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Lazy-loading -->

<tr>
<th scope="row"><?php _e( 'Lazy-load Video', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_lazyload"><input type="checkbox" name="youtube_embed_lazyload" value="1" <?php checked( $options['lazyload'], '1' ); ?>/>
<?php _e( 'Use native lazy-loading for video', 'youtube-embed' ); ?></label>
<p class="description"><?php _e( 'Native lazy-loading is only supported by some browsers.', 'youtube-embed' ); ?></p></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Security', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Privacy-Enhanced Mode -->

<tr>
<th scope="row"><?php _e( 'Privacy-Enhanced Mode', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_menu_access"><select name="youtube_embed_privacy">
<option value="0"
<?php
if ( '0' == $options['privacy'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Cookies should always be stored', 'youtube-embed' ); ?>
</option>
<option value="1"
<?php
if ( '1' == $options['privacy'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( 'Cookies should never be stored', 'youtube-embed' ); ?>
</option>
<option value="2"
<?php
if ( '2' == $options['privacy'] ) {
	echo " selected='selected'";
}
?>
>
<?php _e( "Cookies should be stored based on user's Do Not Track setting", 'youtube-embed' ); ?>
</option>
</select></label>
<p class="description"><?php _e( 'Read more about <a href="http://donottrack.us/">Do Not Track</a>.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Show debug output -->

<tr>
<th scope="row"><?php _e( 'Show debug output', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_debug"><input type="checkbox" name="youtube_embed_debug" value="1" <?php checked( $options['debug'], '1' ); ?>/>
<?php _e( 'Show debug output as HTML comments', 'youtube-embed' ); ?></label></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Profile Demo Videos', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Standard Video -->

<tr>
<th scope="row"><?php _e( 'Standard Video', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_video"><input type="text" size="30" name="youtube_embed_video" value="<?php echo esc_attr( $options['standard_video'] ); ?>"/></label>
<p class="description"><?php _e( 'ID of a YouTube video.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Playlist Video -->

<tr>
<th scope="row"><?php _e( 'Playlist', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_playlist"><input type="text" size="30" name="youtube_embed_playlist" value="<?php echo esc_attr( $options['playlist_video'] ); ?>"/></label>
<p class="description"><?php _e( 'ID of a YouTube playlist.', 'youtube-embed' ); ?></p></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Miscellaneous', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Validation -->

<tr>
<th scope="row"><?php _e( 'Improve Validation', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_frameborder"><input type="checkbox" name="youtube_embed_frameborder" value="1" <?php checked( $options['frameborder'], '1' ); ?>/>
<?php _e( 'Improve the validity of the generated markup', 'youtube-embed' ); ?></label>
<p class="description"><?php _e( 'Will extend the length of the URL, limiting the number of videos in a manual playlist. Switch off metadata for even better validation results.', 'youtube-embed' ); ?></p></td>
</tr>

</table>

<?php wp_nonce_field( 'youtube-embed-general', 'youtube_embed_general_nonce', true, true ); ?>

<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'youtube-embed' ); ?>"/></p>

</form>

</div>
