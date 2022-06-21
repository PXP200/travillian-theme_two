<?php
/**
 * Tabbed widget.
 *
 * @package    blogsite
 * @author     WPEnjoy
 * @copyright  Copyright (c) 2022, WPEnjoy
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */
class blogsite_Tabs_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'   => 'widget-blogsite-tabs widget_tabs posts-thumbnail-widget',
			'description' => __( 'Display popular & recent posts, comments and tags.', 'blogsite-pro' )
		);

		// Create the widget.
		parent::__construct(
			'blogsite-tabs',                  // $this->id_base
			__( '&raquo; Tabs', 'blogsite-pro' ), // $this->name
			$widget_options                    // $this->widget_options
		);
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 1.0.0
	 */
	function widget( $args, $instance ) {
		extract( $args );

		// Output the theme's $before_widget wrapper.
		echo wp_kses_post( $before_widget );
		?>
		<div class='tabs tabs_default'>
		<ul class="horizontal">
			<li class="active"><a href="#tab1" title="<?php esc_attr_e( 'Popular', 'blogsite-pro' ); ?>"><i class="fa fa-star"></i> <?php esc_html_e( 'Popular', 'blogsite-pro' ); ?></a></li>
			<li><a href="#tab2" title="<?php esc_attr_e( 'Latest', 'blogsite-pro' ); ?>"><i class="fa fa-clock-o"></i> <?php esc_html_e( 'Latest', 'blogsite-pro' ); ?></a></li>
			<li><a href="#tab3" title="<?php esc_attr_e( 'Comments', 'blogsite-pro' ); ?>"><i class="fa fa-comments"></i> <?php esc_html_e( 'Comments', 'blogsite-pro' ); ?></a></li>        
			<li><a href="#tab4" title="<?php esc_attr_e( 'Tags', 'blogsite-pro' ); ?>"><i class="fa fa-tag"></i> <?php esc_html_e( 'Tags', 'blogsite-pro' ); ?></a></li>
		</ul>

			<div id='tab1' class="tab-content widget-posts-thumbnail">
				<?php echo do_shortcode('[gravityform id="1" title="true"]'); ?>
			</div>

			<div id='tab2' class="tab-content widget-posts-thumbnail">
				<?php
					// Posts query arguments.
					$args = array(
						'posts_per_page' => $instance['recent_num'],
						'post_type'      => 'post',
						'post__not_in' => get_option( 'sticky_posts' ),
					);

					// The post query

					$i = 1;

					$recent = new WP_Query( $args );

					if ( $recent ) {
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
					}

					// Reset the query.
					wp_reset_postdata();				
				?>
			</div>

			<div id='tab3' class="tab-content">
				<?php $comments = get_comments( array( 'number' => (int) $instance['comments_num'], 'status' => 'approve', 'post_status' => 'publish' ) ); ?>
				<?php if ( $comments ) : ?>
					<ul class="tab-comments">
						<?php foreach( $comments as $comment ) : ?>
							<li class="clear">
								<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php echo get_avatar( $comment->comment_author_email, '64' ); ?></a>
								<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><strong><?php echo esc_html( $comment->comment_author ); ?></strong><span><?php echo esc_html( wp_trim_words( $comment->comment_content, '10' ) ); ?></span></a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div id='tab4' class="tab-content">
				<div class="tags-wrap clear">
					<?php wp_tag_cloud('number=30&orderby=count&order=DESC'); ?>
				</div>
			</div>

		</div><!-- .tabs -->

		<?php
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

		$instance['popular_num']  = absint( $new_instance['popular_num'] );
		$instance['recent_num']   = absint( $new_instance['recent_num'] );
		$instance['comments_num'] = absint( $new_instance['comments_num'] );
		$instance['day_limit']     = (int) $new_instance['day_limit'];		
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
			'popular_num'  => 6,
			'recent_num'   => 6,
			'comments_num' => 6,
			'day_limit' => 10000,			
			'show_number' => true
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
	?>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'popular_num' ) ); ?>">
				<?php esc_html_e( 'Number of popular posts to show', 'blogsite-pro' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo esc_html( $this->get_field_id( 'popular_num' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'popular_num' ) ); ?>" value="<?php echo absint( $instance['popular_num'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'recent_num' ) ); ?>">
				<?php esc_html_e( 'Number of recent posts to show', 'blogsite-pro' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo esc_html( $this->get_field_id( 'recent_num' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'recent_num' ) ); ?>" value="<?php echo absint( $instance['recent_num'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'comments_num' ) ); ?>">
				<?php esc_html_e( 'Number of recent comments to show', 'blogsite-pro' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo esc_html( $this->get_field_id( 'comments_num' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'comments_num' ) ); ?>" value="<?php echo esc_attr( $instance['comments_num'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'day_limit' ); ?>">
				<?php _e( 'Show posts published within xx days', 'blogsite-pro' ); ?>
			</label>
			<input class="small-text" id="<?php echo $this->get_field_id( 'day_limit' ); ?>" name="<?php echo $this->get_field_name( 'day_limit' ); ?>" type="number" step="1" min="0" value="<?php echo (int)( $instance['day_limit'] ); ?>" />
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

/**
 * Popular Posts by comment
 *
 * @since 1.0.0
 */
function blogsite_popular_posts( $number = 6 ) {

	// Posts query arguments.
	$args = array(
		'posts_per_page' => $number,
		'orderby'        => 'comment_count',
		'post_type'      => 'post'
	);

	$i = 1;

	$popular = new WP_Query( $args );

	if ( $popular ) {
		echo '<ul>';

			while ( $popular->have_posts() ) : $popular->the_post();
				//setup_postdata( $post );

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

}

/**
 * Recent Posts
 *
 * @since 1.0.0
 */
function blogsite_latest_posts( $number = 6 ) {

	// Posts query arguments.
	$args = array(
		'posts_per_page' => $number,
		'post_type'      => 'post',
		'post__not_in' => get_option( 'sticky_posts' ),
	);

	// The post query

	$i = 1;

	$recent = new WP_Query( $args );

	if ( $recent ) {
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


}