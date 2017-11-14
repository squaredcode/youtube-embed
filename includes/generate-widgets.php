<?php
/**
* Widgets
*
* Create and display widgets
*
* @package	youtube-embed
*/

class YouTubeEmbedWidget extends WP_Widget {

	/**
	* Widget Constructor
	*
	* Call WP_Widget class to define widget
	*
	* @since	2.0
	*
	* @uses		WP_Widget				Standard WP_Widget class
	*/

	function __construct() {

		parent::__construct( 'youtube_embed_widget',
							__( 'YouTube Embed', 'youtube-embed' ),
							array( 'description' => __( 'Embed YouTube Widget.', 'youtube-embed' ),
							'class' => 'ye-widget',
							'customize_selective_refresh' => true )
							);
	}

	/**
	* Display widget
	*
	* Display the YouTube widget
	*
	* @since	2.0
	*
	* @uses		generate_youtube_code	Generate the required YouTube code
	*
	* @param	string		$args			Arguments
	* @param	string		$instance		Instance
	*/

	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		// Output the header
		echo $before_widget;

		// Extract title for heading
		$title = $instance[ 'titles' ];

		// Output title, if one exists
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }

		// Set the correct
		if ( $instance[ 'id_type' ] == 's' ) { $search = 1; } else { $search = ''; }
		if ( $instance[ 'id_type' ] == 'u' ) { $user = 1; } else { $user = ''; }

		// Built the parameter array

		$array = array(
					'id' => $instance[ 'id' ],
					'start' => $instance[ 'start' ],
					'stop' => $instance[ 'start' ],
					'profile' => $instance[ 'profile' ],
					'list_style' => $instance[ 'list' ],
					'search' => $search,
					'user' => $user );

		// Generate the video and output it

		echo apply_filters( 'a3_lazy_load_html', ye_generate_youtube_code ( $array ) );

		// Output the trailer
		echo $after_widget;
	}

	/**
	* Widget update/save function
	*
	* Update and save widget
	*
	* @since	2.0
	*
	* @param	string		$new_instance		New instance
	* @param	string		$old_instance		Old instance
	* @return	string							Instance
	*/

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance[ 'titles' ] = strip_tags( $new_instance[ 'titles' ] );
		$instance[ 'id' ] = $new_instance[ 'id' ];
		$instance[ 'profile' ] = $new_instance[ 'profile' ];
		$instance[ 'start' ] = $new_instance[ 'start' ];
		$instance[ 'list' ] = $new_instance[ 'list' ];
		$instance[ 'stop' ] = $new_instance[ 'stop' ];
		$instance[ 'id_type' ] = $new_instance[ 'id_type' ];

		return $instance;
	}

	/**
	* Widget Admin control form
	*
	* Define admin file
	*
	* @since	2.0
	*
	* @uses		ye_get_general_defaults	Fetch general options
	*
	* @param	string		$instance		Instance
	*/

	function form( $instance ) {

		// Set default options

		$default = array( 'titles' => 'YouTube', 'id' => '', 'profile' => '', 'start' => '', 'list' => '', 'stop' => '', 'id_type' => 'v' );
		$instance = wp_parse_args( ( array ) $instance, $default );
		$general = ye_get_general_defaults();

		// Widget Title field

		$field_id = $this -> get_field_id( 'titles' );
		$field_name = $this -> get_field_name( 'titles' );
		echo "\r\n" . '<p><label for="' . $field_id . '">' . __( 'Widget Title', 'youtube-embed' ) . ': </label><input type="text" class="widefat" id="' . $field_id . '" name="' . $field_name . '" value="' . esc_attr( $instance[ 'titles' ] ).'" /></p>';

		// Video ID field

		$field_id = $this -> get_field_id( 'id' );
		$field_name = $this -> get_field_name( 'id' );
		echo "\r\n" . '<p><label for="' . $field_id . '">' . __( 'Video ID', 'youtube-embed' ) . ': </label><input type="text" class="widefat" id="' . $field_id . '" name="' . $field_name . '" value="' . esc_attr( $instance[ 'id' ] ) . '" /></p>';

		// ID Type

		echo "<table>\n";

		$field_id = $this -> get_field_id( 'id_type' );
		$field_name = $this -> get_field_name( 'id_type' );
		echo "\r\n" . '<tr><td width="100%">' . __( 'ID Type', 'youtube-embed' ) . '</td><td><select name="' . $field_name . '" id="' . $field_id . '"><option value="v"';
		if ( esc_attr( $instance[ 'id_type' ] ) == 'v' ) { echo " selected='selected'"; }
		echo '>' . __( 'Video or Playlist', 'youtube-embed' ) . '</option><option value="s"';
		if ( esc_attr( $instance[ 'id_type' ] ) == 's' ) { echo " selected='selected'"; }
		echo '>' . __( 'Search', 'youtube-embed' ) . '</option><option value="u"';
		if ( esc_attr( $instance[ 'id_type' ] ) == 'u' ) { echo " selected='selected'"; }
		echo '>' . __( 'User', 'youtube-embed' ) . '</option></select></td></tr>';

		echo "</table>\n";

		// Profile field

		$field_id = $this -> get_field_id( 'profile' );
		$field_name = $this -> get_field_name( 'profile' );
		echo "\r\n" . '<p><label for="' . $field_id . '">' . __( 'Profile', 'youtube-embed' ) . ': </label><select name="' . $field_name . '" class="widefat" id="' . $field_id . '">';
		ye_generate_profile_list( esc_attr( $instance[ 'profile' ] ), $general[ 'profile_no' ]  );
		echo '</select></p>';

		echo "<table>\n";

		// Start field

		$field_id = $this -> get_field_id( 'start' );
		$field_name = $this -> get_field_name( 'start' );
		echo "\r\n" . '<tr><td width="100%">' . __( 'Start (seconds)', 'youtube-embed' ) . '</td><td><input type="text" size="3" maxlength="3" id="' . $field_id . '" name="' . $field_name . '" value="' . esc_attr( $instance[ 'start' ] ) . '" /></td></tr>';

		// Stop field

		$field_id = $this -> get_field_id( 'stop' );
		$field_name = $this -> get_field_name( 'stop' );
		echo "\r\n" . '<tr><td width="100%">' . __( 'Stop (seconds)', 'youtube-embed' ) . '</td><td><input type="text" size="3" maxlength="3" id="' . $field_id . '" name="' . $field_name . '" value="' . esc_attr( $instance[ 'stop' ] ) . '" /></td></tr>';

		echo "</table><table>\n";

		// List field

		$field_id = $this -> get_field_id( 'list' );
		$field_name = $this -> get_field_name( 'list' );
		echo "\r\n" . '<tr><td width="100%">' . __( 'List Playback', 'youtube-embed' ) . '</td><td><select name="' . $field_name . '" id="' . $field_id . '"><option value=""';
		if ( esc_attr( $instance[ 'list' ] ) == '' ) { echo " selected='selected'"; }
		echo '>' . __( 'Profile default', 'youtube-embed' ) . '</option><option value="order"';
		if ( esc_attr( $instance[ 'list' ] ) == 'order' ) { echo " selected='selected'"; }
		echo '>' . __( 'Play each video in order', 'youtube-embed' ) . '</option><option value="random"';
		if ( esc_attr( $instance[ 'list' ] ) == 'random' ) { echo " selected='selected'"; }
		echo '>' . __( 'Play videos randomly', 'youtube-embed' ) . '</option><option value="single"';
		if ( esc_attr( $instance[ 'list' ] ) == 'single' ) { echo " selected='selected'"; }
		echo '>' . __( 'Play one random video', 'youtube-embed' ) . '</option></select></td></tr>';

		echo "</table>\n";
	}
}

/**
* Register Widget
*
* Register widget when loading the WP core
*
* @since	2.0
*/

function youtube_embed_register_widgets() {
	register_widget( 'YouTubeEmbedWidget' );
}
add_action( 'widgets_init', 'youtube_embed_register_widgets' );
?>
