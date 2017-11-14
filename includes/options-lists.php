<?php
/**
* Lists Options Page
*
* Screen for specifying different lists and the video IDs within them
*
* @package	youtube-embed
* @since	2.0
*/

// Set current list number

if ( isset( $_POST[ 'youtube_embed_list_no' ] ) ) { $list_no = esc_textarea( $_POST[ 'youtube_embed_list_no' ] ); } else { $list_no = 0; }
if ( $list_no == '' ) { $list_no = 1; }

// If options have been updated on screen, update the database

$message = '';
if ( ( !empty( $_POST[ 'Submit' ] ) ) && ( check_admin_referer( 'youtube-embed-general', 'youtube_embed_general_nonce' ) ) ) {

	$class = 'updated fade';
	$message = __( 'Settings Saved.', 'youtube-embed' );
	$new_id_list = '';

	if ( ( $_POST[ 'youtube_embed_video_list' ] == '' ) or ( $_POST[ 'youtube_embed_name' ] == '' ) ) {
		$class = 'error';
		$message = __( 'All fields must be completed.', 'youtube-embed' );
	} else {
		$id_array = explode( "\n", esc_textarea( $_POST[ 'youtube_embed_video_list' ] ) );
		$loop = 0;
		$valid = true;

		// Loop through the video IDs

		while ( $loop < count( $id_array ) ) {

			// Extract the ID from the provided data

			$id = trim( ye_extract_id( $id_array[ $loop ] ) );

			// Now check its validity using the API data

			if ( $id != '' ) {
				$data = ye_get_api_data( $id );
				$valid = $data[ 'valid' ];
				if ( $data[ 'type' ] != 'v' ) { $valid = false; }
				$new_id_list .= $id . "\n";
			}
			$loop ++;
		}

		// If one or more IDs weren't valid, output an error

		if ( !$valid ) {
			$class = 'error';
			$message = __( 'Errors were found with your video list. See the list below for details.', 'youtube-embed' );
		}
	}

	// Update the options

	$options[ 'list_name' ] = $_POST[ 'youtube_embed_name' ];

	if ( $new_id_list == '' ) {
		$options[ 'list' ] = sanitize_text_field( $_POST[ 'youtube_embed_video_list' ] );
	} else {
		$options[ 'list' ] = substr( $new_id_list, 0, strlen( $new_id_list ) - 1 );
	}

	if ( substr( $class, 0, 7 ) == 'updated' ) { update_option( 'youtube_embed_list' . $list_no, $options ); }

} else {
	$class = '';
}

// Fetch options into an array

if ( $class != "error" ) { $options = ye_get_list( $list_no ); }
$general = ye_get_general_defaults();

// Get number of lists in use

$loop = 0;
$max_lists = 0;
while ( $loop <= $general[ 'list_no' ] ) {

	$list = ye_get_list( $loop );
	if ( !$list[ 'default' ] ) { $max_lists ++; }

	$loop ++;
}

// Display any screen headings

?>
<div class="wrap">
<h1><?php _e( 'YouTube Embed Lists', 'youtube-embed' ); ?><span class="title-count"><?php echo $max_lists; ?></span></h1>

<?php

// Output any messages

if ( $message != '' ) {	echo '<div class="' . $class . '"><p><strong>' . $message . "</strong></p></div>\n"; }
?>

<form method="post" action="<?php echo get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=ye-list-options'; ?>">

<span class="alignright">
<select name="youtube_embed_list_no">
<?php
$loop = 1;
while ( $loop <= $general[ 'list_no' ] ) {

	$listfiles = ye_get_list( $loop );
	if ( $listfiles[ 'list' ] != '' ) {
		$listname = $listfiles[ 'list_name' ];
		$list_found = true;
	} else {
		$listname = __( 'List', 'youtube-embed' ) . ' ' . $loop;
		$list_found = false;
	}
	echo '<option value="' . $loop . '"';
	if ( $list_no == $loop ) { echo " selected='selected'"; }
	echo '>' . $listname;
	if ( !$list_found ) { echo ' [undefined]'; }
	echo "</option>\n";

	$loop ++;
}
?>
</select>
<input type="submit" name="List" class="button-secondary" value="<?php _e( 'Change list' ); ?>"/>
</span><br/>

