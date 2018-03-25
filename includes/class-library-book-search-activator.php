<?php
if( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly
/**
 * Fired during plugin activation
 *
 * @link       https://www.facebook.com/sahil.rizvi.902
 * @since      1.0.0
 *
 * @package    Library_Book_Search
 * @subpackage Library_Book_Search/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Library_Book_Search
 * @subpackage Library_Book_Search/includes
 * @author     Mohd Jafar <jafar.mohd2@gmail.com>
 */
class Library_Book_Search_Activator {

	/**
	 * Create a Page for Book Search on Activation
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$page_title = "Book Search";
		if( get_page_by_title( $page_title ) == NULL ) {
			wp_insert_post( array(
				'post_type' => 'page',
				'post_status' => 'publish',
				'post_title' => $page_title,
				'post_content' => '[library_book_search]'
			) );
		}
	}
}
?>