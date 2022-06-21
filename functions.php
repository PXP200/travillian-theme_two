<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'blogsite-fontawesome-style','blogsite-genericons-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION

// unhook parent widget theme function
function remove_my_parent_theme_widget_function() {
    remove_action('widgets_init', 'blogsite_widgets_init');
}
add_action('wp_loaded', 'remove_my_parent_theme_widget_function');


// add parent theme function back with child directory files
function blogsite_widgets_init_child() {       
                                
    require trailingslashit( get_stylesheet_directory_uri() ) . 'inc/widgets/widget-recent.php';
    register_widget( 'BlogSite_Recent_Widget' );     

    require trailingslashit( get_stylesheet_directory_uri() ) . 'inc/widgets/widget-most-commented.php';
    register_widget( 'BlogSite_Most_Commented_Widget' );        

    require trailingslashit( get_stylesheet_directory_uri() ) . 'inc/widgets/widget-category-posts.php';
    register_widget( 'BlogSite_Category_Posts_Widget' );   

    require trailingslashit( get_stylesheet_directory_uri() ) . 'inc/widgets/widget-tabs.php';
    register_widget( 'BlogSite_Tabs_Widget' ); 
    
}

add_action( 'widgets_init_child', 'blogsite_widgets_init_child' );

// https://wordpress.stackexchange.com/questions/19326/how-to-unregister-a-widget-from-a-child-theme







 