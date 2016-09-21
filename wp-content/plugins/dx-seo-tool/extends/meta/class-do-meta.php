<?php

class DX_Seo_Do_Meta {
	
	/**
	 * Hooks
	 */
	function __construct() {
		add_filter( 'wp_title', array( $this, 'do_title' ), 99999, 2 );
		add_action( 'wp_head', array( $this, 'do_meta' ), 1 );
	}
	
	/**
	 * wp title
	 */
	function wp_title( $sep = '&raquo;', $display = true, $seplocation = '' ) {
		global $wpdb, $wp_locale;
	
		$m = get_query_var('m');
		$year = get_query_var('year');
		$monthnum = get_query_var('monthnum');
		$day = get_query_var('day');
		$search = get_query_var('s');
		$title = '';
	
		$t_sep = '%WP_TITILE_SEP%'; // Temporary separator, for accurate flipping, if necessary
	
		// If there is a post
		if ( is_single() || ( is_home() && !is_front_page() ) || ( is_page() && !is_front_page() ) ) {
			$title = single_post_title( '', false );
		}
	
		// If there's a category or tag
		if ( is_category() || is_tag() ) {
			$title = single_term_title( '', false );
		}
	
		// If there's a taxonomy
		if ( is_tax() ) {
			$term = get_queried_object();
			$tax = get_taxonomy( $term->taxonomy );
			$title = single_term_title( $tax->labels->name . $t_sep, false );
		}
	
		// If there's an author
		if ( is_author() ) {
			$author = get_queried_object();
			$title = $author->display_name;
		}
	
		// If there's a post type archive
		if ( is_post_type_archive() )
			$title = post_type_archive_title( '', false );
	
		// If there's a month
		if ( is_archive() && !empty($m) ) {
			$my_year = substr($m, 0, 4);
			$my_month = $wp_locale->get_month(substr($m, 4, 2));
			$my_day = intval(substr($m, 6, 2));
			$title = $my_year . ( $my_month ? $t_sep . $my_month : '' ) . ( $my_day ? $t_sep . $my_day : '' );
		}
	
		// If there's a year
		if ( is_archive() && !empty($year) ) {
			$title = $year;
			if ( !empty($monthnum) )
				$title .= $t_sep . $wp_locale->get_month($monthnum);
			if ( !empty($day) )
				$title .= $t_sep . zeroise($day, 2);
		}
	
		// If it's a search
		if ( is_search() ) {
			/* translators: 1: separator, 2: search phrase */
			$title = sprintf(__('Search Results %1$s %2$s'), $t_sep, strip_tags($search));
		}
	
		// If it's a 404 page
		if ( is_404() ) {
			$title = __('Page not found');
		}
	
		$prefix = '';
		if ( !empty($title) )
			$prefix = "$sep";
	
		// Determines position of the separator and direction of the breadcrumb
		if ( 'right' == $seplocation ) { // sep on right, so reverse the order
			$title_array = explode( $t_sep, $title );
			$title_array = array_reverse( $title_array );
			$title = implode( "$sep", $title_array ) . $prefix;
		} else {
			$title_array = explode( $t_sep, $title );
			$title = $prefix . implode( "$sep", $title_array );
		}
		
		// Remove $sep in end
		$title = rtrim( $title, "$sep" );
	
		// Send it out
		if ( $display )
			echo $title;
		else
			return $title;
	
	}
	
	/**
	 * Is tax condition
	 */
	function is_tax( $tax ) {
		if( empty( $tax ) )
			return false;
		if( is_category() && in_array( 'category', $tax ) )
			return true;
		if( is_tag() && in_array( 'post_tag', $tax ) )
			return true;
		if( is_tax( $tax ) )
			return true;
		return false;
	}
	
