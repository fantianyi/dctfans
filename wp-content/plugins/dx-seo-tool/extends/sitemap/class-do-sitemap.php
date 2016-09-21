<?php

class DX_Seo_Sitemap_Do_Sitemap {	
	
	/**
	 * Hooks
	 */
	function __construct() {
		add_action( 'wp_ajax_' . DXSEO_PRE . '_do_sitemap', array( $this, 'ajax_do_sitemap' ) );
		add_action( 'wp_ajax_' . DXSEO_PRE . '_delete_sitemap', array( $this, 'ajax_delete_sitemap' ) );
		add_action( 'transition_post_status', array( $this, 'publish_do_xml' ), 999, 3 );
	}
	
	/**
	 * Do baidu sitemap jquery code
	 */
	static function sitemap_jquery() {
?>
<script type="text/javascript">
jQuery(document).ready(function($){
	$( '#generate-baidu' ).click(function(){
		$( '#sitemap-progress' ).html( '正在生成，请稍后......' );
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: ({
					action: '<?php echo DXSEO_PRE; ?>_do_sitemap',
					nonce: '<?php echo wp_create_nonce( DXSEO_PRE . '_do_sitemap' ); ?>'
				}),
			cache: false,
			success: function(res){
				$( '#sitemap-progress' ).html( '' );
				$( '#settings-container h2.menu-title' ).after( res );
			}	
		});
	});
	$( '#delete-baidu' ).click(function(){
		$( '#sitemap-progress' ).html( '正在删除，请稍后......' );
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: ({
					action: '<?php echo DXSEO_PRE; ?>_delete_sitemap',
					nonce: '<?php echo wp_create_nonce( DXSEO_PRE . '_delete_sitemap' ); ?>'
				}),
			cache: false,
			success: function(res){
				$( '#sitemap-progress' ).html( '' );
				$( '#settings-container h2.menu-title' ).after( res );
			}	
		});
	});	
});
</script>
<?php	
	}	
	
	/**
	 * Do post types query
	 */
	function post_types_query() {
		$settings = get_option( 'dxseo_sitemap_generate' );
		$num = isset( $settings['baidu-num'] ) ? (int) $settings['baidu-num'] : 500;
		$post_types = isset( $settings['baidu-post-types'] ) ? (array) $settings['baidu-post-types'] : array();
		if( empty( $num ) || empty( $post_types ) )
			return NULL;
		$args = array(
			'ignore_sticky_posts' => true,
			'posts_per_page' => $num,
			'post_type' => $post_types,
			'order' => 'DESC',
			'orderby' => 'date',
			'post_status' => 'publish'
		);
		$query = new WP_Query( $args );
		return $query;
	}
	
	/**
	 * Do taxonomies query
	 */
	function taxonomies_query() {
		$settings = get_option( 'dxseo_sitemap_generate' );
		$taxonomies = isset( $settings['baidu-taxonomies'] ) ? (array) $settings['baidu-taxonomies'] : array();
		if( empty( $taxonomies ) )
			return array();
		$args = array(
			'orderby' => 'id',
			'order' => 'DESC',
			'fields' => 'all',
			'hide_empty' => false
		);
		$terms = get_terms( $taxonomies, $args );
		return $terms;
	}
	
	/**
	 * Do baidu sitemap
	 */
	function do_sitemap() {
		date_default_timezone_set( get_option('timezone_string') );
		
		$xml = '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '"?>';
		$xml .= '<!-- generator="wordpress/' . get_bloginfo('version') . '" --><!-- sitemap-generator-url="' . home_url() . '" sitemap-generator-version="' . get_bloginfo('version') . '" --><!-- generated-on="' . current_time( 'mysql' ) . '" -->';
		$xml .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		// Home
		$xml .= '<url>';
		$xml .= '<loc>' . home_url() . '</loc>';
		$xml .= '<lastmod>' . date( 'c' ) . '</lastmod>';
				$xml .= '<changefreq>daily</changefreq>';
				$xml .= '<priority>1.0</priority>';		
		$xml .= '</url>';

		$posts = $this->post_types_query();
		$terms = $this->taxonomies_query();		
		// Post types
		if( $posts ) {
			while( $posts->have_posts() ) {
				$posts->the_post();
				$xml .= '<url>';
				$post_link = get_permalink();
				$xml .= '<loc>' . apply_filters( DXSEO_PRE . '_sitemap_post_link', $post_link ) . '</loc>';
				$xml .= '<lastmod>' . get_the_modified_time( 'c' ) . '</lastmod>';
				$xml .= '<changefreq>monthly</changefreq>';
				$priority = ( (int) $posts->post->comment_count > 0 ) ? '0.7' : '0.3';
				$xml .= '<priority>' . $priority . '</priority>';
				$xml .= '</url>';
			}
		}
		
		wp_reset_postdata();
		
		// Taxonomies
		if( $terms ) {
			foreach( $terms as $term ) {
				$xml .= '<url>';
				$tax_link = get_term_link( (int)$term->term_id, $term->taxonomy );
				$xml .= '<loc>' . apply_filters( DXSEO_PRE . '_sitemap_tax_link', $tax_link ) . '</loc>';
				$xml .= '<changefreq>weekly</changefreq>';
				$xml .= '<priority>0.5</priority>';				
				$xml .= '</url>';
			}
		}
		$xml .= '</urlset>';
		
		return $xml;
	}
	
	/**
	 * Get sitemap file
	 */
	static function get_sitemap_file() {
		$settings = get_option( 'dxseo_sitemap_generate' );
		$sitemap = isset( $settings['sitemap-file'] ) ? $settings['sitemap-file'] : 'sitemap';
		$file = ABSPATH . $sitemap . '.xml';
		return $file;		
	}
	
	/**
	 * Show sitemap file message
	 */
	static function xml_notice() {
		$file = self::get_sitemap_file();
		if( file_exists( $file ) ) {
			$size = @filesize( $file );
			date_default_timezone_set( get_option('timezone_string') );
			$time = date( 'Y-m-d H:i:s', @filemtime( $file ) );
			$notice = '<div id="setting-error-settings_updated" class="updated settings-error"><p style="color:green;">已经生成sitemap文件，大小：<strong>' . number_format( $size/1024, 2 ) . 'KB</strong>，生成时间：<strong>' . $time . '</strong>。sitemap地址： ' . home_url() . '/' . basename( $file ) . ' ，<a href="' . home_url() . '/' . basename( $file ) . '" target="_blank">点击浏览</a></p></div>';
		} else {
			$notice = '<div id="setting-error-settings_updated" class="error settings-error"><p>sitemap文件已删除或不存在，请设置好参数保存后点击&quot;生成sitemap&quot;按钮。。</p></div>';
		}
		echo $notice;
	}	
	
	/**
	 * Do sitemap xml file
	 */
	function do_xml( $notice = true ) {
		$file = $this->get_sitemap_file();
		$xml = 	$this->do_sitemap();
		$size = @file_put_contents( $file, $xml );
		if( $size > 0 && true === $notice ) {
			$this->xml_notice();
		}
	}
	
	/**
	 * ajax for do baidu sitemap
	 */
	function ajax_do_sitemap() {
		if( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], DXSEO_PRE . '_do_sitemap' ) )
			die( 'ERROR!' );
		$this->do_xml();
		die();
	}
	
	/**
	 * ajax for delete baidu sitemap
	 */
	function ajax_delete_sitemap() {
		if( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], DXSEO_PRE . '_delete_sitemap' ) )
			die( 'ERROR!' );
		$file = $this->get_sitemap_file();
		if( file_exists( $file ) ) {
			@unlink( $file );
			$this->xml_notice();
		}
		if( file_exists( ABSPATH . 'sitemap-baidu.xml' ) )
			@unlink( ABSPATH . 'sitemap-baidu.xml' );
		die();
	}
	
	/**
	 * Publish and do xml file
	 */	
	function publish_do_xml( $new_status, $old_status, $post ) {
		$settings = get_option( 'dxseo_sitemap_generate' );
		$auto = isset( $settings['baidu-auto-update'] ) ? $settings['baidu-auto-update'][0] : '';
		if( 'on' == $auto && ( ( 'publish' == $new_status && $old_status != $new_status ) || ( 'publish' == $old_status && $old_status != $new_status ) ) ) {
			$this->do_xml( false );
		}
	}
	
}

new DX_Seo_Sitemap_Do_Sitemap;