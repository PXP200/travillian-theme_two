<?php		
	if (!get_query_var('paged')) {	
?>

<div id="featured-content" class="clear">

<?php		

	$custom_query_args = array( 
	    'posts_per_page' => blogsite_option('featured-slide-num','2'),
		'ignore_sticky_posts' => 1,
		'post__not_in' => get_option( 'sticky_posts' ),	
		'meta_query' => array(
	        array(
	        'key' => 'blogsite-featured',
	        'value' => 'yes'
        )
    )
	);  

	global $wp_query;

	$merged_query_args = array_merge( $wp_query->query, $custom_query_args );

	query_posts( $merged_query_args );		

	if ( $wp_query->have_posts() && (!get_query_var('paged')) ) {	

?>

	<div class="featured-left">

	<ul class="bxslider">	

	<?php
		// The Loop
		while ( $wp_query->have_posts() ) : $wp_query->the_post();
	?>	

	<li class="featured-slide hentry">

		<a class="thumbnail-link" href="<?php the_permalink(); ?>">
			
			<div class="thumbnail-wrap">
				<?php 
				if ( has_post_thumbnail() ) {
					the_post_thumbnail('blogsite_featured_large_thumb');  
				} else {
					echo '<img src="' . esc_url( get_template_directory_uri() ). '/assets/img/featured-large.png" alt="" />';
				}
				?>
			</div><!-- .thumbnail-wrap -->
			<div class="gradient">
			</div>
		</a>

		<div class="entry-header clear">		
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</div><!-- .entry-header -->

		<div class="entry-category">
			<?php blogsite_first_category(); ?>
		</div>	
			
	</li><!-- .featured-slide .hentry -->

	<?php
		endwhile;
	?>

	</ul><!-- .bxslider -->

	</div><!-- .featured-left -->

	<?php 
		} 
	?>

	<?php
		wp_reset_postdata();	
		wp_reset_query();			
	?>

	<?php
		$custom_query_args = array( 
		    'posts_per_page' => 2,
			'ignore_sticky_posts' => 1,
			'offset' => blogsite_option('featured-slide-num','2'),
			'post__not_in' => get_option( 'sticky_posts' ),	
			'meta_query' => array(
                array(
                'key' => 'blogsite-featured',
                'value' => 'yes'
	        )
	    )
		);  

		global $wp_query;

		$merged_query_args = array_merge( $wp_query->query, $custom_query_args );

		query_posts( $merged_query_args );		

		if ( $wp_query->have_posts() && (!get_query_var('paged')) ) {	
	?>
	
	<div class="featured-right">

	<?php
		while ( $wp_query->have_posts() ) : $wp_query->the_post();
	?>		

	<div class="featured-grid">

		<a class="thumbnail-link" href="<?php the_permalink(); ?>">
			
			<div class="thumbnail-wrap">
				<?php 
				if ( has_post_thumbnail() ) {
					the_post_thumbnail('blogsite_featured_small_thumb');  
				} else {
					echo '<img src="' . esc_url( get_template_directory_uri() ). '/assets/img/featured-small.png" alt="" />';
				}
				?>
			</div><!-- .thumbnail-wrap -->

			<div class="entry-header clear">	
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</div><!-- .entry-header -->

			<div class="gradient">
			</div>
		</a>

	</div><!-- .featured-grid -->

	<?php
		endwhile;
	?>

	</div><!-- .featured-right -->

	<?php
		}
	?>

	<?php
		wp_reset_postdata();	
		wp_reset_query();			
	?>
		
	</div><!-- #featured-content -->

	<?php
		if ( blogsite_option('featured-ad-on',true) && blogsite_option('featured-ad-code') ) {
			echo '<div class="custom-ad featured-ad">' . blogsite_option('featured-ad-code') . '</div>';
		}
	?>
	
<?php } ?>		
	