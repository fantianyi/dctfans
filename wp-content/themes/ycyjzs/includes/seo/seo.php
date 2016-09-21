<?php

//add meta menu page
add_action('admin_menu','web589SEO_meta_menu_page');
function web589SEO_meta_menu_page(){
	add_submenu_page('theme_options','meta','SEO设置','manage_options','web589_meta','web589SEO_meta_menu_page_form');
}
function web589SEO_meta_menu_page_form(){
	include_once('meta-form.php');
}


//add meta boxes
add_action('add_meta_boxes','web589_post_meta_box');
function web589_post_meta_box(){
	add_meta_box('web589_post_meta_box','自定义SEO设置','Web589Meta_postmeta_form','post','normal','high');
	add_meta_box('web589_post_meta_box','自定义SEO设置','Web589Meta_postmeta_form','page','normal','high');
}


//add meta form
function Web589Meta_postmeta_form(){
	include_once('singular-form.php');
}


//save meta
add_action('save_post','Web589Meta_save_post_meta');
function Web589Meta_save_post_meta($id){
	if($_POST['meta_save']=='on'){
		$title='title';
		$keywords='keywords';
		$description='description';
		$metas='code';		
		$val=array(
			$title=>$_POST[$title],
			$keywords=>$_POST[$keywords],
			$description=>$_POST[$description],
			$metas=>$_POST[$metas],
		);
		update_post_meta($id,'_web589_singular_meta',$val);
	}
}


//datas
function Web589Meta_datas(){
	$datas=array(
		'singular'=>array(
			'_aioseop_title',
			'_aioseop_keywords',
			'_aioseop_description',
			'_web589_head_code'
		),
	);
	return $datas;
}


//add cat metabox
add_action('edit_category_form','web589_cat_meta_box');
function web589_cat_meta_box(){
	if( isset($_GET['tag_ID']) && $_GET['tag_ID']!=0 && $_GET['taxonomy']=='category' ) include_once('cat-form.php');
}
add_action('edit_category','web589_save_cat_meta');
function web589_save_cat_meta(){	
	if( isset($_POST['action']) && isset($_POST['taxonomy']) && $_POST['action']=='editedtag' && $_POST['taxonomy']=='category' ){
		update_option('cat_meta_key_'.$_POST['tag_ID'],array('page_title'=>$_POST['cat_page_title'],'description'=>$_POST['cat_description'],'metakey'=>$_POST['cat_keywords'],'metas'=>$_POST['cat_metas']));
	}
}

//add tag metabox
add_action('edit_tag_form','web589_tag_meta_box');
function web589_tag_meta_box(){
	if( $_GET['taxonomy']=='post_tag' && $_GET['tag_ID']!=0 ) include_once('tag-form.php');
}
add_action('admin_init','web589_save_tag_meta');
function web589_save_tag_meta(){	
	if( isset($_POST['action']) && isset($_POST['taxonomy']) && $_POST['action']=='editedtag' && $_POST['taxonomy']=='post_tag' ){
		update_option('tag_meta_key_'.$_POST['tag_ID'],array('page_title'=>$_POST['tag_page_title'],'description'=>$_POST['tag_description'],'metakey'=>$_POST['tag_keywords'],'metas'=>$_POST['tag_metas']));
	}
}


