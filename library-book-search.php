<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.facebook.com/sahil.rizvi.902
 * @since             1.0.0
 * @package           Library_Book_Search
 *
 * @wordpress-plugin
 * Plugin Name:       Library Book Search
 * Plugin URI:        https://github.com/jafarmohd2/library-book-search
 * Description:       This plugin creates a search shortcode for custom post type Books.
 * Version:           1.0.0
 * Author:            Mohd Jafar
 * Author URI:        https://www.facebook.com/sahil.rizvi.902
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       library-book-search
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Plugin Version
define( 'LBS_PLUGIN_NAME_VERSION', '1.0.0' );

//Plugin Text Domain
if ( ! defined( 'LBS_TEXT_DOMAIN' ) ) {
	define( 'LBS_TEXT_DOMAIN', 'library-book-search' );
}
//Plugin URL
if ( ! defined( 'LBS_PLUGIN_URL' ) ) {
	define( 'LBS_PLUGIN_URL', plugin_dir_url(__FILE__) );
}
//Plugin Path
if ( ! defined( 'LBS_PLUGIN_PATH' ) ) {
	define( 'LBS_PLUGIN_PATH', plugin_dir_path(__FILE__) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-library-book-search-activator.php
 */
function activate_library_book_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-library-book-search-activator.php';
	Library_Book_Search_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-library-book-search-deactivator.php
 */
function deactivate_library_book_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-library-book-search-deactivator.php';
	Library_Book_Search_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_library_book_search' );
register_deactivation_hook( __FILE__, 'deactivate_library_book_search' );



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_library_book_search() {
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-library-book-search.php';

	$plugin = new Library_Book_Search();
	$plugin->run();

}

/**
 * Check plugin requirement or any other thing when plugins load
 */
add_action('plugins_loaded', 'lbs_plugin_init');
function lbs_plugin_init() {
	run_library_book_search();
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'lbs_plugin_links' );
}

/**
 * Plugin links on plugins listing page
 */
function lbs_plugin_links( $links ) {
	$lbs_links = array(
		'<a href="'.admin_url('edit.php?post_type=book&page=book-search-shortcode').'">'.__( 'Shortcode', LBS_TEXT_DOMAIN ).'</a>'
	);
	return array_merge( $links, $lbs_links );
}
?>