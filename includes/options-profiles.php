<?php
/**
* Profiles Options Page
*
* Screen for specifying different profiles and settings the options for each
*
* @package	youtube-embed
* @since	2.0
*/

$demo_video = 'EYs_FckMqow';

// Set current profile number

if ( isset( $_POST[ 'youtube_embed_profile_no' ] ) ) { $profile_no = sanitize_text_field( $_POST[ 'youtube_embed_profile_no' ] ); } else { $profile_no = 0; }
if ( $profile_no == '' ) { $profile_no = 0; }

// If options have been updated on screen, update the database

if ( ( !empty( $_POST[ 'Submit' ] ) ) && ( check_admin_referer( 'youtube-embed-profile' , 'youtube_embed_profile_nonce' ) ) ) {

	$options[ 'profile_name' ] = sanitize_text_field( $_POST[ 'youtube_embed_name' ] );
	$options[ 'width' ] = sanitize_text_field( $_POST[ 'youtube_embed_width' ] );
	$options[ 'height' ] = sanitize_text_field( $_POST[ 'youtube_embed_height' ] );

	$allowed_html = array(	'a' => array(	'href' => array(),
						        			'title' => array(),
											'target' => array(),
											'class' => array(),
											'id' => array(),
											'style' => array()
						    			),
							'img' => array(	'src' => array(),
											'alt' => array(),
											'height' => array(),
											'width' => array(),
											'align' => array(),
											'class' => array(),
											'id' => array(),
											'style' => array()
						    			),
							'div' => array(	'class' => array(),
											'id' => array(),
											'align' => array(),
											'style' => array()
										),
							'span' => array('class' => array(),
											'id' => array(),
											'style' => array()
										),
						    'br' => array(),
						    'p' => array(),
						    'strong' => array(),
						);

	$options[ 'template' ] = wp_kses( htmlspecialchars_decode( $_POST[ 'youtube_embed_template' ] ), $allowed_html );
	if ( strpos( $options[ 'template' ], '%video%' ) === false ) { $options[ 'template' ] = '%video%'; }

	$options[ 'style' ] = sanitize_text_field( $_POST[ 'youtube_embed_style' ] );
	$options[ 'autohide'] = sanitize_text_field( $_POST[ 'youtube_embed_autohide' ] );
	$options[ 'controls'] = sanitize_text_field( $_POST[ 'youtube_embed_controls' ] );
	$options[ 'wmode'] = sanitize_text_field( $_POST[ 'youtube_embed_wmode' ] );
	$options[ 'color' ] = sanitize_text_field( $_POST[ 'youtube_embed_color' ] );
	$options[ 'theme' ] = sanitize_text_field( $_POST[ 'youtube_embed_theme' ] );
	$options[ 'download_style' ] = sanitize_text_field( $_POST[ 'youtube_embed_download_style' ] );

	$options[ 'download_text' ] = str_replace( '\"', '"', str_replace( "\'", "'", $_POST[ 'youtube_embed_download_text' ] ) );

	if ( isset( $_POST[ 'youtube_embed_playsinline' ] ) ) { $options[ 'playsinline' ] = sanitize_text_field( $_POST[ 'youtube_embed_playsinline' ] ); } else { $options[ 'playsinline' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_fullscreen' ] ) ) { $options[ 'fullscreen' ] = sanitize_text_field( $_POST[ 'youtube_embed_fullscreen' ] ); } else { $options[ 'fullscreen' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_autoplay' ] ) ) { $options[ 'autoplay'] = sanitize_text_field( $_POST[ 'youtube_embed_autoplay' ] ); } else { $options[ 'autoplay' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_loop' ] ) ) { $options[ 'loop'] = sanitize_text_field( $_POST[ 'youtube_embed_loop' ] ); } else { $options[ 'loop' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_cc' ] ) ) { $options[ 'cc'] = sanitize_text_field( $_POST[ 'youtube_embed_cc' ] ); } else { $options[ 'cc' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_annotation' ] ) ) { $options[ 'annotation'] = sanitize_text_field( $_POST[ 'youtube_embed_annotation' ] ); } else { $options[ 'annotation' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_related' ] ) ) { $options[ 'related'] = sanitize_text_field( $_POST[ 'youtube_embed_related' ] ); } else { $options[ 'related' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_info' ] ) ) { $options[ 'info'] = sanitize_text_field( $_POST[ 'youtube_embed_info' ] ); } else { $options[ 'info' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_disablekb' ] ) ) { $options[ 'disablekb'] = sanitize_text_field( $_POST[ 'youtube_embed_disablekb' ] ); } else { $options[ 'disablekb' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_html5' ] ) ) { $options[ 'html5'] = sanitize_text_field( $_POST[ 'youtube_embed_html5' ] ); } else { $options[ 'html5' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_modest' ] ) ) { $options[ 'modest' ] = sanitize_text_field( $_POST[ 'youtube_embed_modest' ] ); } else { $options[ 'modest' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_dynamic' ] ) ) { $options[ 'dynamic' ] = sanitize_text_field( $_POST[ 'youtube_embed_dynamic' ] ); } else { $options[ 'dynamic' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_fixed' ] ) ) { $options[ 'fixed' ] = sanitize_text_field( $_POST[ 'youtube_embed_fixed' ] ); } else { $options[ 'fixed' ] = ''; }
	if ( isset( $_POST[ 'youtube_embed_download' ] ) ) { $options[ 'download' ] = sanitize_text_field( $_POST[ 'youtube_embed_download' ] ); } else { $options[ 'download' ] = ''; }

	$default_size = $_POST[ 'youtube_embed_size' ];

	if ( $default_size !== '' ) {
		$options[ 'width' ] = ltrim( substr( $default_size, 0, 4 ), '0' );
		$options[ 'height'] = ltrim( substr( $default_size, -4, 4 ), '0' );
	}

	// Set width or height, if missing

	if ( ( $options[ 'width' ] == '' ) && ( $options[ 'height' ] == '' ) ) {
		if ( isset( $GLOBALS[ 'content_width' ] ) ) {
			$options[ 'width' ] = $GLOBALS[ 'content_width' ];
		} else {
			$options[ 'width' ] = 560;
		}
		$options[ 'height' ] = 27 + round( ( $options[ 'width' ] / 16 ) * 9, 0 );
	}
	if ( ( $options[ 'width' ] == '' ) && ( $options[ 'height' ] != '' ) ) {
		$options[ 'width' ] = round( ( $options[ 'height' ] / 9 ) * 16, 0 );
	}
	if ( ( $options[ 'width' ] != '' ) && ( $options[ 'height' ] == '' ) ) {
		$options[ 'height' ] = 27 + round( ( $options[ 'width' ] / 16 ) * 9, 0 );
	}

	update_option( 'youtube_embed_profile' . $profile_no, $options );
	$updated = true;

} else {

	$default_size = '';
	$updated = false;
}