	/**
	 * Do title
	 */
	function do_title( $title, $sep ) {				
		global $paged, $page, $post, $wp_query;
		$options = get_option( 'aioseop_options' );
		$sep = isset( $options['dxseo_title_sep'] ) && $options['dxseo_title_sep'] ? $options['dxseo_title_sep'] : " $sep ";
		
		// Default
		$title = $this->wp_title( $sep, false, 'right' );
		
		// Home
		if( is_home() ){
			$title = ( isset( $options['aiosp_home_title'] ) && $options['aiosp_home_title'] ) ? $options['aiosp_home_title'] : get_bloginfo('name') . $sep . get_bloginfo('description');
		}
		
		// Singulat
		$singular_set = get_option( 'aioseop_options' );
		$posttypes = isset( $singular_set['posttypes'] ) ? (array) $singular_set['posttypes'] : array( 'post', 'page' );
		if( ( is_singular( $posttypes ) || ( is_home() && ! is_front_page() ) )&& ! empty( $posttypes ) ) {
			$datas = get_post_meta( $wp_query->queried_object_id, '_web589_singular_meta', true);
			$title = ( isset( $datas['title'] ) && $datas['title'] ) ? $datas['title'] : single_post_title( '', false );
		}
		
		// Taxonomy		
		$tax_set = get_option( 'aioseop_options' );
		$taxes = isset( $tax_set['taxonomies'] ) ? (array) $tax_set['taxonomies'] : array( 'category', 'post_tag' );
		if( $this->is_tax( $taxes ) ) {
			$tid = $wp_query->queried_object_id;
			$datas = get_option( 'tag_meta_key_' . $tid );
			if( is_category() )
				$datas = get_option( 'cat_meta_key_' . $tid );
			$title = ( isset( $datas['page_title'] ) && $datas['page_title'] ) ? $datas['page_title'] : single_term_title( '', false );
		}				
		
		// Suffix
		if( isset( $options['dxseo_title_suffix'] ) && 'on' == $options['dxseo_title_suffix'] && ! is_home() && ! is_front_page() ) {
			$tail = ( isset( $options['dxseo_title_tail'] ) && $options['dxseo_title_tail'] ) ? $options['dxseo_title_tail'] : get_bloginfo( 'name' );
			$title .= $sep . $tail;
		}
		
		// Paged	
		if ( ( $paged >= 2 || $page >= 2 ) && isset( $options['dxseo_title_paged'] ) && 'on' == $options['dxseo_title_paged'] ) {
			$title = $title . $sep . sprintf( '第 %s 页', max( $paged, $page ) );
		}
		
		return $title;		
	}
	
	/**
	 * Auto post tags to keywords
	 */
	function auto_tags( $pid ) {
		if( ! is_single() )
			return '';
		$options = get_option( 'aioseop_options' );
		if( ! isset( $options['web589_auto_keywords'] ) || 'on' != $options['web589_auto_keywords'] )
			return '';
		$auto_words = '';
		$tags = get_the_tags( $pid );
		if( $tags ) {
			$i = 1;
			$num = isset( $options['keywords_num'] ) && $options['keywords_num'] ? (int) $options['keywords_num'] : 0;
			foreach( $tags as $tag ) {
				$auto_words .= $tag->name . ',';
				if( 0 != $num && $i == $num )	// Set tag num
					break;
				$i++;
			}
		}
		return rtrim( $auto_words, ',' );	
	}
	
	/**
	 * Auto post content to description
	 */
	function auto_des() {
		if( ! is_single() && ! is_page() )
			return '';
		$options = get_option( 'aioseop_options' );
		if( !isset( $options['web589_auto_description'] ) || 'on' != $options['web589_auto_description'] )
			return '';
		global $post;
		$num = isset( $options['web589_auto_description_num'] ) && 0 < $options['web589_auto_description_num'] ? (int) $options['web589_auto_description_num'] : 0;
		$des = '';
		if( 0 < $num ) {
			$content = trim( strip_tags( $post->post_content ) );
			$content = str_replace( "\n", '', $content );
			$des = mb_strimwidth( $content, 0, $num, '', get_bloginfo( 'charset' ) );
		}
		return $des;
	}
	
