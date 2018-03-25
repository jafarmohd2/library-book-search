<?php 
if( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

$books = get_posts(
	array(
		'post_type' => 'book',
		'post_status' => 'publish',
		'posts_per_page' => -1
	)
);
$publishers = get_terms(
	'book-publisher', array(
		'hide_empty' => false
	)
);
?>
<div class="lbs">
	<div class="lbs-fields">
		<div class="lbs-row-1 lbs-row">
			<div class="lbs-book-name lbs-col">
				<label for="lbs-book-name"><?php _e( 'Book Name', LBS_TEXT_DOMAIN );?></label>
				<p><input type="text" placeholder="<?php _e( 'Book Name', LBS_TEXT_DOMAIN );?>" id="lbs-book-name"></p>
			</div>
			<div class="lbs-author lbs-col">
				<label for="lbs-author"><?php _e( 'Author', LBS_TEXT_DOMAIN );?></label>
				<p><input type="text" placeholder="<?php _e( 'Author', LBS_TEXT_DOMAIN );?>" id="lbs-author"></p>
			</div>
		</div>

		<div class="lbs-row-2 lbs-row">
			<div class="lbs-book-publisher lbs-col">
				<label for="lbs-publisher"><?php _e( 'Book Publisher', LBS_TEXT_DOMAIN );?></label>
				<select id="lbs-publisher">
					<option value=""><?php _e( '--Select--', LBS_TEXT_DOMAIN );?></option>
					<?php if( ! empty ( $publishers ) ) {?>
						<?php foreach( $publishers as $publisher ) {?>
							<option value="<?php echo $publisher->term_id;?>"><?php echo $publisher->name;?></option>
						<?php }?>
					<?php }?>
				</select>
			</div>
			<div class="lbs-rating lbs-col">
				<label for="lbs-rating"><?php _e( 'Rating', LBS_TEXT_DOMAIN );?></label>
				<select id="lbs-rating">
					<option value=""><?php _e( '--Select--', LBS_TEXT_DOMAIN );?></option>
					<?php for ( $i = 1; $i <= 5; $i++ ) {?>
						<option value="<?php echo $i;?>"><?php echo $i;?></option>
					<?php }?>
				</select>
			</div>
		</div>

		<div class="lbs-row-3 lbs-row">
			<div class="lbs-price lbs-col">
				<label for="lbs-price"><?php _e( 'Price (in $)', LBS_TEXT_DOMAIN );?></label>
				<label id="lbs-price-display">(200 - 800)</label>
				<input type="hidden" id="lbs-min-price" value="200">
				<input type="hidden" id="lbs-max-price" value="800">
				<div id="lbs-price"></div>
			</div>
		</div>

		<div class="lbs-row-4">
			<div class="lbs-search">
				<button type="button" id="lbs-search-books"><?php _e( 'Search', LBS_TEXT_DOMAIN );?></button>
			</div>
		</div>
	</div>
	<div class="lbs-results">
		<?php if( ! empty( $books ) ) {?>
			<table class="lbs-list-results">
				<thead>
					<tr>
						<th><?php _e( 'No.', LBS_TEXT_DOMAIN );?></th>
						<th><?php _e( 'Book Name', LBS_TEXT_DOMAIN );?></th>
						<th><?php _e( 'Price', LBS_TEXT_DOMAIN );?></th>
						<th><?php _e( 'Author', LBS_TEXT_DOMAIN );?></th>
						<th><?php _e( 'Publisher', LBS_TEXT_DOMAIN );?></th>
						<th><?php _e( 'Rating', LBS_TEXT_DOMAIN );?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $books as $index => $book ) {?>
						<?php 
						$book_meta = get_post_meta( $book->ID );
						$authors = wp_get_object_terms( $book->ID, 'book-author' );
						$authors_str = '--';
						if( !empty( $authors ) ) {
							$authors_str = '';
							foreach( $authors as $author ) {
								$author_link = get_term_link( $author->term_id, 'book-author' );
								$authors_str .= '<a href="'.$author_link.'">'.$author->name.'</a>,';
							}
							$authors_str = rtrim( $authors_str, ',' );
						}

						$publishers = wp_get_object_terms( $book->ID, 'book-publisher' );
						$publishers_str = '--';
						if( !empty( $publishers ) ) {
							$publishers_str = '';
							foreach( $publishers as $publisher ){
								$publisher_link = get_term_link( $publisher->term_id, 'book-publisher' );
								$publishers_str .= '<a href="'.$publisher_link.'">'.$publisher->name.'</a>,';
							}
							$publishers_str = rtrim( $publishers_str, ',' );
						}
						?>
						<tr>
							<td><?php echo ( $index + 1 ).'.';?></td>
							<td><a target="_blank" href="<?php echo get_permalink( $book->ID );?>" title="<?php echo $book->post_title;?>"><?php echo $book->post_title;?></a></td>
							<td><?php echo '&#36 ' . $book_meta['book-price'][0];?></td>
							<td><?php echo $authors_str;?></td>
							<td><?php echo $publishers_str;?></td>
							<td>
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
							</td>
						</tr>
					<?php }?>
				</tbody>
			</table>
		<?php } else {?>
			<div class="lbs-failure">
				<p><?php _e( 'Sorry! No Books Available!', LBS_TEXT_DOMAIN );?></p>
			</div>
		<?php }?>
	</div>
</div>