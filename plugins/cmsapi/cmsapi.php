<?php
/**
* Plugin Name: CMSAPI
* Plugin URI: https://github.com/api4cms/cmsapi-wp
* Description: Switch CMS Mode for API Theme
* Version: 0.0.1
* Author: Jerzy Wawro
* Author URI: http://tenar.pl
* License: LGPL
*
* @package Cmsapi
* @category Core
*/
//namespace Galicea\Plugins\Cmsapi;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'CMSAPI_VERSION', '0.0.1' );
define( 'CMSAPI__FILE__', __FILE__ );
define( 'CMSAPI_PLUGIN_BASE', plugin_basename( CMSAPI__FILE__ ) );
define( 'CMSAPI_PATH', plugin_dir_path( CMSAPI__FILE__ ) );

/// options
function get_cmsapi_options() {
    $default = array(
        'cmsapi_mode' => 'html',
        'cmsapi_notes' => ''
        );
    return get_option('cmsapi_plg_options', $default);
}


//https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
/*
 *  plugins page: settings
 */
// Add a menu for our option page
add_action('admin_menu', 
   function () {
	add_menu_page(
		__( 'CMSAPI - Options', 'cmsapi' ),
		__( 'CMSAPI - Options', 'cmsapi' ),
		'manage_options',
	 'cmsapi_setting', 
	 'cmsapi_plg_option_page' );
  }
);

// Draw the option page
function cmsapi_plg_option_page() {
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Theme options</h2>
        <form action="options.php" method="post">
            <?php settings_fields('cmsapi_options'); ?>
            <?php do_settings_sections('cmsapi_setting'); ?>
            <input name="Submit" type="submit" value="Save Changes" />
        </form>
    </div>
    <?php
}

// The settings define and register
add_action('admin_init', 
   function (){
	// https://developer.wordpress.org/reference/functions/register_setting/
    register_setting(
        'cmsapi_options',
        'cmsapi_plg_options',
        'cmsapi_validate_options'
    );
    add_settings_section(
        'cmsapi_render_mode',
        'Rendering Mode',
        // 'cmsapi_section_text',
        '__return_false',
        'cmsapi_setting'
    );
    add_settings_field(
        'cmsapi_mode',
        'HTML / OpenAPI / JSON:',
        'cmsapi_mode_setting',
        'cmsapi_setting',
        'cmsapi_render_mode'
    );
    add_settings_field(
        'cmsapi_notes',
        'Notes:',
        'cmsapi_notes_setting',
        'cmsapi_setting',
        'cmsapi_render_mode'
    );
});

register_setting(
    'cmsapi_options',
    'cmsapi_plg_options',
    'cmsapi_validate_options'
);

function cmsapi_mode_setting() {
    $options = get_cmsapi_options('cmsapi_plg_options');
    $items = array("html", "OpenAPI", "json");
    echo "<select id='cmsapi_mode' name='cmsapi_plg_options[cmsapi_mode]'>";
    foreach($items as $item) {
        $selected = ($options['cmsapi_mode']==$item) ? 'selected="selected"' : '';
        echo "<option value='$item' $selected>$item</option>";
    }
    echo "</select>";
}

function cmsapi_notes_setting() {
    $options = get_cmsapi_options('cmsapi_plg_options');
    echo "<textarea id='cmsapi_notes' name='cmsapi_plg_options[cmsapi_notes]' rows='7' cols='50' type='textarea'>{$options['cmsapi_notes']}</textarea>";
}


add_filter('the_content', 
  function ($content) {
    $mode = get_option('cmsapi_mode');
//    $notes = get_option('cmsapi_notes');
    if( is_single() && is_main_query() ) {
        $content = explode("</p>", $content);
        for ($i = 0; $i <count($content); $i++ ) {
            if ($i == $mode)   
            echo $content[$i] . "</p>";
        }
    }   
    return $content;
 }
);


//////////////
/*
 * Links to display on the plugins page (beside the activate/deactivate links).
 *
 */

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 
   function ( $links ) {
     $link = sprintf( "<a href='%s' >%s</a>", 
				 admin_url( 'admin.php?page=cmsapi_setting' ), 
				 __( 'Settings', 'cmsapi' ) );
	array_push( $links, $link );
	return $links;
} );


////////////////////////// Plugin Conditions
/**
 * Load Cmsapi textdomain.
 *
 * Load gettext translate for Cmsapi text domain.
 * https://developer.wordpress.org/reference/functions/load_plugin_textdomain/
 *
 * @since 1.0.0
 *
 * @return void
 */
function Cmsapi_load_plugin_textdomain() {
	load_plugin_textdomain( 'cmsapi' );
}

/**
 * Cmsapi admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function Cmsapi_fail_php_version() {
	/* translators: %s: PHP version */
	$message = sprintf( esc_html__( 'Cmsapi requires PHP version %s+, plugin is currently NOT RUNNING.', 'cmsapi' ), '5.6' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Cmsapi admin notice for minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @since 1.5.0
 *
 * @return void
 */
function Cmsapi_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message = sprintf( esc_html__( 'Cmsapi requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'cmsapi' ), '5.0' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}
