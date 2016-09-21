<?php
/**
 * Auto replace to anchor in content 
 */
class DX_Seo_Do_Anchor {
	
	/*
	 * Properties
	 */
	var $settings;		// Settings value
	var $datas;			// anchor text datas value
	var $pattern;		// $pattern for preg_replace_callback
	var $replace;		// $replace for preg_replace_callback
	
	/**
	 * Hooks
	 */
	function __construct() {
		add_filter( 'the_content', array( $this, 'do_anchor' ), 8000 );
	}
	
	/**
	 * Datas orderby priority
	 */
	function datas_orderby_priority() {
		$priorities = get_option( DXSEO_PRE . 'anchor_text_priority' );
		if( $priorities ) {
			arsort( $priorities, SORT_NUMERIC );
			foreach( $priorities as $key => $priority ) {
				if( isset( $this->datas[ $key ] ) )
					$temp[ $key ] = $this->datas[ $key ];
			}
			if( $temp )
				$this->datas = $temp;
		}		
	}
	
	/**
	 * Is do anchor page condition
	 */
	function is_anchor_page() {		

		// Check main query and loop
		if( ! in_the_loop() || ! is_main_query()  )
			return false;
		
		// Check post types	
		$this->settings = get_option( DXSEO_PRE . '_anchor_text_settings' );
		$post_types = isset( $this->settings['application-page'] ) ? (array) $this->settings['application-page'] : array( 'post' );
		if( ! is_singular( $post_types ) || empty( $post_types ) )
			return false;			
		
		// Check datas	
		$this->datas = get_option( DXSEO_PRE . '_anchor_text_datas' );
		if( empty( $this->datas ) )
			return false;
		
		// Check max_num value
		$this->max_num = isset( $this->settings['max-num'] ) ? (int) $this->settings['max-num'] : 8;
		if( 0 == $this->max_num || -1 > $this->max_num )
			return false;
			
		// Check admin
		if( is_admin() )
			return false;
		
		return true;
	}
	
	/**
	 * Ignore tags
	 */
	function ignore_tags() {
		$tag = ( isset( $this->settings['tags-filter'] ) && $this->settings['tags-filter'] ) ? $this->settings['tags-filter'] : '';
		$tags = array();
		if( $tag ) {
			$tags = explode( ',', trim( trim( trim( $tag ), ',' ) ) );
		}
		$tags = array_merge( array( 'a', 'embed', 'iframe' ), $tags );
		$tags = array_flip( $tags );
		$tags = array_flip( $tags );
		return $tags;
	}
	
	/**
	 * Content to trash ignore some tags
	 */
	function content_trash() {
		$tags = $this->ignore_tags();
		$temp = '*&####&*';
		$i = 0;	
			
		// tags filter
		foreach( $tags as $tag ) {
			if( preg_match_all( '/<' . $tag . '.*?>.*?<\/' . $tag . '>/is', $this->content, $matches ) ) {
				foreach( $matches[0] as $match ) {
					$this->content_ignore[] = $match;
					$this->content_temp[] = $temp . '_' . $i . '_' . $temp;
					$this->content = str_replace( $this->content_ignore[ $i ], $this->content_temp[ $i ], $this->content );
					$i++;
				}				
			}			
		}
		
		// tag img filter
		if( preg_match_all( '/<img.*?>/i', $this->content, $matches ) ) {
			foreach( $matches[0] as $match ) {
				$this->content_ignore[] = $match;
				$this->content_temp[] = $temp . '_' . $i . '_' . $temp;
				$this->content = str_replace( $this->content_ignore[ $i ], $this->content_temp[ $i ], $this->content );
				$i++;
			}				
		}
	}
	
	/**
	 * Content trashed restore
	 */
	function content_restore() {
		if( isset( $this->content_temp ) && $this->content_temp )
			$this->content = str_replace( $this->content_temp, $this->content_ignore, $this->content );
	}
	
	/**
	 * Do links replace
	 */
	function do_links_replace() {
		$k = 0;
		$class = ( isset( $this->settings['class'] ) && $this->settings['class'] ) ? ' class="' . $this->settings['class'] . '"' : '';
		foreach( $this->datas as $key=>$value ) {			
			// Variables
			$i = ( 1 == $this->datas[ $key ][3] ) ? 'i' : '';
			$pattern =  '/(' . $key . ')/' . $i;
			$href = ' href="' . $value[0] . '"';
			$title = ' title="' . esc_attr( $key ) . '"';
			$rel = ( 1 == $value[1] ) ? ' rel="nofollow"' : '';
			$target = ( 1 == $value[2] ) ? 'target="_blank"' : '';
			
			// Ignore self page
			if( get_permalink() == $value[0] )
				continue;

			// Replace
			$limit = $value[5];	
			
			if( 1 == $value[4] )
				$this->content = preg_replace( $pattern, '<strong><a' . $class . $href . $title . $rel . $target . '>${1}</a></strong>', $this->content, $limit );
			else {				
				$this->content = preg_replace( $pattern, '<a' . $class . $href . $title . $rel . $target . '>${1}</a>', $this->content, $limit );
			}
			
			// Temp
			$temp = '%^^&&^^%';
			$preg_temp = '\%\^\^\&\&\^\^\%';
			if( 1 == $value[4] )
				$preg = preg_match( '/<strong><a.*?>(.*?)<\/a><\/strong>/', $this->content, $match );
			else {				
				$preg = preg_match( '/<a.*?>(.*?)<\/a>/', $this->content, $match );
			}			
			if( $preg ) {
				$anchor_temp[ $k ] = $temp . '_' . $k . '_' . $temp;
				$anchor_link[ $k ] = $match[0];
				$anchor_text[ $k ] = $match[1];
				if( 1 == $value[4] )
					$this->content = preg_replace( '/<strong><a.*?>.*?<\/a><\/strong>/', $anchor_temp[ $k ], $this->content );
				else
					$this->content = preg_replace( '/<a.*?>.*?<\/a>/', $anchor_temp[ $k ], $this->content );
				$k++;
			}
			
		}
		
		// Restore link
		$num = isset( $this->settings['max-num'] ) ? (int) $this->settings['max-num'] : 8;
		if( -1 != $num && isset( $anchor_temp ) && $anchor_temp ) {
			for( $j = 0; $j <= ( $num - 1 ); $j++ ) {
				if( preg_match( '/' . $preg_temp . '_(\d+)_' . $preg_temp . '/', $this->content, $match ) ) {
					$this->content = preg_replace( '/' . $preg_temp . '_' . $match[1] . '_' . $preg_temp . '/', $anchor_link[ $match[1] ], $this->content, 1 ); 
				}
			}
			// Restore text
			$this->content = str_replace( $anchor_temp, $anchor_text, $this->content );		
		} else {
			if( isset( $anchor_temp ) && $anchor_temp )
				$this->content = str_replace( $anchor_temp, $anchor_link, $this->content );
		}
		
		// Remove repeat strong tag
		if( isset( $anchor_temp ) && $anchor_temp ) {
			$this->content = preg_replace( '/(<strong.*?>.*?)<strong>(.*?)<\/strong>(.*?<\/strong>)/i', '${1}${2}${3}', $this->content );
		}
	}
	
	/**
	 * Do anchor
	 */
	function do_anchor( $content ) {
		if( $this->is_anchor_page() ) {
			$this->content = $content;
			$this->content_trash();
			$this->datas_orderby_priority();
			$this->do_links_replace();
			$this->content_restore();
			$content = $this->content;
		}
		return $content;
	}
	
}

new DX_Seo_Do_Anchor;