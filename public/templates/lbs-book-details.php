<?php
/**
 * Book Details Template
 */

if( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

get_header();
global $post;
$postid = $post->ID;
$post_status = $post->post_status;
$post_content = ( $post->post_excerpt == '' ) ? $post->post_content : $post->post_excerpt;
$post_meta = get_post_meta( $postid );

$authors = wp_get_object_terms( $postid, 'book-author' );
$publishers = wp_get_object_terms( $postid, 'book-publisher' );
?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<article id="post-<?php echo $postid;?>" class="post-<?php echo $postid;?> page type-page status-<?php echo $post_status;?> hentry">
			<header class="entry-header">
				<h1 class="entry-title">
					<?php echo $post->post_title;?>
				</h1>
			</header>
			<div class="entry-content">
				<?php echo $post_content;?>
			</div>
			<footer class="entry-footer">
				<table class="lbs-book-meta-details-tbl">
					<!-- BOOK PRICE -->
					<tr>
						<th><?php _e( 'Price', LBS_TEXT_DOMAIN );?></th>
						<td><?php echo '&#8377 ' . $post_meta['book-price'][0];?></td>
					</tr>

					<!-- BOOK RATING -->
					<tr>
						<th><?php _e( 'Rating', LBS_TEXT_DOMAIN );?></th>
						<td>
							<div class="rating-stars">
								<ul>
									<?php for( $i = 1; $i <= 5; $i++ ) {?>
										<?php
										$rating_class = '';
										if( $i <= $post_meta['book-rating'][0] ) {
											$rating_class = 'selected';
										}
										?>
										<li class="star <?php echo $rating_class;?>"><i class="fa fa-star"></i></li>
									<?php }?>
								</ul>
							</div>
						</td>
					</tr>

					<!-- BOOK AUTHOR -->
					<tr>
						<th><?php _e( 'Author', LBS_TEXT_DOMAIN );?></th>
						<td>
							<?php if( ! empty( $authors ) ) {?>
								<?php foreach( $authors as $author ) {?>
									<?php $author_link = get_term_link( $author->term_id, 'book-author' );?>
									<p><a href="<?php echo $author_link;?>"><?php echo $author->name;?></a></p>
								<?php }?>
							<?php } else {?>
								<div>--</div>
							<?php }?>
						</td>
					</tr>

					<!-- BOOK PUBLISHER -->
					<tr>
						<th><?php _e( 'Publisher', LBS_TEXT_DOMAIN );?></th>
						<td>
							<?php if( ! empty( $publishers ) ) {?>
								<?php foreach( $publishers as $publisher ) {?>
									<?php $publisher_link = get_term_link( $publisher->term_id, 'book-publisher' );?>
									<p><a href="<?php echo $publisher_link;?>"><?php echo $publisher->name;?></a></p>
								<?php }?>
							<?php } else {?>
								<div>--</div>
							<?php }?>
						</td>
					</tr>
				</table>
				<?php 
				if( current_user_can( 'administrator' ) ) 
				{
					$post_edit_link = admin_url( "post.php?post=$postid&amp;action=edit" );
					?>
					<span class="edit-link">
						<a class="post-edit-link" href="<?php echo $post_edit_link;?>">
							<?php _e( 'Edit', LBS_TEXT_DOMAIN );?>
							<span class="screen-reader-text">"Sample Page"</span>
						</a>
					</span>
				<?php 
				}
				?>
			</footer>
		</article>
	</main>
</div>
<?php get_sidebar();?>
<?php get_footer();?>