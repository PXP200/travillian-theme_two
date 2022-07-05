<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package blogsite-pro
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="HandheldFriendly" content="true">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
<style type="text/css">
	/* Primary Color */
	button,
	.btn,
	input[type="submit"],
	input[type="reset"],
	input[type="button"],
	#back-top a span,
	.widget_tag_cloud .tagcloud a:hover,
	.sf-menu li a:hover .menu-text,
	.sf-menu li.current-menu-item a .menu-text,
	.sf-menu li.current-menu-item a:hover .menu-text,
	.pagination .page-numbers:hover,
    .pagination .prev:hover,
    .pagination .next:hover,
    .entry-tags .tag-links a:hover,
    .widget_tag_cloud .tagcloud a:hover,
    .sidebar .widget .widget-title a:hover,
    .header-search .search-submit,
    .content-loop .entry-category a,
    .pagination .page-numbers:hover,
    .pagination .page-numbers.current:hover,
    .entry-tags .tag-links a:hover,
    .sidebar .widget-posts-thumbnail ul > li.post-list:nth-of-type(4) span,
    .widget_tag_cloud .tagcloud a:hover,
    .site-footer .widget_tabs .tab-content .tag-cloud-link:hover,
	.sidebar .widget_tabs .tab-content .tag-cloud-link:hover, 
	.site-footer .widget_tabs .tab-content .tag-cloud-link:hover {
		background-color: <?php echo blogsite_option('primary-color'); ?>;
	}	
    a, 
    .site-title a, 
    .widget ul li a:hover,
    .widget-title a:hover,
	.site-title a,
	a:hover,
	.site-header .search-icon:hover span,
	.breadcrumbs .breadcrumbs-nav a:hover,
	.entry-title a:hover,
	article.hentry .edit-link a,
	.author-box a,
	.page-content a,
	.entry-content a,
	.comment-author a,
	.comment-content a,
	.comment-reply-title small a:hover,
	.sidebar .widget a,
	.sidebar .widget ul li a:hover,
	.sidebar .widget ol li a:hover,
	#post-nav a:hover h4,    
  	.single #primary .entry-header .entry-category a,
    .pagination .page-numbers.current,
    #site-bottom .site-info a:hover,
    #site-bottom .footer-nav li a:hover,
    .site-header .search-icon:hover .fa,
    .site-footer .widget a,
    .site-footer .widget .widget-title a:hover,
    .site-footer .widget ul > li a:hover,
    .site-footer .widget_tabs ul.horizontal li.active a,
    .site-footer .widget_tabs ul.horizontal li.active .fa,
	.content-loop .entry-meta .entry-category a, 
	.single #primary .entry-header .entry-meta .entry-category a,
	.sidebar .widget_tabs ul.horizontal li.active a, 
	.site-footer .widget_tabs ul.horizontal li.active a,
	.sidebar .widget_tabs ul.horizontal li.active .fa, 
	.site-footer .widget_tabs ul.horizontal li.active .fa {
    	color: <?php echo blogsite_option('primary-color'); ?>;
    }
    .sf-menu li li a:hover .menu-text,
    .sf-arrows ul li a.sf-with-ul:hover:after {
    	color: <?php echo blogsite_option('primary-color'); ?> !important;
    }

	.sidebar .widget-title a:hover,
	.author-box a:hover,
	.page-content a:hover,
	.entry-content a:hover,
	.widget_tag_cloud .tagcloud a:hover:before,
	.entry-tags .tag-links a:hover:before,
	article.hentry .edit-link a:hover,
	.comment-content a:hover,
	.single #primary .entry-header .entry-meta .entry-category a:hover {
		color: <?php echo blogsite_option('secondary-color'); ?> 
	}

	#back-top a:hover span,
	.sidebar .widget ul li:before,
	.sidebar .wp-block-search .wp-block-search__button {
		background-color: <?php echo blogsite_option('secondary-color'); ?>
	}

    .header-search {
    	border-color: <?php echo blogsite_option('primary-color'); ?>;
    }
    <?php if ( blogsite_option('hide-sidebar-on',false) == true): ?>
	    @media only screen and (max-width: 479px) {
	    	#secondary {
	    		display: none !important;
	    	}	
	    }
	<?php endif; ?>

	@media only screen and (min-width: 1180px) {
		.content-loop .thumbnail-link {
			width: <?php  echo blogsite_option('loop-featured-width','230') . 'px'; ?>;
		}
	}
</style>
<?php echo blogsite_option('header-code'); ?>
</head>

<body <?php body_class(); ?>>

