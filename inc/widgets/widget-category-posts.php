<?php
/**
 * Category Posts widget.
 *
 * @package    blogsite
 * @author     WPEnjoy
 * @copyright  Copyright (c) 2021, WPEnjoy
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */
class BlogSite_Category_Posts_widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'   => 'widget-blogsite-category-posts widget-posts-thumbnail',
			'description' => __( 'Display posts in selected category', 'blogsite-pro' )
		);

		// Create the widget.
		parent::__construct(
			'blogsite-category-posts',          // $this->id_base
			__( '&raquo; Category Posts', 'blogsite-pro' ), // $this->name
			$widget_options                    // $this->widget_options
		);
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

		// Theme prefix
		$prefix = 'blogsite-';

		// Pull the selected category.
		$cat_id = isset( $instance['cat'] ) ? absint( $instance['cat'] ) : 0;

		// Get the category.
		$category = get_category( $cat_id );

		// Get the category archive link.
		$cat_link = get_category_link( $cat_id );

		// Limit to category based on user selected tag.
		if ( ! $cat_id == 0 ) {
			$args['cat'] = $cat_id;
		}

 ?>

			<?php
				if ( ( ! empty( $instance['title'] ) ) && ( $cat_id != 0 ) ) {
					echo ( '<h3 class="widget-title"><a target="_blank" href="' . esc_url( $cat_link ) . '">' . esc_html( $instance['title'] ). '</a></h3>' );
				} elseif ( $cat_id == 0 ) {
					echo ( '<h3 class="widget-title"><span>' . esc_html__( 'Recent Posts', 'blogsite-pro' ) . '</span></h3>' );
				} else {
					echo ( '<h3 class="widget-title"><a href="' . esc_url( $cat_link ) . '">' . esc_attr( $category->name ) . '</a></h3>' );
				}
			?>

			<?php

				// Define custom query args
				$args = array( 
					'post_type'      => 'post',
					'ignore_sticky_posts' => 1,
					'post__not_in' => get_option( 'sticky_posts' ),						
					'posts_per_page' => $instance['limit'],
					'cat' => $cat_id
				);  

				// The post query
				$wp_query = new WP_Query( $args );

				// Store the transient.
				set_transient( 'blogsite_category_posts_widget_' . $this->id, $wp_query );

				$i = 1;

				if ( $wp_query->have_posts() ) :

				echo '<ul>';

				while ( $wp_query->have_posts() ) : $wp_query->the_post(); 

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
							echo '<li class="post-list post-list-'. ($i - 1) .'"><span>' . ($i - 1) . '</span>';
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

			?>

			<?php 
			endif;
			wp_reset_query();
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

		$instance['title'] = $new_instance['title'];
		$instance['limit'] = (int) $new_instance['limit'];
		$instance['cat']   = (int) $new_instance['cat'];
		$instance['show_number'] = isset( $new_instance['show_number'] ) ? (bool) $new_instance['show_number'] : false;		
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 1.0.0
	 */
	function form( $instance ) {

		// Default value.
		$defaults = array(
			'title' => '',
			'limit' => 6,
			'cat'   => '',
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
			<label for="<?php echo esc_html( $this->get_field_id( 'cat' ) ); ?>"><?php esc_html_e( 'Select a category', 'blogsite-pro' ); ?></label>
			<select class="widefat" id="<?php echo esc_html( $this->get_field_id( 'cat' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'cat' ) ); ?>" style="width:100%;">
				<?php $categories = get_terms( 'category' ); ?>
				<option value="0"><?php esc_html_e( 'All categories &hellip;', 'blogsite-pro' ); ?></option>
				<?php foreach( $categories as $category ) { ?>
					<option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $instance['cat'], $category->term_id ); ?>><?php echo esc_html( $category->name ); ?></option>
				<?php } ?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'limit' ) ); ?>">
				<?php esc_html_e( 'Number of posts to show', 'blogsite-pro' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'limit' ) ); ?>" type="number" step="1" min="0" value="<?php echo esc_html( (int)( $instance['limit'] ) ); ?>" />
		</p>	

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_number'] ); ?> id="<?php echo esc_html( $this->get_field_id( 'show_number' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'show_number' ) ); ?>" />
			<label for="<?php echo esc_html( $this->get_field_id( 'show_number' )); ?>">
				<?php esc_html_e( 'Display number before post title?', 'blogsite-pro' ); ?>
			</label>
		</p>										

	<?php

	}

}