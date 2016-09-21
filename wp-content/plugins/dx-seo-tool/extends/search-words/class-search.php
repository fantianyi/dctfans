<?php

class DX_Seo_Search {
	
	/**
	 * Properties
	 */
	protected $prefix = 'dxseo_search';
	
	/**
	 * Hooks
	 */
	function __construct() {
		add_action( $this->prefix . '_click', array( $this, 'click' ) );
		add_action( 'wp_ajax_' . $this->prefix, array( $this, 'do_search' ) );
	}
	
	/**
	 * Click html tags
	 */
	function click() {
		$this->css();
		$this->js();
		echo '<span class="' . $this->prefix . '-click" style="color: #21759b; margin-left: 10px; cursor: pointer;">关键词建议</span>';
		echo' <img class="result-loading" src="' . plugins_url( 'loading.gif', __FILE__ ) . '"/>';
		wp_nonce_field( $this->prefix . '_action', $this->prefix . '_wpnonce' );
		$this->result();
	}
	
	/**
	 * Show search result html
	 */
	function result() {
		echo '<div class="' . $this->prefix . '-result"></div>';
	}
	
	/**
	 * Css style
	 */
	function css() {
?>
<style type="text/css">
.<?php echo $this->prefix; ?>-result { margin-top: 10px; }
.<?php echo $this->prefix; ?>-result table td{ color: #21759b; cursor: pointer; border: 1px solid #21759b; padding: 2px 10px; }
.result-loading { display: none;width: 15px; height: 15px; vertical-align: middle; }
</style>
<?php		
	}
	
	/**
	 * Js
	 */
	function js() {
?>
<script type="text/javascript">
jQuery(document).ready(function($){
	/* Ajax */
	$( '.<?php echo $this->prefix; ?>-click' ).click(function(){
		$( '.<?php echo $this->prefix; ?>-result' ).html( '' );
		$( '.result-loading' ).show();
		var nonce = $( '#<?php echo $this->prefix; ?>_wpnonce' ).val();
		var sWord = '';
		<?php 
			global $hook_suffix;
			if( 'dx-seo_page_dx_seo_meta' == $hook_suffix )
				echo 'var sWord = "' . get_bloginfo( 'name' ) . '"';
			if( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix )
				echo 'var sWord = $("#title").val()';
			if( 'edit-tags.php' == $hook_suffix )
				echo 'var sWord = $("#name").val()';
		?>		
		$.ajax({
			url: ajaxurl,
			cache: false,
			type: 'POST',
			dataType: 'html',
			data: { action: '<?php echo $this->prefix; ?>', wpnonce: nonce, word: sWord },
			success: function(res){
				$( '.result-loading' ).hide();
				$( '.<?php echo $this->prefix; ?>-result' ).html( res );
			}
		});
	});
	/* Click to input */
	$( '.dxseo-search-word' ).live('click',function(){
		var sWord = $(this).text();		
		var sTitle = $( '.seo-title' ).val();
		var sKeywords = $( '.seo-keywords' ).val(); 
		$( '.seo-title' ).val( sTitle + sWord );
		if( '' == sKeywords )
			$( '.seo-keywords' ).val( sWord );
		else
			$( '.seo-keywords' ).val( sKeywords + ',' + sWord );
	});
});
</script>
<?php
	}
	
	/**
	 * Convert string to requested character encoding
	 */
	function iconv( $str ) {
		return iconv( 'utf-8', get_bloginfo( 'charset' ), $str );
	}
	
	/**
	 * Get baidu search words
	 */
	function get_baidu( $word ) {
		$url = 'http://www.baidu.com/s?ie=utf-8&wd=' . urlencode( $word );
		$content = @file_get_contents( $url );
		if( $content ) {
			$content = $this->iconv( $content );
			if( preg_match( '/<div id="rs">[\s\S]*?<\/div>/', $content, $match ) ) {
				if( preg_match_all( '/<a.*?>(.*?)<\/a>/', $match[0], $matches ) ) {
					return $matches[1];
				}
			}
		}
		return array();
	}
	
	/**
	 * Result fomat
	 */
	function do_format( $datas ) {
		if( empty( $datas ) )
			return '';
		$str = '<table cellspacing="8">';
		$i = 0;
		foreach( $datas as $data ) {
			if( 0 == $i || 5 == $i )
				$str .= '<tr>';
			if( 0 == $i )
				$str .= '<td style="border: none;" rowspan="2" valgin="middle"><img src="' . plugins_url( 'baidu.gif', __FILE__ ) . '"/></td>';
			$str .= "<td class=\"dxseo-search-word\" align=\"center\">$data</td>";
			if( 4 == $i )
				$str .= '</tr>';
			$i++;
		}
		if( preg_match( '/<\/tr>$/', $str ) )
			$str .= '</tr>';
		$str .= '</table>';
		echo $str;
	}
	
	/**
	 * Do search for ajax
	 */
	function do_search() {
		if( ! isset( $_POST['wpnonce'] ) || ! wp_verify_nonce( $_POST['wpnonce'], $this->prefix . '_action' ) )
			die( '<p style="color: red">Error！</p>' );
		if( ! isset( $_POST['word'] ) || empty( $_POST['word'] ) )
			die( '<p style="color: red">标题或名称为空！</p>' );
		$res = $this->get_baidu( $_POST['word'] );
		if( empty( $res ) )
			die( '<p style="color: red">无查询结果！</p>' );
		$this->do_format( $res );
		die();
	}
	
}

new DX_Seo_Search;