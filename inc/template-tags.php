<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Exalt
 */



if ( ! function_exists( 'exalt_primary_nav' ) ) : 
	/**
	 * Displays primary navigation.
	 * 
	 */
	function exalt_primary_nav() {
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location'	=> 'primary',
				'menu_id'			=> 'primary-menu',
				'exalt_show_icons'	=> true
			) );
		} else {
			wp_page_menu( array(
				'title_li'      	=> '',
				'exalt_show_icons'	=> true,
				'walker'			=> new Exalt_Walker_Page()
			) );
		}
	}

endif;

if ( ! function_exists( 'exalt_primary_nav_sidebar' ) ) : 
	/**
	 * Displays primary navigation.
	 * 
	 */
	function exalt_primary_nav_sidebar() {
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' 		=> 'primary',
				'menu_id'        		=> 'primary-menu',
				'exalt_show_toggles'	=> true
			) );
		} else {
			wp_page_menu( array(
				'title_li'      		=> '',
				'exalt_show_toggles'  	=> true,
				'walker'        		=> new Exalt_Walker_Page()
			) );
		}
	}

endif;

if ( ! function_exists( 'exalt_secondary_nav' ) ) : 
	/**
	 * Displays secondary navigation.
	 */
	function exalt_secondary_nav() {
		wp_nav_menu( array(
			'theme_location' 	=> 'secondary',
			'menu_id'        	=> 'secondary-menu',
			'exalt_show_icons'	=> true
		) );
	}

endif;

if ( ! function_exists( 'exalt_secondary_nav_mobile' ) ) : 
	/**
	 * Displays secondary navigation.
	 */
	function exalt_secondary_nav_mobile() {
		wp_nav_menu( array(
			'theme_location' 		=> 'secondary',
			'menu_id'        		=> 'secondary-menu',
			'exalt_show_toggles'   	=> true
		) );
	}

endif;

if ( ! function_exists( 'exalt_social_nav' ) ) : 
	/**
	 * Displays social navigation.
	 */
	function exalt_social_nav() {
		if ( has_nav_menu( 'social' ) ) : ?>
			<nav class="exalt-social-menu" aria-label="<?php esc_attr_e( 'Expanded Social links', 'exalt' ); ?>">
				<ul class="exalt-social-menu exalt-social-icons">
				<?php
					wp_nav_menu(
						array(
							'theme_location'  => 'social',
							'container'       => '',
							'container_class' => '',
							'items_wrap'      => '%3$s',
							'menu_id'         => '',
							'menu_class'      => '',
							'depth'           => 1,
							'link_before'     => '<span class="screen-reader-text">',
							'link_after'      => '</span>',
							'fallback_cb'     => '',
						)
					);
				?>
				</ul>
			</nav><!-- .exalt-social-menu -->
		<?php
		endif; 
	}
endif;


if ( ! function_exists( 'exalt_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function exalt_posted_on() {

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published sm-hu" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		add_filter( 'get_the_modified_date', 'exalt_convert_modified_to_time_ago', 10, 3 );

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		remove_filter( 'get_the_modified_date', 'exalt_convert_modified_to_time_ago', 10, 3 );

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'exalt_author_avatar' ) ) :

	function exalt_author_avatar() {

		$author_email	= get_the_author_meta( 'user_email' );
		$avatar_url 	= get_avatar_url( $author_email );
		
		echo '<span class="exalt-author-avatar"><img class="author-photo" alt="' . esc_attr( get_the_author() ) . '" src="' . esc_url( $avatar_url ) . '" /></span>';

	}

endif;