//add meta action
add_action('wp_head','web589_meta_action');
function web589_meta_action(){
	$data=Web589Meta_datas();
	
	$pages=get_query_var('page');
	if( (is_single() || is_page()) && $pages<2 ){
		$id=get_the_ID();
		$switch=get_option('aioseop_options');
		$tag = '';
		$tags=get_the_tags();
		if( $tags ){
			foreach($tags as $val){
				$tag.=','.$val->name;
			}
		}
		$tag=ltrim($tag,',');
		$meta=get_post_meta($id,'_web589_singular_meta',true);
		$key_meta= isset($meta['keywords']) ? $meta['keywords'] : '';
		$des_meta=isset($meta['description']) ? $meta['description'] : '';
		$pts=get_post($id);
		$pt=preg_replace('/\s+/','',strip_tags($pts->post_content));
		$num = isset( $switch['web589_auto_description_num'] ) ? (int) $switch['web589_auto_description_num'] : 0;
		$excerpt=mb_strimwidth($pt,0,$num, '', get_bloginfo( 'charset' ) );
		
		if( empty($key_meta) && $switch['web589_auto_keywords']=='on' && isset($tag) ) $keywords=$tag;
		else $keywords=$key_meta;
		if( empty($des_meta) && $switch['web589_auto_description']=='on') $description=$excerpt;
		else $description=$des_meta;
		if($keywords){	
			echo '<meta name="keywords" content="'.$keywords.'" />';
			echo "\n";
		}
		if($description){	
			echo '<meta name="description" content="'.esc_attr($description).'" />';
			echo "\n";
		}
	}
	
	if( (is_home() || is_front_page()) && !is_paged() ){
		$val=get_option('aioseop_options');
		$keywords=$val['aiosp_home_keywords'];
		$description=$val['aiosp_home_description'];
		$metas=$val['aiosp_home_metas'];
		if($keywords){	
			echo '<meta name="keywords" content="'.$keywords.'" />';
			echo "\n";
		}
		if($description){
			echo '<meta name="description" content="'.esc_attr(stripslashes($description)).'" />';
			echo "\n";
		}	
	}
	
	if(is_category() && !is_paged()){
		$cat_id=get_query_var('cat');
		$val=get_option('cat_meta_key_'.$cat_id);
		$keywords=$val['metakey'];
		$description=$val['description'];
		$metas=$val['metas'];
		if($keywords){
			echo '<meta name="keywords" content="'.$keywords.'" />';
			echo "\n";
		}
		if($description){
			echo '<meta name="description" content="'.esc_attr(stripslashes($description)).'" />';
			echo "\n";
		}
	}
	
	if(is_tag() && !is_paged()){
		$tag_id=get_query_var('tag_id');
		$val=get_option('tag_meta_key_'.$tag_id);
		$keywords=$val['metakey'];
		$description=$val['description'];
		$metas=$val['metas'];
		if($keywords){
			echo '<meta name="keywords" content="'.$keywords.'" />';
			echo "\n";
		}
		if($description){
			echo '<meta name="description" content="'.esc_attr(stripslashes($description)).'" />';
			echo "\n";
		}	
	}	
}

//wp title filter
add_filter( 'wp_title', 'dxseo_title_filter', 9999, 2 );
function dxseo_title_filter( $title, $sep ){
	global $paged, $page, $post;
	$option = get_option( 'aioseop_options' );
	$data = Web589Meta_datas();
	$sep = isset($option['dxseo_title_sep']) ? $option['dxseo_title_sep'] : ' | ';
	if( is_single() || is_page() ){
		$meta=get_post_meta($post->ID,'_web589_singular_meta',true);
		$title = ( isset($meta['title']) && !empty($meta['title']) ) ? $meta['title'] : get_the_title( $post->ID );
	}
	else if( is_home() || is_front_page() ){
		$title = ( isset($option['aiosp_home_title']) && !empty($option['aiosp_home_title'])) ? $option['aiosp_home_title'] : get_bloginfo('name').$sep.get_bloginfo('description');
	}
	else if(is_category()){
		$cat_id=get_query_var('cat');
		$val=get_option('cat_meta_key_'.$cat_id);
		$title = ( isset($val['page_title']) && !empty($val['page_title']) ) ? $val['page_title'] : get_cat_name($cat_id);
	}
	else if(is_tag()){
		$tag_id=get_query_var('tag_id');
		$val=get_option('tag_meta_key_'.$tag_id);
		$title = ( isset($val['page_title']) && !empty($val['page_title']) ) ? $val['page_title'] : single_tag_title( '', false );
	}
	else return $title;
	if( isset($option['dxseo_title_suffix']) && $option['dxseo_title_suffix']=='on' && !is_home() && !is_front_page() )
		$title .= $sep.get_bloginfo( 'name' );
	if ( ( $paged >= 2 || $page >= 2 ) && isset($option['dxseo_title_paged']) && $option['dxseo_title_paged']=='on' )
		$title = $title.$sep.sprintf( '第 %s 页', max( $paged, $page ) );
	$tailed = isset($option['dxseo_title_tail']) ? $option['dxseo_title_tail'] : '';
	return $title.$tailed;
}


//add wp_head action
add_action('wp_head','web589_custom_code');
function web589_custom_code(){
	if( is_single() || is_page() ){
		$meta=get_post_meta(get_the_ID(),'_web589_singular_meta',true);
		if( isset($meta['code']) && $meta['code'] ){
			echo $meta['code']."\n";
		}
	}
	if( is_home() || is_front_page() ){
		$val=get_option('aioseop_options');
		$metas=$val['aiosp_home_metas'];
		if( isset($metas) && $metas ){
			echo stripslashes($metas);
			echo "\n";	
		}
	}
	if(is_category()){
		$cat_id=get_query_var('cat');
		$val=get_option('cat_meta_key_'.$cat_id);
		$metas=$val['metas'];
		if( isset( $metas ) && $metas){
			echo stripslashes($metas);
			echo "\n";	
		}
	}
	if(is_tag()){
		$tag_id=get_query_var('tag_id');
		$val=get_option('tag_meta_key_'.$tag_id);
		$metas=$val['metas'];
		if( isset($metas) && $metas ){
			echo stripslashes($metas);
			echo "\n";	
		}
	}
}