// Fetch options into an array

$options = ye_get_profile( $profile_no );
$general = ye_get_general_defaults();

// Get number of profiles in use

$loop = 0;
$max_profiles = 0;
while ( $loop <= $general[ 'profile_no' ] ) {

	$profile = ye_get_profile( $loop );
	if ( !$profile[ 'default' ] ) { $max_profiles ++; }

	$loop ++;
}


// Output headings
?>

<div class="wrap">

<h1><?php _e( 'YouTube Embed Profiles', 'youtube-embed' ); ?><span class="title-count"><?php echo $max_profiles; ?></span></h1>

<?php

// Output message text

if ( $updated ) { echo '<div class="updated fade"><p><strong>' . __( $options[ 'profile_name' ].' Profile Saved.' ) . "</strong></p></div>\n"; }

// Video option button has been pressed

if ( !empty( $_POST[ 'Video' ] ) ) { $video_type = $_POST[ 'youtube_embed_video_type' ]; } else { $video_type = 'd'; }

// Generate Profile list
?>
<form method="post" action="<?php echo get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=ye-profile-options' ?>">

<span class="alignright">
<select name="youtube_embed_profile_no">
<?php ye_generate_profile_list( $profile_no, $general[ 'profile_no' ], true ) ?>
</select>
<input type="submit" name="Profile" class="button-secondary" value="<?php _e( 'Change profile', 'youtube-embed' ); ?>"/>
</span><br/>

<?php

// Show explanatory message at top of screen

if ( $profile_no == '0' ) {
	_e( 'These are the options for the default profile.', 'youtube-embed' );
} else {
	sprintf( _e( 'These are the options for profile %s.', 'youtube-embed' ), $profile_no );
}
echo ' ' . __( 'Use the drop-down on the right hand side to swap between profiles.', 'youtube-embed' );
?>