<?php
	//wp_body_open hook from WordPress 5.2
	if ( function_exists( 'wp_body_open' ) ) {
	    wp_body_open();
	} else { 
	    do_action( 'wp_body_open' ); 
	}
?>

<div id="page" class="site">

	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'blogsite-pro' ); ?></a>

	<header id="masthead" class="site-header clear">

		<?php
			the_custom_header_markup();
		?>

		<div class="container">

			<div class="site-branding">

				<?php if ( has_custom_logo() ) { ?>

					<div id="logo">
						<?php the_custom_logo(); ?>
					</div><!-- #logo -->

				<?php } ?>

				<?php if (display_header_text()==true) { ?>

					<div class="site-title-desc">

						<div class="site-title <?php if (empty(get_bloginfo('description'))) { echo 'no-desc'; } ?>">
							<h1><a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo('name'); ?></a></h1>
						</div><!-- .site-title -->	

						<div class="site-description">
							<?php bloginfo('description'); ?>
						</div><!-- .site-desc -->

					</div><!-- .site-title-desc -->

				<?php } ?>

			</div><!-- .site-branding -->		

			<nav id="primary-nav" class="primary-navigation">

				<?php 
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'sf-menu', 'link_before' => '<span class="menu-text">','link_after'=>'</span>' ) );
					}
				?>

			</nav><!-- #primary-nav -->

			<?php if ( blogsite_option('header-search-on', true) == true ) : ?> 
				<div class="header-search">
					<form id="searchform" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="search" name="s" class="search-input" placeholder="<?php esc_attr_e('Search', 'blogsite-pro'); ?>" autocomplete="off">
						<button type="submit" class="search-submit"><span class="genericon genericon-search"></span></button>		
					</form>
				</div><!-- .header-search -->
			<?php endif; ?>

			<div class="header-toggles <?php if ( blogsite_option('header-search-on', true) == true ) { echo 'has-search'; } ?> ">
				<button class="toggle nav-toggle mobile-nav-toggle" data-toggle-target=".menu-modal"  data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
					<span class="toggle-inner">
						<span class="toggle-icon">
							<?php blogsite_the_theme_svg( 'ellipsis' ); ?>
						</span>
						<span class="toggle-text"><?php esc_html_e( 'Menu', 'blogsite-pro' ); ?></span>
					</span>
				</button><!-- .nav-toggle -->
			</div><!-- .header-toggles -->

			<?php if ( blogsite_option('header-search-on', true) == true ) : ?> 
				<div class="header-search-icon">
					<span class="search-icon">
						<i class="fa fa-search"></i>
						<i class="fa fa-close"></i>			
					</span>
				</div>
			<?php endif; ?>
						
		</div><!-- .container -->

	</header><!-- #masthead -->	

	<div class="menu-modal cover-modal header-footer-group" data-modal-target-string=".menu-modal">

		<div class="menu-modal-inner modal-inner">

			<div class="menu-wrapper section-inner">

				<div class="menu-top">

					<button class="toggle close-nav-toggle fill-children-current-color" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".menu-modal">
						<span class="toggle-text"><?php esc_html_e( 'Close Menu', 'blogsite-pro' ); ?></span>
						<?php blogsite_the_theme_svg( 'cross' ); ?>
					</button><!-- .nav-toggle -->

					<?php

					$mobile_menu_location = '';

					// If the mobile menu location is not set, use the primary location as fallbacks, in that order.
					if ( has_nav_menu( 'mobile' ) ) {
						$mobile_menu_location = 'mobile';
					} elseif ( has_nav_menu( 'primary' ) ) {
						$mobile_menu_location = 'primary';
					}

					?>

					<nav class="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile', 'blogsite-pro' ); ?>" role="navigation">

						<ul class="modal-menu reset-list-style">

						<?php
						if ( $mobile_menu_location ) {

							wp_nav_menu(
								array(
									'container'      => '',
									'items_wrap'     => '%3$s',
									'show_toggles'   => true,
									'theme_location' => $mobile_menu_location,
								)
							);

						} else {

							wp_list_pages(
								array(
									'match_menu_classes' => true,
									'show_toggles'       => true,
									'title_li'           => false,
									'walker'             => new BlogSite_Walker_Page(),
								)
							);

						}
						?>

						</ul>

					</nav>

				</div><!-- .menu-top -->

			</div><!-- .menu-wrapper -->

		</div><!-- .menu-modal-inner -->

	</div><!-- .menu-modal -->	

<div class="header-space"></div>

<div id="content" class="site-content container <?php if( (!is_active_sidebar( 'home-sidebar' )) && (!is_active_sidebar( 'sidebar-1' )) ) { echo 'is_full_width'; } ?> clear">
