<?php
/*
Plugin Name: DX-Seo
Plugin URI: http://www.daxiawp.com/dx-seo-plugin.html
Description: 强大、多功能的中文seo插件。能够自定义首页、文章页、页面、分类页、标签页、自定义post type、自定义taxonomy的title、keywords、description，自动长尾关键词建议，自动文章图片title、alt属性，自动nofollow，相关文章功能，站点地图、自动锚文本等。
Version: 7.0.3
Author: 大侠WP
Author URI: http://www.daxiawp.com/dx-seo-plugin.html
Copyright: daxiawp原创插件，任何个人或组织不得擅自更改版权或者盗用代码。
*/


/**
 * Define constant
 */
define( 'DXSEO_FILE', plugin_basename( __FILE__ ) );	// Plugin basename
define( 'DXSEO_PRE', 'dxseo' );		// Plugin name prefix

/**
 * Require function files
 */
 
// Settings 
require_once( 'settings/class-settings.php' );

// Seo meta
if( get_option( 'web589seo_switch_meta' ) == 'on' )
	require_once( 'extends/meta/meta.php' );
	
// Search words
if( get_option( 'web589seo_switch_search_keywords' ) == 'on' && get_option( 'web589seo_switch_meta' ) == 'on' )
	require_once( 'extends/search-words/class-search.php' );
	
// Image attribute
if( get_option( 'web589seo_image_attr' ) == 'on' )
	require_once( 'extends/image-att/class-image-attr.php' );
	
// Nofollow
if( get_option( 'dxseo_nofollow' ) == 'on' )
	require_once( 'extends/nofollow/class-nofollow.php' );
	
// Relative
if( get_option( 'dxseo_relative' ) == 'on' ) {
	require_once( 'extends/relative/relative.php' );	
}

// Sitemap
if( get_option( 'dxseo_sitemap' ) == 'on' ) {
	require_once( 'extends/sitemap/sitemap.php' );	
}

// Anchor text
if( get_option( 'dxseo_anchor_text' ) == 'on' ) {
	require_once( 'extends/anchor-text/anchor-text.php' );
}