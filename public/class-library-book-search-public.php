<?php
if( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.facebook.com/sahil.rizvi.902
 * @since      1.0.0
 *
 * @package    Library_Book_Search
 * @subpackage Library_Book_Search/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Library_Book_Search
 * @subpackage Library_Book_Search/public
 * @author     Mohd Jafar <jafar.mohd2@gmail.com>
 */
class Lbs_Search_Public {

	/**
	 * The ID of plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version current version of plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of the plugin.
	 * @param    string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register stylesheets for frontend.
	 *
	 * @since    1.0.0
	 */
	public function lbs_enqueue_styles() {
		$load_styles = false;
		global $post;
		if( is_single() && get_post_type( $post->ID ) == 'book' ) {
			$load_styles = true;
		}

		if( isset( $post ) && has_shortcode( $post->post_content, 'library_book_search' ) ) {
			$load_styles = true;
		}

		if( $load_styles ) {
			wp_enqueue_style( $this->plugin_name.'-selectize', LBS_PLUGIN_URL . 'public/css/selectize.css' );
			wp_enqueue_style( $this->plugin_name.'-ui', LBS_PLUGIN_URL . 'public/css/jquery-ui.min.css' );
			wp_enqueue_style( $this->plugin_name.'-data-tables', LBS_PLUGIN_URL . 'public/css/jquery.dataTables.min.css' );
			wp_enqueue_style( $this->plugin_name.'-font-awesome', LBS_PLUGIN_URL . 'admin/css/font-awesome.min.css' );
			wp_enqueue_style( $this->plugin_name, LBS_PLUGIN_URL . 'public/css/library-book-search-public.css' );
		}
	}

	/**
	 * Register JavaScript for frontend.
	 *
	 * @since    1.0.0
	 */
	public function lbs_enqueue_scripts() {
		$load_scripts = false;
		global $post;
		if( is_single() && get_post_type( $post->ID ) == 'book' ) {
			$load_scripts = true;
		}

		if( isset( $post ) && has_shortcode( $post->post_content, 'library_book_search' ) ) {
			$load_scripts = true;
		}

		if( $load_scripts ) {
			wp_enqueue_script( $this->plugin_name.'-selectize', LBS_PLUGIN_URL . 'public/js/selectize.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->plugin_name.'-ui', LBS_PLUGIN_URL . 'public/js/jquery-ui.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->plugin_name.'-data-tables', LBS_PLUGIN_URL . 'public/js/jquery.dataTables.min.js', array( 'jquery' ) );
			wp_enqueue_script( $this->plugin_name, LBS_PLUGIN_URL . 'public/js/library-book-search-public.js', array( 'jquery' ) );

			wp_localize_script(
				$this->plugin_name,
				'lbs_public_js_obj',
				array(
					'ajaxurl' => admin_url('admin-ajax.php'),
					'security' => wp_create_nonce( "lbs-security-nonce" )
				)
			);
		}
	}

	/**
	 * Set template for shortcode
	 */
	public function lbs_search_shortcode_template() {
		$shortcode_template = LBS_PLUGIN_PATH . 'public/templates/lbs-shortcode.php';
		if( file_exists( $shortcode_template ) ) {
			include $shortcode_template;
		}
	}

	/**
	 * Serve ajax request for searching books
	 */
	public function lbs_search_books() {
		
		if( check_ajax_referer( 'lbs-security-nonce', 'security' ) )
		{
			$book_name = sanitize_text_field( $_POST['book_name'] );
			$min_price = sanitize_text_field( $_POST['min_price'] );
			$max_price = sanitize_text_field( $_POST['max_price'] );
			$rating = sanitize_text_field( $_POST['rating'] );
			$author = sanitize_text_field( $_POST['author'] );
			$publisher = sanitize_text_field( $_POST['publisher'] );

			$args = array(
				'post_type' => 'book',
				'posts_per_page' => -1,
				'fields' => 'ids',
			);

			// Book Fields
			if( $book_name ){
				$args['s'] = $book_name;
			}

			//Rating field
			if( $rating ) {
				$args['meta_query']['relation'] = "AND";
				$args['meta_query'][] = array(
					'key'		=>	'book-rating',
					'value'		=>	$rating,
					'compare'	=>	'='
				);
			}

			//Price range field
			if( isset($min_price) && isset($max_price) ) {
				$args['meta_query'][] = array(
					'key'		=> 'book-price',
					'value'		=> array($min_price, $max_price),
					'type'    	=> 'numeric',
					'compare'	=> 'BETWEEN'
				);
			}

			$tax_query_count = 0;

			//Author field
			if( $author ) {
				$args['tax_query'][] = array(
					'taxonomy'			=>	'book-author',
					'terms'				=>	$author,
					'field'				=>	'name',
					'include_children'	=>	true,
				);
				$tax_query_count++;
			}

			//Publisher Field
			if( $publisher ) {
				$args['tax_query'][] = array(
					'taxonomy'			=>	'book-publisher',
					'terms'				=>	$publisher,
					'field'				=>	'id',
					'include_children'	=>	true,
				);
				$tax_query_count++;
			}

			if($tax_query_count == 2){
				$args['tax_query']['relation'] = "AND";
			}
			
			$book_ids = get_posts( $args );
			
			$response_html = '';
			if( empty( $book_ids ) ) {
				$message = __( 'Sorry, No Book Matches as per your selection!', LBS_TEXT_DOMAIN );
				$response_html .= '<div class="lbs-failure">';
				$response_html .= '<p>' . $message . '</p>';
				$response_html .= '</div>';
			} else {
				$message = __( 'Hello, Here is your Searched Books!', LBS_TEXT_DOMAIN );
				$response_html .= '<div class="lbs-success">';
				$response_html .= '<p>' . $message . '</p>';
				$response_html .= '</div>';
				$response_html .= '<table class="lbs-list-results">';
				$response_html .= '<thead>';
				$response_html .= '<tr>';
				$response_html .= '<th>'.__( 'No.', LBS_TEXT_DOMAIN ).'</th>';
				$response_html .= '<th>'.__( 'Book Name', LBS_TEXT_DOMAIN ).'</th>';
				$response_html .= '<th>'.__( 'Price', LBS_TEXT_DOMAIN ).'</th>';
				$response_html .= '<th>'.__( 'Author', LBS_TEXT_DOMAIN ).'</th>';
				$response_html .= '<th>'.__( 'Publisher', LBS_TEXT_DOMAIN ).'</th>';
				$response_html .= '<th>'.__( 'Rating', LBS_TEXT_DOMAIN ).'</th>';
				$response_html .= '</tr>';
				$response_html .= '</thead>';
				$response_html .= '<tbody>';
				foreach( $book_ids as $index => $bid ) {
					$book = get_post( $bid );
					$book_meta = get_post_meta( $bid );
					$authors = wp_get_object_terms( $bid, 'book-author' );
					$authors_str = '--';
					if( !empty( $authors ) ) {
						$authors_str = '';
						foreach( $authors as $author ) {
							$author_link = get_term_link( $author->term_id, 'book-author' );
							$authors_str .= '<a href="'.$author_link.'">'.$author->name.'</a>,';
						}
						$authors_str = rtrim( $authors_str, ',' );
					}

					$publishers = wp_get_object_terms( $bid, 'book-publisher' );
					$publishers_str = '--';
					if( !empty( $publishers ) ) {
						$publishers_str = '';
						foreach( $publishers as $publisher ){
							$publisher_link = get_term_link( $publisher->term_id, 'book-publisher' );
							$publishers_str .= '<a href="'.$publisher_link.'">'.$publisher->name.'</a>,';
						}
						$publishers_str = rtrim( $publishers_str, ',' );
					}

					$response_html .= '<tr>';
					$response_html .= '<td>' . ( $index + 1 ) . '.</td>';
					$response_html .= '<td><a target="_blank" href="'.get_permalink( $bid ).'" title="'.$book->post_title.'">'.$book->post_title.'</a></td>';
					$response_html .= '<td>&#36 '.$book_meta['book-price'][0].'</td>';
					$response_html .= '<td>'.$authors_str.'</td>';
					$response_html .= '<td>'.$publishers_str.'</td>';
					$response_html .= '<td>';
					$response_html .= '<div class="rating-stars">';
					$response_html .= '<ul>';
					for( $i = 1; $i <= 5; $i++ ) {
						$rating_class = '';
						if( $i <= $book_meta['book-rating'][0] ) {
							$rating_class = 'selected';
						}

						$response_html .= '<li class="star '.$rating_class.'"><i class="fa fa-star"></i></li>';
					}
					$response_html .= '</ul>';
					$response_html .= '</div>';
					$response_html .= '</td>';
					$response_html .= '</tr>';
				}
				$response_html .= '</tbody>';
				$response_html .= '</table>';
			}

			$result = array(
				'message'		=>	__( $message, LBS_TEXT_DOMAIN ),
				'html'			=>	$response_html,
				'books_count'	=>	count( $book_ids )
			);
			wp_send_json_success( $result );
			die;
		}
	}

	/**
	 * Set template for book details
	 */
	public function lbs_detail_page_template( $template ) {
		global $post;
		$book_details_template = LBS_PLUGIN_PATH . 'public/templates/lbs-book-details.php';
		if ( $post->post_type == 'book' && file_exists( $book_details_template ) ) {
			$template = $book_details_template;
		}
		return $template;
	}
}
?>