if ( ! function_exists( 'exalt_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function exalt_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'exalt' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

if ( ! function_exists( 'exalt_categories' ) ) :
	/**
	 * Prints the category list
	 */
	function exalt_categories() {
		if ( 'post' === get_post_type() ) {

			if ( is_single() ) {
				$show_category_list = get_theme_mod( 'exalt_show_cat_links_s', true );
			} else {
				$show_category_list = get_theme_mod( 'exalt_show_cat_links', false );
			}

			if ( ! $show_category_list ) {
				return;
			}

			$categories_list = get_the_category_list();
			if ( $categories_list ) {
				/* translators: 1: posted in label 2: list of categories. */
				printf( 
					'<span class="cat-links"><span class="screen-reader-text">%1$s</span>%2$s</span>', 
					esc_html__( 'Posted in', 'exalt' ),
					apply_filters( 'exalt_theme_categories', $categories_list )
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

endif;

if ( ! function_exists( 'exalt_tags_list' ) ) :
	/**
	 * Prints the tags list
	 */
	function exalt_tags_list() {
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'exalt' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'exalt' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

endif;

if ( ! function_exists( 'exalt_comments_link' ) ) :
	/**
	 * Prints comments link
	 */
	function exalt_comments_link() {

		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="exalt-comments-icon">' . exalt_get_icon_svg( 'comment' ) . '</span>';
			echo '<span class="comments-link">';
				comments_popup_link( '0', '1', '%' );
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'exalt_entry_meta' ) ) :
	/**
	 * Entry Meta
	 */
	function exalt_entry_meta() {

		$entry_meta_items = array();
	
		if ( is_single() ) {
			if ( true == get_theme_mod( 'exalt_show_author_avatar_s', false ) ) {
				$entry_meta_items[] = 'exalt_author_avatar';
			}
			if ( true == get_theme_mod( 'exalt_show_author_s', true ) ) {
				$entry_meta_items[] = 'exalt_posted_by';
			}
			if ( true == get_theme_mod( 'exalt_show_date_s', true ) ) {
				$entry_meta_items[] = 'exalt_posted_on';
			}
			if ( true == get_theme_mod( 'exalt_show_comments_link_s', true ) ) {
				$entry_meta_items[] = 'exalt_comments_link';
			}
		} else {
			if ( true == get_theme_mod( 'exalt_show_author_avatar', false ) ) {
				$entry_meta_items[] = 'exalt_author_avatar';
			}
			if ( true == get_theme_mod( 'exalt_show_author', true ) ) {
				$entry_meta_items[] = 'exalt_posted_by';
			}
			if ( true == get_theme_mod( 'exalt_show_date', true ) ) {
				$entry_meta_items[] = 'exalt_posted_on';
			}
			if ( true == get_theme_mod( 'exalt_show_comments_link', true ) ) {
				$entry_meta_items[] = 'exalt_comments_link';
			}
		}

		foreach( $entry_meta_items as $key => $item ) {
			$item();
			// if ( $key !== array_key_last( $entry_meta_items ) ) {
			// 	echo '<span="exalt-meta-sep">&mdash;</span>';
			// }
		}

		//$entry_meta_string = implode( '<span="exalt-meta-sep">&mdash;</span>', $entry_meta_items );
		//echo $entry_meta_string;
		
	}

endif;

if ( ! function_exists( 'exalt_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function exalt_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {

			if ( is_single() && true === get_theme_mod( 'exalt_show_tags_list_s', true ) ) {
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list();
				if ( $tags_list ) {
					/* translators: 1: list of tags. */
					printf( 
						'<div class="exalt-tag-list"><span class="exalt-tagged">%1$s</span><span class="tags-links exalt-tags-links">%2$s</span></div>', 
						esc_html__( 'Tagged', 'exalt' ), 
						$tags_list 
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			} 
			
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'exalt' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'exalt' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'exalt_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function exalt_post_thumbnail() {

		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		$image_size = apply_filters( 'exalt_post_thumbnail_size', 'exalt-featured-image' );

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail( $image_size ); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<div class="post-thumbnail">
				<a href="<?php the_permalink(); ?>">
					<?php
						the_post_thumbnail(
							$image_size,
							array(
								'alt' => the_title_attribute(
									array(
										'echo' => false,
									)
								),
							)
						);
					?>
				</a>
			</div><!-- .post-thumbnail -->

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'exalt_post_previous_next' ) ) :
	/**
	 * Prints previous and next links for single posts.
	 */
	function exalt_post_previous_next() {
		if ( true === get_theme_mod( 'exalt_post_previous_next', true ) && is_singular( 'post' ) ) {
			the_post_navigation(
				array(
					'next_text' => '<span class="posts-nav-text" aria-hidden="true">' . esc_html__( 'Next Article', 'exalt' ) . '</span> ' .
						'<span class="screen-reader-text">' . esc_html__( 'Next article:', 'exalt' ) . '</span> <br/>' .
						'<span class="post-title">%title</span>',
					'prev_text' => '<span class="posts-nav-text" aria-hidden="true">' . esc_html__( 'Previous Article', 'exalt' ) . '</span> ' .
						'<span class="screen-reader-text">' . esc_html__( 'Previous article:', 'exalt' ) . '</span> <br/>' .
						'<span class="post-title">%title</span>',
				)
			);
		}
	}
endif;
add_action( 'exalt_after_article', 'exalt_post_previous_next', 12 );

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;


if ( ! function_exists( 'exalt_posts_pagination' ) ) {
	/**
	 * Posts pagination.
	 */
	function exalt_posts_pagination() {

		$pagination_type = get_theme_mod( 'exalt_pagination_type', 'page-numbers' );

		if ( $pagination_type == 'page-numbers' ) {
			the_posts_pagination();
		} else {
			the_posts_navigation(
				array(
					'prev_text' => __( '&larr; Older Posts', 'exalt' ),
					'next_text' => __( 'Newer Posts &rarr;', 'exalt' ),
				)
			);
		}

	}

}

if ( ! function_exists( 'exalt_read_more_button' ) ) {
	/**
	 * Read More Button Markup
	 */
	function exalt_read_more_button() {
		if ( 'button' === get_theme_mod( 'exalt_read_more_type', 'link' ) ) : ?>
			<div class="entry-readmore">
				<a href="<?php the_permalink(); ?>" class="exalt-readmore-btn">
					<?php the_title( '<span class="screen-reader-text">', '</span>' ); ?>
					<?php echo esc_html_e( 'Read More', 'exalt' ); ?>
				</a>
			</div>
		<?php endif; 
	}
}

if ( ! function_exists( 'exalt_entry_footer_markup' ) ) {
	/**
	 * Entry footer.
	 */
	function exalt_entry_footer_markup() {
		if ( is_single() ) {
			?>
				<footer class="entry-footer">
					<?php exalt_entry_footer(); ?>
				</footer><!-- .entry-footer -->
			<?php
		}
	}
}
add_action( 'exalt_after_entry_content', 'exalt_entry_footer_markup' );