	/**
	 * Do meta
	 */
	function do_meta() {
		global $page, $post, $wp_query;
		$options = get_option( 'aioseop_options' );
		$keywords = '';
		$description = '';
		$metas = '';
		
		// Get meta
		if( is_home() && ! is_paged() ) {
			$keywords = isset( $options['aiosp_home_keywords'] ) ? $options['aiosp_home_keywords'] : '';
			$description = isset( $options['aiosp_home_description'] ) ? $options['aiosp_home_description'] : '';
			$metas = isset( $options['aiosp_home_metas'] ) ? $options['aiosp_home_metas'] : '';
		}
		
		$singular_set = get_option( 'aioseop_options' );
		$posttypes = isset( $singular_set['posttypes'] ) ? (array) $singular_set['posttypes'] : array( 'post', 'page' );
		if( ( ( is_singular( $posttypes ) && 2 > $page ) || ( is_home() && ! is_front_page() && ! is_paged() ) ) && ! empty( $posttypes ) ) {			
			$postmeta = get_post_meta( $wp_query->queried_object_id, '_web589_singular_meta', true );
			$keywords = isset( $postmeta['keywords'] ) && $postmeta['keywords'] ? $postmeta['keywords'] : $this->auto_tags( $post->ID );
			$description = isset( $postmeta['description'] ) && $postmeta['description'] ? $postmeta['description'] : $this->auto_des();
			$metas = isset( $postmeta['code'] ) && $postmeta['code'] ? $postmeta['code'] : '';
		}
		
		if( is_category() && ! is_paged() ) {
			$tax_set = get_option( 'aioseop_options' );
			$taxes = isset( $tax_set['taxonomies'] ) ? (array) $tax_set['taxonomies'] : array( 'category', 'post_tag' );
			if( ! $this->is_tax( $taxes ) )
				return;
			global $wp_query;
			$cid = $wp_query->queried_object_id;
			$catmeta = get_option( 'cat_meta_key_' . $cid );
			$keywords = isset( $catmeta['metakey'] ) && $catmeta['metakey'] ? $catmeta['metakey'] : '';
			$description = isset( $catmeta['description'] ) && $catmeta['description'] ? $catmeta['description'] : '';
			$metas = isset( $catmeta['metas'] ) && $catmeta['metas'] ? $catmeta['metas'] : '';
		}
		
		if( is_tag() && ! is_paged() ) {
			$tax_set = get_option( 'aioseop_options' );
			$taxes = isset( $tax_set['taxonomies'] ) ? (array) $tax_set['taxonomies'] : array( 'category', 'post_tag' );
			if( ! $this->is_tax( $taxes ) )
				return;
			$cid = $wp_query->queried_object_id;
			$tagmeta = get_option( 'tag_meta_key_' . $cid );
			$keywords = isset( $tagmeta['metakey'] ) && $tagmeta['metakey'] ? $tagmeta['metakey'] : '';
			$description = isset( $tagmeta['description'] ) && $tagmeta['description'] ? $tagmeta['description'] : '';
			$metas = isset( $tagmeta['metas'] ) && $tagmeta['metas'] ? $tagmeta['metas'] : '';
		}
		
		if( is_tax() && ! is_paged() ) {
			$tax_set = get_option( 'aioseop_options' );
			$taxes = isset( $tax_set['taxonomies'] ) ? (array) $tax_set['taxonomies'] : array( 'category', 'post_tag' );
			if( ! $this->is_tax( $taxes ) )
				return;			
			$cid = $wp_query->queried_object_id;
			$tagmeta = get_option( 'tag_meta_key_' . $cid );
			$keywords = isset( $tagmeta['metakey'] ) && $tagmeta['metakey'] ? $tagmeta['metakey'] : '';
			$description = isset( $tagmeta['description'] ) && $tagmeta['description'] ? $tagmeta['description'] : '';
			$metas = isset( $tagmeta['metas'] ) && $tagmeta['metas'] ? $tagmeta['metas'] : '';			
		}
		
		$keywords = esc_attr( $keywords );
		$description = esc_attr( $description );
		
		// Output
		if( ! empty( $keywords ) || ! empty( $description ) || ! empty( $metas ) )
			echo "<!-- www.2zzt.com site info -->\n";
		if( $keywords )
			echo "<meta name=\"keywords\" content=\"$keywords\" />\n";
		if( $description )
			echo "<meta name=\"description\" content=\"$description\" />\n";
		if( $metas )
			echo $metas . "\n";
		if( ! empty( $keywords ) || ! empty( $description ) || ! empty( $metas ) )
			echo "<!-- www.2zzt.com site info -->\n";
	}

}

new DX_Seo_Do_Meta;