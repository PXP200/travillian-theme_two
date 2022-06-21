<?php
/**
 * Recent Posts with Thumbnail widget.
 *
 * @package    blogsite
 * @author     WPEnjoy
 * @copyright  Copyright (c) 2021, WPEnjoy
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */
class BlogSite_Recent_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'   => 'widget-blogsite-recent widget-posts-thumbnail',
			'description' => __( 'Display recent posts with thumbnails.', 'blogsite-pro' )
		);

		// Create the widget.
		parent::__construct(
			'blogsite-recent', // $this->id_base
			__( '&raquo; Recent Posts', 'blogsite-pro' ), // $this->name
			$widget_options // $this->widget_options
		);

		// Flush the transient.
		add_action( 'save_post'   , array( $this, 'flush_widget_transient' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_transient' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_transient' ) );

	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 1.0.0
	 */
	function widget( $args, $instance ) {

		// Default value.
		$defaults = array(
			'title' => '',
			'limit' => 6,
			'show_number' => true
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		extract( $args );

		// Output the theme's $before_widget wrapper.
		echo wp_kses_post( $before_widget );

		// If the title not empty, display it.
		if ( $instance['title'] ) {
			echo wp_kses_post( $before_title ) . wp_kses_post( apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) . wp_kses_post( $after_title );
		}

		// Display the recent posts.
		if ( false === ( $recent = get_transient( 'blogsite_recent_widget_' . $this->id ) ) ) {

			// Posts query arguments.
			$args = array(
				'post_type'      => 'post',
				'posts_per_page' => $instance['limit'],
			);

			// The post query
			$recent = new WP_Query( $args );

			// Store the transient.
			set_transient( 'blogsite_recent_widget_' . $this->id, $recent );

		}

		global $post;
		
		$i = 1;

		if ( $recent->have_posts() ) {
			echo '<ul>';

				while ( $recent->have_posts() ) : $recent->the_post();

					if ( ($i < 2) && has_post_thumbnail() ){

					echo '<li class="clear">';

						echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
						echo '<div class="thumbnail-wrap">';
							the_post_thumbnail('blogsite_widget_thumb');  
						echo '</div>';
							
						echo '<div class="entry-wrap">';
						 the_title();

					echo '</div><div class="gradient"></div></a></li>';

					} else {

						if ( $instance['show_number'] ) {
							echo '<li class="post-list"><span>' . ($i - 1) . '</span>';
						} else {
							echo '<li class="post-list no-number">';
						}						

						echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
							the_title();
						echo '</a></li>';

					}

					$i++;

				endwhile;

			echo '</ul>';
		}

		// Reset the query.
		wp_reset_postdata();

		// Close the theme's widget wrapper.
		echo wp_kses_post( $after_widget );

	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 1.0.0
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $new_instance;
		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['limit']     = (int) $new_instance['limit'];
		$instance['show_number'] = isset( $new_instance['show_number'] ) ? (bool) $new_instance['show_number'] : false;

		// Delete our transient.
		$this->flush_widget_transient();

		return $instance;
	}

	/**
	 * Flush the transient.
	 *
	 * @since  1.0.0
	 */
	function flush_widget_transient() {
		delete_transient( 'blogsite_recent_widget_' . $this->id );
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 1.0.0
	 */
	function form( $instance ) {

		// Default value.
		$defaults = array(
			'title'     => esc_html__( 'Recent Posts', 'blogsite-pro' ),
			'limit'     => 6,
			'show_number' => true
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
	?>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title', 'blogsite-pro' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'limit' ) ); ?>">
				<?php esc_html_e( 'Number of posts to show', 'blogsite-pro' ); ?>
			</label>
			<input class="small-text" id="<?php echo esc_html( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'limit' ) ); ?>" type="number" step="1" min="0" value="<?php echo (int)( $instance['limit'] ); ?>" />
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_number'] ); ?> id="<?php echo esc_html( $this->get_field_id( 'show_number' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'show_number' ) ); ?>" />
			<label for="<?php echo esc_html( $this->get_field_id( 'show_number' ) ); ?>">
				<?php esc_html_e( 'Display number before post title?', 'blogsite-pro' ); ?>
			</label>
		</p>

	<?php

	}

}
