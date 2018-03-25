<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.facebook.com/sahil.rizvi.902
 * @since      1.0.0
 *
 * @package    Library_Book_Search
 * @subpackage Library_Book_Search/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Library_Book_Search
 * @subpackage Library_Book_Search/admin
 * @author     Mohd Jafar <jafar.mohd2@gmail.com>
 */
class Library_Book_Search_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function lbs_enqueue_styles() {
		$load_styles = false;
		if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'book' ) {
			$load_styles = true;
		} elseif( isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == 'book' ) {
			$load_styles = true;
		}

		if( $load_styles ) {
			wp_enqueue_style( $this->plugin_name.'-font-awesome', LBS_PLUGIN_URL . 'admin/css/font-awesome.min.css' );
			wp_enqueue_style( $this->plugin_name, LBS_PLUGIN_URL . 'admin/css/library-book-search-admin.css' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function lbs_enqueue_scripts() {
		$load_scripts = false;
		if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'book' ) {
			$load_scripts = true;
		} elseif( isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == 'book' ) {
			$load_scripts = true;
		}

		if( $load_scripts ) {
			wp_enqueue_script( $this->plugin_name, LBS_PLUGIN_URL . 'admin/js/library-book-search-admin.js', array( 'jquery' ) );
		}
	}

	/**
	 * Register A CPT - Books
	 */
	public function lbs_register_books_cpt() {
		$labels = array(
			'name'					=>	__( 'Books', LBS_TEXT_DOMAIN ),
			'singular_name'			=>	__( 'Book', LBS_TEXT_DOMAIN ),
			'menu_name'				=>	__( 'Books', 'admin menu', LBS_TEXT_DOMAIN ),
			'name_admin_bar'		=>	__( 'Book', 'add new on admin bar', LBS_TEXT_DOMAIN ),
			'add_new'				=>	__( 'Add New', LBS_TEXT_DOMAIN ),
			'add_new_item'			=>	__( 'Add New Book', LBS_TEXT_DOMAIN ),
			'new_item'				=>	__( 'New Book', LBS_TEXT_DOMAIN ),
			'edit_item'				=>	__( 'Edit Book', LBS_TEXT_DOMAIN ),
			'view_item'				=>	__( 'View Book', LBS_TEXT_DOMAIN ),
			'all_items'				=>	__( 'All Books', LBS_TEXT_DOMAIN ),
			'search_items'			=>	__( 'Search Books', LBS_TEXT_DOMAIN ),
			'parent_item_colon'		=>	__( 'Parent Books:', LBS_TEXT_DOMAIN ),
			'not_found'				=>	__( 'No Books Found.', LBS_TEXT_DOMAIN ),
			'not_found_in_trash'	=>	__( 'No Books Found In Trash.', LBS_TEXT_DOMAIN )
		);

		$args = array(
			'labels'				=>	$labels,
			'public'				=>	true,
			'menu_icon'				=>	'dashicons-book',
			'publicly_queryable'	=>	true,
			'show_ui'				=>	true,
			'show_in_menu'			=>	true,
			'query_var'				=>	true,
			'rewrite'				=>	array( 'slug' => 'book' ),
			'capability_type'		=>	'post',
			'has_archive'			=>	true,
			'hierarchical'			=>	false,
			'menu_position'			=>	null,
			'supports'				=>	array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);
		register_post_type( 'book', $args );
	}

	/**
	 * Register Taxonomies for CPT - Books
	 */
	public function lbs_register_books_cpt_taxonomies() {
		//Author taxonomy
		$a_labels = array(
			'name'				=> __( 'Authors', LBS_TEXT_DOMAIN ),
			'singular_name'		=> __( 'Author', LBS_TEXT_DOMAIN ),
			'search_items'		=> __( 'Search Authors', LBS_TEXT_DOMAIN ),
			'all_items'			=> __( 'All Authors', LBS_TEXT_DOMAIN ),
			'parent_item'		=> __( 'Parent Author', LBS_TEXT_DOMAIN ),
			'parent_item_colon'	=> __( 'Parent Author:', LBS_TEXT_DOMAIN ),
			'edit_item'			=> __( 'Edit Author', LBS_TEXT_DOMAIN ),
			'update_item'		=> __( 'Update Author', LBS_TEXT_DOMAIN ),
			'add_new_item'		=> __( 'Add Author', LBS_TEXT_DOMAIN ),
			'new_item_name'		=> __( 'New Author Name', LBS_TEXT_DOMAIN ),
			'menu_name'			=> __( 'Authors', LBS_TEXT_DOMAIN ),
		);
		$a_args = array(
			'hierarchical'		=> true,
			'labels'			=> $a_labels,
			'show_ui'			=> true,
			'show_admin_column'	=> true,
			'query_var'			=> true,
			'rewrite'			=> array( 'slug' => 'book-author' ),
		);
		register_taxonomy( 'book-author', array( 'book' ), $a_args );

		//Publisher taxonomy
		$p_labels = array(
			'name'				=> __( 'Publishers', LBS_TEXT_DOMAIN ),
			'singular_name'		=> __( 'Publisher', LBS_TEXT_DOMAIN ),
			'search_items'		=> __( 'Search Publishers', LBS_TEXT_DOMAIN ),
			'all_items'			=> __( 'All Publishers', LBS_TEXT_DOMAIN ),
			'parent_item'		=> __( 'Parent Publisher', LBS_TEXT_DOMAIN ),
			'parent_item_colon'	=> __( 'Parent Publisher:', LBS_TEXT_DOMAIN ),
			'edit_item'			=> __( 'Edit Publisher', LBS_TEXT_DOMAIN ),
			'update_item'		=> __( 'Update Publisher', LBS_TEXT_DOMAIN ),
			'add_new_item'		=> __( 'Add Publisher', LBS_TEXT_DOMAIN ),
			'new_item_name'		=> __( 'New Publisher Name', LBS_TEXT_DOMAIN ),
			'menu_name'			=> __( 'Publishers', LBS_TEXT_DOMAIN ),
		);
		$p_args = array(
			'hierarchical'		=> true,
			'labels'			=> $p_labels,
			'show_ui'			=> true,
			'show_admin_column'	=> true,
			'query_var'			=> true,
			'rewrite'			=> array( 'slug' => 'book-publisher' ),
		);
		register_taxonomy( 'book-publisher', array( 'book' ), $p_args );
	}

	/**
	 * Add meta box to books cpt
	 */
	public function lbs_books_metabox() {
		//Price metabox
		add_meta_box( 'lbs-price-metabox', __( 'Price', LBS_TEXT_DOMAIN ), array( $this, 'lbs_price_metabox_content' ), 'book', 'side', 'high', null );
		//Rating metabox
		add_meta_box( 'lbs-rating-metabox', __( 'Rating', LBS_TEXT_DOMAIN ), array( $this, 'lbs_rating_metabox_content' ), 'book', 'side', 'high', null );
	}

	/**
	 * Price metabox - show content
	 */
	public function lbs_price_metabox_content() {
		global $post;
		$price = get_post_meta( $post->ID, 'book-price', true );
		if( ! $price ) {
			$price = 500;
		}
		?>
		<input type="hidden" value="<?php echo $price;?>" id="hidden-book-price" name="lbs-price" />
		<button type="button" class="button button-secondary lbs-price-dec">-</button>
		<input value="<?php echo $price;?>" id="lbs-price-range" type="range" min="1" max="3000">
		<button type="button" class="button button-secondary lbs-price-inc">+</button>
		<p id="lbs-price-display"><?php echo "&#36 $price";?></p>
		<?php
	}

	/**
	 * Rating metabox - show content
	 */
	public function lbs_rating_metabox_content() {
		global $post;
		$rating = get_post_meta( $post->ID, 'book-rating', true );
		if( ! $rating ) {
			$rating = 0;
		}
		?>
		<div class="rating-stars text-center">
			<ul id="stars">
				<?php for( $i = 1; $i <= 5; $i++ ) {?>
					<?php
					$rating_class = '';
					if( $i <= $rating ) {
						$rating_class = 'selected';
					}
					?>
					<li class="star <?php echo $rating_class;?>" data-value="<?php echo $i;?>">
						<i class="fa fa-star"></i>
					</li>
				<?php }?>
			</ul>
			<input type="hidden" value="<?php echo $rating;?>" id="hidden-book-rating" name="lbs-rating" />
		</div>
		<?php
	}

	/**
	 * Actions performed to save the meta fields in books
	 */
	public function lbs_update_books_meta_fields( $postid ) {
		if(isset($_POST['lbs-price'])){
			if( get_post_type( $postid ) == 'book' ) {
				$price = sanitize_text_field( $_POST['lbs-price'] );
				update_post_meta( $postid, 'book-price', $price );

				$rating = sanitize_text_field( $_POST['lbs-rating'] );
				update_post_meta( $postid, 'book-rating', $rating );
			}
		}	
	}

	/**
	 * Actions performed to add new column headings in the books list
	 */
	public function lbs_new_column_heading( $defaults ) {
		$defaults['price']		=	__( 'Price', LBS_TEXT_DOMAIN );
		$defaults['rating']		=	__( 'Rating', LBS_TEXT_DOMAIN );
		return $defaults;
	}

	/**
	 * Actions performed to add new column content in the books list
	 */
	public function lbs_new_column_content( $column_name, $postid ) {
		$book_meta = get_post_meta( $postid );
		//Show the Book Price
		if ( $column_name == 'price' ) {
			echo '&#36 '.$book_meta['book-price'][0];
		}

		//Show Book Rating
		if ( $column_name == 'rating' ) {
			?>
			<div class="rating-stars">
				<ul>
					<?php for( $i = 1; $i <= 5; $i++ ) {?>
						<?php
						$rating_class = '';
						if( $i <= $book_meta['book-rating'][0] ) {
							$rating_class = 'selected';
						}
						?>
						<li class="star <?php echo $rating_class;?>"><i class="fa fa-star"></i></li>
					<?php }?>
				</ul>
			</div>
			<?php
		}
	}

	/**
	 * Register a submenu page to handle shortcode
	 *
	 * @since    1.0.0
	 */
	public function lbs_add_submenu_page() {
		add_submenu_page( 'edit.php?post_type=book', __( 'Book Search Shortcode Settings', LBS_TEXT_DOMAIN ), __( 'Shortcode', LBS_TEXT_DOMAIN ), 'manage_options', 'book-search-shortcode', array( $this, 'lbs_admin_page' ) );
	}

	/**
	 * Actions performed to show the contant of the submenu added
	 */
	public function lbs_admin_page() {
		?>
		<div class="wrap">
			<h2><?php _e( 'Library Book Search Shortcode', LBS_TEXT_DOMAIN );?></h2>
			<table class="form-table">
				<tr scope="row">
					<th><?php _e( 'Shortcode', LBS_TEXT_DOMAIN );?></th>
					<td>
						<strong>[library_book_search]</strong>	
						<p><i><?php _e( 'This shortcode will render the book search template, which will help the user to search the books on different criterias.', LBS_TEXT_DOMAIN );?></i></p>
					</td>
				</tr>
				<tr scope="row">
					<th><?php _e( 'How to use', LBS_TEXT_DOMAIN );?></th>
					<td>
						<p><?php _e( 'You can write that shortcode into in Wordpress Text Editor', LBS_TEXT_DOMAIN );?>:</p>
						<p><strong>[library_book_search]</strong></p>	
						
						
						<p><?php _e( 'You can write that code into files where you want to show the search filter', LBS_TEXT_DOMAIN );?>:</p>
						<p><strong> echo do_shortcode("[library_book_search]");</strong></p>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
}
?>