<table class="form-table">

<!-- Profile Name -->

<tr>
<th scope="row"><?php _e( 'Profile Name', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_name"><input type="text" size="20" name="youtube_embed_name" value="<?php echo esc_attr( $options[ 'profile_name' ] ); ?>"<?php if ( $profile_no == 0 ) { echo ' readonly="readonly"'; } ?>/>
<?php if ( $profile_no != 0 ) { _e( 'The name you wish to give this profile', 'youtube-embed' ); } ?></label></td>
</tr>

<!-- Template -->

<tr>
<th scope="row"><?php _e( 'Template', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_template"><textarea name="youtube_embed_template" rows="4" cols="50" class="large-text code"><?php echo esc_html( $options[ 'template' ] ); ?></textarea>
<p class="description"><?php _e( 'Wrapper for video output. Must include <code>%video%</code> tag to show video position. Valid HTML tags are <code>a</code>, <code>br</code>, <code>div</code>, <code>img</code>, <code>p</code>, <code>span</code> and <code>strong</code>.', 'youtube-embed' ); ?></p></label></td>
</tr>

<!-- Style -->

<tr>
<th scope="row"><?php _e( 'Style', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_style"><input type="text" name="youtube_embed_style" class="large-text code" value="<?php echo esc_html( $options[ 'style' ] ); ?>"/>
<p class="description"><?php _e( 'CSS elements to apply to video.', 'youtube-embed' ); ?></p></label></td>
</tr>

<!-- Window Mode -->

<tr>
<th scope="row"><?php _e( 'Window Mode', 'youtube-embed' ); ?>&nbsp;<img src="<?php echo plugins_url( 'images/flash.png', dirname(__FILE__) ); ?>" alt="<?php _e( 'Flash', 'youtube-embed' ) ?>" width="10px" align="top"></th>
<td><label for="youtube_embed_wmode"><select name="youtube_embed_wmode">
<option value="opaque"<?php if ( $options[ 'wmode' ] == "opaque" ) { echo " selected='selected'"; } ?>><?php _e( 'Opaque', 'youtube-embed' ); ?></option>
<option value="transparent"<?php if ( $options[ 'wmode' ] == "transparent" ) { echo " selected='selected'"; } ?>><?php _e( 'Transparent', 'youtube-embed' ); ?></option>
<option value="window"<?php if ( $options[ 'wmode' ] == "window" ) { echo " selected='selected'"; } ?>><?php _e( 'Window', 'youtube-embed' ); ?></option>
</select></label>
<p class="description"><?php _e( 'Sets the Window Mode property of the Flash movie for transparency, layering, and positioning in the browser. <a href="http://www.communitymx.com/content/article.cfm?cid=e5141">Learn more</a>.', 'youtube-embed' ); ?></p></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Video Size', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Video Size -->

<tr>
<th scope="row"><?php _e( 'Video Size', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_height"><input type="text" size="3" maxlength="3" name="youtube_embed_width" value="<?php echo esc_attr( $options[ 'width' ] ); ?>"/>&nbsp;x&nbsp;<input type="text" size="3" maxlength="3" name="youtube_embed_height" value="<?php echo esc_attr( $options[ 'height' ] ); ?>"/>
<?php _e( 'The width x height of the video, in pixels', 'youtube-embed' ); ?></label></td>
</tr>

<!-- Default Sizes -->

<tr>
<th scope="row"><?php _e( 'Default Sizes', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_size"><select name="youtube_embed_size">
<option value=""<?php if ( $default_size == '' ) { echo " selected='selected'"; } ?>><?php _e( 'Use above sizes', 'youtube-embed' ); ?></option>
<option value="04260420"<?php if ( $default_size == "04260420" ) { echo " selected='selected'"; } ?>><?php echo '240p'; ?></option>
<option value="06400360"<?php if ( $default_size == "06400360" ) { echo " selected='selected'"; } ?>><?php echo '360p'; ?></option>
<option value="08540480"<?php if ( $default_size == "08540480" ) { echo " selected='selected'"; } ?>><?php echo '480p'; ?></option>
<option value="12800720"<?php if ( $default_size == "12800720" ) { echo " selected='selected'"; } ?>><?php echo '720p'; ?></option>
<option value="19201080"<?php if ( $default_size == "19201080" ) { echo " selected='selected'"; } ?>><?php echo '1080p'; ?></option>
<option value="38402160"<?php if ( $default_size == "38402160" ) { echo " selected='selected'"; } ?>><?php echo '1440p'; ?></option>
<option value="38402160"<?php if ( $default_size == "38402160" ) { echo " selected='selected'"; } ?>><?php echo '2160p'; ?></option>
</select></label><p class="description"><?php _e( 'Select one of these default sizes to override the above video sizes.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Responsive Output -->

<tr>
<th scope="row"><?php _e( 'Responsive Output', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_dynamic"><input type="checkbox" name="youtube_embed_dynamic" value="1"<?php if ( $options[ 'dynamic' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'Show full width and resize with the browser', 'youtube-embed' ); ?></label></td>
</tr>

<!-- Maximum Size -->

<tr>
<th scope="row">&nbsp;&nbsp;&nbsp;&nbsp;<?php _e( 'Set Maximum Size', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_fixed"><input type="checkbox" name="youtube_embed_fixed" value="1"<?php if ( $options[ 'fixed' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'Use above width to define maximum size', 'youtube-embed' ); ?></label></td>
</tr>

<!-- Playsinline -->

<tr>
<th scope="row"><?php _e( 'Plays Inline', 'youtube-embed' ); ?>&nbsp;<img src="<?php echo plugins_url( 'images/html5.png', dirname(__FILE__) ); ?>" alt="<?php _e( 'HTML5', 'youtube-embed' ) ?>" width="10px" align="top"></th>

<td><label for="youtube_embed_playsinline"><input type="checkbox" name="youtube_embed_playsinline" value="1"<?php if ( $options[ 'playsinline' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'Whether videos play inline or fullscreen in an HTML5 player on iOS. ', 'youtube-embed' ); ?></label></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Playback', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Autoplay -->

<tr>
<th scope="row"><?php _e( 'Autoplay', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_autoplay"><input type="checkbox" name="youtube_embed_autoplay" value="1"<?php if ( $options[ 'autoplay' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'Choose whether the initial video will automatically start to play when the player loads', 'youtube-embed' ); ?></label></td>
</tr>

<!-- Loop Video -->

<tr>
<th scope="row"><?php _e( 'Loop Video', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_loop"><input type="checkbox" name="youtube_embed_loop" value="1"<?php if ( $options[ 'loop' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'Play the initial video again and again', 'youtube-embed' ); ?></label>
<p class="description"><?php _e( 'In the case of a playlist, this will play the entire playlist and then start again at the first video.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Annotations -->

<tr>
<th scope="row"><?php _e( 'Annotations', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_annotation"><input type="checkbox" name="youtube_embed_annotation" value="1"<?php if ( $options[ 'annotation' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'Video annotations are shown by default', 'youtube-embed' ); ?></label></td>
</tr>

<!-- Closed Captions -->

<tr>
<th scope="row"><?php _e( 'Closed Captions', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_cc"><select name="youtube_embed_cc">
<option value="0"<?php if ( $options[ 'cc' ] == "0" ) { echo " selected='selected'"; } ?>><?php _e( 'Don\'t show closed captions', 'youtube-embed' ); ?></option>
<option value="1"<?php if ( $options[ 'cc' ] == "1" ) { echo " selected='selected'"; } ?>><?php _e( 'Show closed captions', 'youtube-embed' ); ?></option>
<option value=""<?php if ( $options[ 'cc' ] == "" ) { echo " selected='selected'"; } ?>><?php _e( 'User default', 'youtube-embed' ); ?></option>
</select></label>
<p class="description"><?php _e( 'Show closed captions (subtitles).', 'youtube-embed' ); ?></p></td>
</tr>

<!-- HTML5 -->

<tr>
<th scope="row"><?php _e( 'Default to HTML5', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_html5"><input type="checkbox" name="youtube_embed_html5" value="1"<?php if ( $options[ 'html5' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'Default to the HTML5 player (if available)', 'youtube-embed' ); ?></label>
<p class="description"><?php _e( 'This is an undocumented feature and may not work.', 'youtube-embed' ); ?></p></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Information', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Info -->

<tr>
<th scope="row"><?php _e( 'Information', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_info"><input type="checkbox" name="youtube_embed_info" value="1"<?php if ( $options[ 'info' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'Display the video title and uploader before the video starts', 'youtube-embed' ); ?></label>
<p class="description"><?php _e( 'If displaying a playlist this will show video thumbnails.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Related -->

<tr>
<th scope="row"><?php _e( 'Related Videos', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_related"><input type="checkbox" name="youtube_embed_related" value="1"<?php if ( $options[ 'related' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'Load related videos once playback starts', 'youtube-embed' ); ?></label>
<p class="description"><?php _e( 'Also toggles the search option.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Modest Branding -->

<tr>
<th scope="row"><?php _e( 'Modest Branding', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_modest"><input type="checkbox" name="youtube_embed_modest" value="1"<?php if ( $options[ 'modest' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'Reduce branding on video', 'youtube-embed' ); ?></label></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Controls', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Auto Hide -->

<tr>
<th scope="row"><?php _e( 'Auto hide', 'youtube-embed' ); ?>&nbsp;<img src="<?php echo plugins_url( 'images/flash.png', dirname(__FILE__) ); ?>" alt="<?php _e( 'Flash', 'youtube-embed' ) ?>" width="10px" align="top"></th>
<td><label for="youtube_embed_autohide"><select name="youtube_embed_autohide">
<option value="0"<?php if ( $options[ 'autohide' ] == "0" ) { echo " selected='selected'"; } ?>><?php _e( 'Controls &amp; progress bar remain visible', 'youtube-embed' ); ?></option>
<option value="1"<?php if ( $options[ 'autohide' ] == "1" ) { echo " selected='selected'"; } ?>><?php _e( 'Controls &amp; progress bar fade out', 'youtube-embed' ); ?></option>
<option value="2"<?php if ( $options[ 'autohide' ] == "2" ) { echo " selected='selected'"; } ?>><?php _e( 'Progress bar fades', 'youtube-embed' ); ?></option>
</select></label>
<p class="description"><?php _e( 'Whether the video controls will automatically hide after a video begins playing.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Controls -->

<tr>
<th scope="row"><?php _e( 'Controls', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_controls"><select name="youtube_embed_controls">
<option value="0"<?php if ( $options[ 'controls' ] == "0" ) { echo " selected='selected'"; } ?>><?php _e( 'Controls do not display &amp; Flash player loads immediately', 'youtube-embed' ); ?></option>
<option value="1"<?php if ( $options[ 'controls' ] == "1" ) { echo " selected='selected'"; } ?>><?php _e( 'Controls display &amp; Flash player loads immediately', 'youtube-embed' ); ?></option>
<option value="2"<?php if ( $options[ 'controls' ] == "2" ) { echo " selected='selected'"; } ?>><?php _e( 'Controls display &amp; Flash player loads once video starts', 'youtube-embed' ); ?></option>
</select></label>
<p class="description"><?php _e( 'Whether the video player controls are displayed. For Flash it also defines when the controls display in the player as well as when the player will load.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Disable Keyboard -->

<tr>
<th scope="row"><?php _e( 'Disable Keyboard', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_disablekb"><input type="checkbox" name="youtube_embed_disablekb" value="1"<?php if ( $options[ 'disablekb' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'Disable the player keyboard controls', 'youtube-embed' ); ?></label></td>
</tr>

<!-- Fullscreen -->

<tr>
<th scope="row"><?php _e( 'Fullscreen', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_fullscreen"><input type="checkbox" name="youtube_embed_fullscreen" value="1"<?php if ( $options[ 'fullscreen' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php _e( 'A button will allow the viewer to watch the video fullscreen', 'youtube-embed' ); ?></label></td>
</tr>

<!-- Theme -->

<tr>
<th scope="row"><?php _e( 'Theme', 'youtube-embed' ); ?>&nbsp;<img src="<?php echo plugins_url( 'images/flash.png', dirname(__FILE__) ); ?>" alt="<?php _e( 'Flash', 'youtube-embed' ) ?>" width="10px" align="top"></th>
<td><label for="youtube_embed_theme"><select name="youtube_embed_theme">
<option value="dark"<?php if ( $options[ 'theme' ] == "dark" ) { echo " selected='selected'"; } ?>><?php _e( 'Dark', 'youtube-embed' ); ?></option>
<option value="light"<?php if ( $options[ 'theme' ] == "light" ) { echo " selected='selected'"; } ?>><?php _e( 'Light', 'youtube-embed' ); ?></option>
</select></label>
<p class="description"><?php _e( 'Display player controls within a dark or light control bar.', 'youtube-embed' ); ?></p></td>
</tr>

<!-- Color -->

<tr>
<th scope="row"><?php _e( 'Progress Bar Color', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_color"><select name="youtube_embed_color">
<option value="red"<?php if ( $options[ 'color' ] == "red" ) { echo " selected='selected'"; } ?>><?php _e( 'Red', 'youtube-embed' ); ?></option>
<option value="white"<?php if ( $options[ 'color' ] == "white" ) { echo " selected='selected'"; } ?>><?php _e( 'White (desaturated)', 'youtube-embed' ); ?></option>
</select></label>
<p class="description"><?php _e( 'The color that will be used in the player\'s video progress bar to highlight the amount of the video that\'s already been seen.', 'youtube-embed' ); ?></p></td>
</tr>

</table><hr><h3 class="title"><?php _e( 'Download Link', 'youtube-embed' ); ?></h3><table class="form-table">

<!-- Download Link -->

<tr>
<th scope="row"><?php _e( 'Show Download Link', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_download"><input type="checkbox" name="youtube_embed_download" value="1"<?php if ( $options[ 'download' ] == "1" ) { echo ' checked="checked"'; } ?>/>
<?php echo sprintf( __( 'Show a link to %s under the video', 'youtube-embed' ), '<a href="http://keepvid.com/" rel="nofollow">KeepVid</a>' ); ?></label></td>
</tr>

<!-- Download Text -->

<tr>
<th scope="row"><?php _e( 'Download Text', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_download_txt"><input type="text" name="youtube_embed_download_text" class="large-text" value="<?php echo esc_html( $options[ 'download_text' ] ); ?>"/>
<p class="description"><?php _e( 'Text or HTML to display to prompt download.', 'youtube-embed' ); ?></p></label></td>
</tr>

<!-- Download Style -->

<tr>
<th scope="row"><?php _e( 'Download Style', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_download_style"><input type="text" name="youtube_embed_download_style" class="large-text code" value="<?php echo esc_html( $options[ 'download_style' ] ); ?>"/>
<p class="description"><?php _e( 'CSS elements to apply to video download link.', 'youtube-embed' ); ?></p></label></td>
</tr>

</table>

<?php wp_nonce_field( 'youtube-embed-profile', 'youtube_embed_profile_nonce', true, true ); ?>

<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'youtube-embed' ); ?>"/></p>

</form>

<a href="#" name="video"></a>
<div style="max-width: <?php echo esc_attr( $options[ 'width' ] ); ?>px">
<form method="post" action="<?php echo get_bloginfo( 'wpurl' ).'/wp-admin/admin.php?page=ye-profile-options#video' ?>">
<h3><?php _e( 'YouTube Video Sample', 'youtube-embed' ); ?></h3>
<p><?php _e( 'The video below uses the above, saved profile settings. Use the drop-down below to change which parameters the video uses - press the Change Video button to update it.', 'youtube-embed' ); ?></p>
<p><select name="youtube_embed_video_type">
<option value="d"<?php if ( $video_type == "d" ) { echo " selected='selected'"; } ?>><?php _e( 'Standard', 'youtube-embed' ); ?></option>
<option value="3"<?php if ( $video_type == "3" ) { echo " selected='selected'"; } ?>><?php _e( '3D', 'youtube-embed' ); ?></option>
<option value="l"<?php if ( $video_type == "l" ) { echo " selected='selected'"; } ?>><?php _e( 'Playlist', 'youtube-embed' ); ?></option>
</select>
<?php wp_nonce_field( 'youtube-embed-profile', 'youtube_embed_profile_nonce', true, true ); ?>
<input type="submit" name="Video" class="button-secondary" value="<?php _e( 'Change video', 'youtube-embed' ); ?>"/></p>

<p><?php
if ( $video_type == "d" ) { $id = $demo_video; }
if ( $video_type == "3" ) { $id = 'NR5UoBY87GM'; }
if ( $video_type == "l" ) { $id = '095393D5B42B2266'; }
echo ye_generate_youtube_code( array( 'id' => $id, 'profile' => $profile_no ) );
?></p>

</form></div>

</div>
