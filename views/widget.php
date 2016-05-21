<?php
	// Block direct requests
	if ( !defined('ABSPATH') )
		die('-1');

	//check to make sure we have a category to load posts from.
	if ( !empty($instance['category']) ) {
		$navLinks 			= $instance['nav'] == 1 			? 'true' 	: 'false';
		$navDots 			= $instance['navdots'] == 1 		? 'true' 	: 'false';
		$autoplay 			= $instance['autoplay'] == 1 		? 'true' 	: 'false';
		$selectedCategory 	= $instance['category'] == 'all' 	? '' 		: $instance['category'];
		//set our get_posts args
		$post_args = array(
			'posts_per_page'   => '-1',
			'category'         => $instance['category'],
			'post_type'        => 'post',
			'post_status'      => 'publish'
		);
		//get our posts from the choosen category
		$posts_array = get_posts( $post_args ); 
		//open carousel stage
		echo '<div class="owl-carousel '.$args['widget_id'].'">';
		if ($posts_array ) {
			//loop through our posts outputting each post in it's own item container
			foreach ($posts_array as $post ) {
				setup_postdata( $post );
				echo '<div class="item">';
					if ( has_post_thumbnail( $post->ID ) && $instance['featuredimage'] == 1 ) {
					echo '<div class="item-image">';
						echo '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( $post->post_title ) . '">';
						echo get_the_post_thumbnail( $post->ID, $instance['imagesize'] );
						echo '</a>';
					echo '</div>';
					}
					echo '<h4 class="item-title"><a href="'.get_attachment_link( $post->ID, false ).'">'.get_the_title($post->ID).'</a></h4>';
					echo '<div class="item-content">';
						echo wpautop(get_the_excerpt($post->ID));
					echo '</div>';
				echo '</div>';
			}
			wp_reset_postdata();
		} else {
			//No posts found so display an short message
			echo '<h2>Sorry, no posts were found for the selected category';
		}
		echo '</div>';
		//output the Javacsript for this instance of the carousel to work. Paramaters can be set or modified here. See http://www.owlcarousel.owlgraphic.com/docs/api-options.html for a full list of options available.
		echo '<script type="text/javascript">';
		echo '(function ($) { "use strict"; $(function () { $(".owl-carousel.'.$args['widget_id'].'").owlCarousel({ loop:true, margin:'.$instance['itemmargin'].', nav:'.$navLinks.', dots:'.$navDots.', autoplay:'.$autoplay.', autoplayHoverPause:true, responsive:{ 0:{ items:1 }, 600:{ items:2 }, 1000:{ items:'.$instance['itemcount'].' } } }); }); }(jQuery));';
		echo '</script>';
	}
?>