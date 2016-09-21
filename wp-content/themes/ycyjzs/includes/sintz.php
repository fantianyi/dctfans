<?php

//文章信息拓展

$new_meta_boxes =
array(
	"sjsmz" => array(
        "name" => "sjsmz",
        "std" => "",
        "title" => "【设计师类】设计师名字"),
	"sjszw" => array(
        "name" => "sjszw",
        "std" => "",
        "title" => "【设计师类】设计师职位"),
	"sjssfbz" => array(
        "name" => "sjssfbz",
        "std" => "",
        "title" => "【设计师类】设计师收费标准"),
	"sjslx" => array(
        "name" => "sjslx",
        "std" => "",
        "title" => "【设计师类】设计师联系方式"),
	"sjsln" => array(
        "name" => "sjsln",
        "std" => "",
        "title" => "【设计师类】设计师理念"),
	"fwlp" => array(
        "name" => "fwlp",
        "std" => "",
        "title" => "【设计师类】服务过的楼盘"),
	"sjszy" => array(
        "name" => "sjszy",
        "std" => "",
        "title" => "【设计师类】设计师主页"),
	"anlizz" => array(
        "name" => "anlizz",
        "std" => "",
        "title" => "【案例类】案例作者"),
	"didian" => array(
        "name" => "didian",
        "std" => "",
        "title" => "【招聘类】工作地点:（例：盐城）"),
	"didian" => array(
        "name" => "didian",
        "std" => "",
        "title" => "【招聘类】工作地点:（例：盐城）"),
	"didian" => array(
        "name" => "didian",
        "std" => "",
        "title" => "【招聘类】工作地点:（例：盐城）"),
    "didian" => array(
        "name" => "didian",
        "std" => "",
        "title" => "【招聘类】工作地点:（例：盐城）"),
	"renshu" => array(
        "name" => "renshu",
        "std" => "",
        "title" => "【招聘类】招聘人数:（例：2）"),
	"beizhu" => array(
        "name" => "beizhu",
        "std" => "",
        "title" => "【招聘类】备注要求:（填写招聘的要求）"),
	"zjgdkh" => array(
        "name" => "zjgdkh",
        "std" => "",
        "title" => "【在建工地】客户名："),
	"zjgdmj" => array(
        "name" => "zjgdmj",
        "std" => "",
        "title" => "【在建工地】设计面积："),
	"zjgdjl" => array(
        "name" => "zjgdjl",
        "std" => "",
        "title" => "【在建工地】项目经理："),
	"zjgdjd" => array(
        "name" => "zjgdjd",
        "std" => "",
        "title" => "【在建工地】阶段："),
);

function new_meta_boxes() {
    global $post, $new_meta_boxes;

    foreach($new_meta_boxes as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'].'_value', true);

        if($meta_box_value == "")
            $meta_box_value = $meta_box['std'];

        echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';

        // 自定义字段标题
        echo'<h4>'.$meta_box['title'].'</h4>';

        // 自定义字段输入框
        echo '<textarea cols="60" rows="3" name="'.$meta_box['name'].'_value">'.$meta_box_value.'</textarea><br />';
    }
}

function create_meta_box() {
    global $theme_name;

    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'new-meta-boxes', '文章拓展信息', 'new_meta_boxes', 'post', 'normal', 'high' );
    }
}

function save_postdata( $post_id ) {
    global $post, $new_meta_boxes;

    foreach($new_meta_boxes as $meta_box) {
        if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) ))  {
            return $post_id;
        }

        if ( 'page' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ))
                return $post_id;
        } 
        else {
            if ( !current_user_can( 'edit_post', $post_id ))
                return $post_id;
        }

        $data = $_POST[$meta_box['name'].'_value'];

        if(get_post_meta($post_id, $meta_box['name'].'_value') == "")
            add_post_meta($post_id, $meta_box['name'].'_value', $data, true);
        elseif($data != get_post_meta($post_id, $meta_box['name'].'_value', true))
            update_post_meta($post_id, $meta_box['name'].'_value', $data);
        elseif($data == "")
            delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_box['name'].'_value', true));
    }
}

add_action('admin_menu', 'create_meta_box');
add_action('save_post', 'save_postdata');

?>