<?php

class DX_Seo_Relative_posts_List {
	
	/**
	 * Hooks
	 */
	function __construct() {
		add_filter( 'the_content', array( $this, 'do_relative' ), 9000 );
	}
	
	/**
	 * Get category ids related post
	 */
	function get_the_category_ids( $pid = '' ) {
		$cats = get_the_category( $pid );
		$cids = array();
		if( $cats ) {
			foreach( $cats as $cat ) {
				$cids[] = $cat->term_id;
			}
		}
		return $cids;
	}
	
	/**
	 * Get tag ids related post
	 */
	function get_the_tag_ids( $pid = '' ) {
		$tags = get_the_tags( $pid );
		$tids = array();
		if( $tags ) {
			foreach( $tags as $tag ) {
				$tids[] = $tag->term_id;
			}
		}
		return $tids;
	}
	
	/**
	 * Do query
	 */
	function do_query() {
		// Option value and parameters
		$setting = get_option( 'dxseo_relative_posts' );
		$num = isset( $setting['posts-num'] ) ? (int) $setting['posts-num'] : 8;
		$type = isset( $setting['related-type'] ) ? $setting['related-type'] : 'cat';
		$orderby = isset( $setting['orderby'] ) ? $setting['orderby'] : 'ID';
		$exclude_ids = array( get_the_ID() );
		
		// Query args
		$args = array(
			'posts_per_page' => $num,
			'ignore_sticky_posts' => true,
			'orderby' => $orderby,
			'post__not_in' => $exclude_ids
		);
		
		switch( $type ) {
			case 'tag': {
				$tids = $this->get_the_tag_ids();
				if( empty( $tids ) )
					return false;
				else
					$args['tag__in'] = $tids;
				break;
			}
			case 'all' : {
				$args['cat'] = 0;
				break;
			}
			default : {
				$cids = $this->get_the_category_ids();
				if( empty( $cids ) )
					return false;
				else
					$args['category__in'] = $cids;
			}
		}
		
		// Return query object
		return new WP_Query( $args );
	}
	
	/**
	 * Do loop
	 */
	function do_loop() {
		$related_query = $this->do_query();
		if( $related_query ) {
			$loop = '';
			$style = 'style="list-style-type: disc;" ';
			while( $related_query->have_posts() ) {
				$related_query->the_post();
				$loop .= '<li ' . $style . 'class="dxseo-rl-item"><a href="' . get_permalink() . '" target="_blank" title="' . the_title_attribute( array( 'before' => '', 'after' => '', 'echo' => false ) ) . '">' . get_the_title() . '</a></li>';
			}
			wp_reset_postdata();
			return $loop;
		} else
			return '';
	}
	
	/**
	 * Show relative posts list
	 */
	function do_relative( $content ) {
		// Show condition
		if( ! is_single() || ! in_the_loop() || ! is_main_query() )
			return $content;
			
		// Do loop and args
		$loop = $this->do_loop();
		$setting = get_option( 'dxseo_relative_posts' );
		$title = isset( $setting['title'] ) ? $setting['title'] : '相关文章';
		$title_size = isset( $setting['title-size'] ) ? (int) $setting['title-size'] : 14;
		
		$h_title = '<h3 style="margin:20px 0 10px;padding:0;font-weight:bold;font-size:' . $title_size . 'px;">' . $title . '</h3>';
		
		// Output
		if( $loop )
			return $content . '<div id="dxseo-related-posts" style="clear:both;">' . $h_title . '<ul class="dxseo-rl-items">' . $loop . '</ul></div>';
		else
			return $content;
	}
	
}

new DX_Seo_Relative_posts_List;