<?php echo sprintf( __( 'These are the options for list %s.', 'youtube-embed' ), $list_no) . '<br/>' . __( 'Update the name, if required, and specify a list of YouTube video IDs. Use the drop-down on the right hand side to swap between lists.', 'youtube-embed' ); ?>

<table class="form-table">

<!-- List Name -->

<tr>
<th scope="row"><?php _e( 'List Name', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_name"><input type="text" size="20" name="youtube_embed_name" value="<?php echo esc_attr( $options[ 'list_name' ] ); ?>"/>
<?php _e( 'The name you wish to give this list', 'youtube-embed' ); ?></label></td>
</tr>

<!-- Video IDs -->

<tr>
<th scope="row"><?php _e( 'Video IDs (one per line)', 'youtube-embed' ); ?></th>
<td><label for="youtube_embed_list"><textarea name="youtube_embed_video_list" rows="10" cols="20" class="regular-text code"><?php echo esc_textarea( $options[ 'list' ] ); ?></textarea></label></td>
</tr>

</table>

<?php wp_nonce_field( 'youtube-embed-general','youtube_embed_general_nonce', true, true ); ?>

<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'youtube-embed' ); ?>"/></p>

</form>

<?php

// If video IDs exist display them on screen along with their status'
if ( $options[ 'list' ] != '' ) {

	$id_array = explode( "\n", $options[ 'list' ] );

	echo "<table class=\"widefat\" style=\"max-width: 800px\">\n<thead>\n\t<tr>\n\t\t<th>" . __( 'Video ID', 'youtube-embed' ) . "</th>\n\t\t<th>" . __( 'Video Title', 'youtube-embed' ) . "</th>\n\t\t<th>" . __( 'Status', 'youtube-embed' ) . "</th>\n\t</tr>\n</thead>\n<tbody>\n";
	$loop = 0;

	while ( $loop < count( $id_array ) ) {

		// Extract the ID from the provided data

		$id = trim( ye_extract_id( $id_array[ $loop ] ) );
		if ( $id != '' ) {

			// Validate the video type

			$api_data = ye_get_api_data( $id );

			if ( $api_data[ 'type' ] == 'p' ) {
				$text = __( 'This is a playlist', 'youtube-embed' );
				$status = '-1';
			} else {
				if ( !$api_data[ 'valid' ] ) {
					$text = __( 'Invalid video ID', 'youtube-embed' );
					$status = '-2';
				} else {
					if ( $api_data[ 'valid' ] ) {
						$text = __( 'Valid video', 'youtube-embed' );
						$status = '0';
					}
				}
			}

			// Output the video information

			echo "\t<tr>\n\t\t<td>" . $id . "</td>\n";
			echo "\t\t<td>";
			if ( $api_data[ 'api' ] ) {
				if ( $api_data[ 'title' ] == '' ) {
					echo '[No title available]';
				} else {
					echo $api_data[ 'title' ];
				}
			} else {
				echo '[No title - API not available]';
			}
			echo "</td>\n";
			echo "\t\t<td style=\"";

			if ( $status != 0 ) {
				echo 'font-weight: bold; color: #f00;';
			}

			echo '"><img src="' . plugins_url( 'images/', dirname(__FILE__) );

			if ( $status == 0 ) {
				$alt_text = __( 'The video ID is valid', 'youtube-embed' );
				echo 'tick.png" alt="' . $alt_text . '" ';
			} else {
				$alt_text = __( 'The video ID is invalid', 'youtube-embed' );
				echo 'cross.png" alt="' . $alt_text . '" ';
			}

			echo "height=\"16px\" width=\"16px\"/>&nbsp;" . $text . "</td>\n\t</tr>\n";
		}
		$loop ++;
	}
	echo "</tbody>\n</table>\n";
}
?>

</div>
