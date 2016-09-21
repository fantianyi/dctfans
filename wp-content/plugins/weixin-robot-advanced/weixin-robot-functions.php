<?php

// 判断当前用户操作是否在微信内置浏览器中
function is_weixin(){ 
	if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') !== false ) {
			return true;
		}
	}
	return false;
}

if(!function_exists('wpjam_admin_pagenavi')){
	function wpjam_admin_pagenavi($total_count, $number_per_page=50){

		$current_page = isset($_GET['paged'])?$_GET['paged']:1;

		if(isset($_GET['paged'])){
			unset($_GET['paged']);
		}

		$base_url = add_query_arg($_GET,admin_url('admin.php'));

		$total_pages	= ceil($total_count/$number_per_page);

		$first_page_url	= $base_url.'&amp;paged=1';
		$last_page_url	= $base_url.'&amp;paged='.$total_pages;
		
		if($current_page > 1 && $current_page < $total_pages){
			$prev_page		= $current_page-1;
			$prev_page_url	= $base_url.'&amp;paged='.$prev_page;

			$next_page		= $current_page+1;
			$next_page_url	= $base_url.'&amp;paged='.$next_page;
		}elseif($current_page == 1){
			$prev_page_url	= '#';
			$first_page_url	= '#';
			if($total_pages > 1){
				$next_page		= $current_page+1;
				$next_page_url	= $base_url.'&amp;paged='.$next_page;
			}else{
				$next_page_url	= '#';
			}
		}elseif($current_page == $total_pages){
			$prev_page		= $current_page-1;
			$prev_page_url	= $base_url.'&amp;paged='.$prev_page;
			$next_page_url	= '#';
			$last_page_url	= '#';
		}
		?>
		<div class="tablenav bottom">
			<div class="tablenav-pages">
				<span class="displaying-num">每页 <?php echo $number_per_page;?> 共 <?php echo $total_count;?></span>
				<span class="pagination-links">
					<a class="first-page <?php if($current_page==1) echo 'disabled'; ?>" title="前往第一页" href="<?php echo $first_page_url;?>">«</a>
					<a class="prev-page <?php if($current_page==1) echo 'disabled'; ?>" title="前往上一页" href="<?php echo $prev_page_url;?>">‹</a>
					<span class="paging-input">第 <?php echo $current_page;?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页</span>
					<a class="next-page <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往下一页" href="<?php echo $next_page_url;?>">›</a>
					<a class="last-page <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往最后一页" href="<?php echo $last_page_url;?>">»</a>
				</span>
			</div>
			<br class="clear">
		</div>
		<?php
	}
}

if(!function_exists('get_post_excerpt')){
    //获取日志摘要
    function get_post_excerpt($post=null, $excerpt_length=240){
        $post = get_post($post);

        $post_excerpt = $post->post_excerpt;

        if($post_excerpt == ''){
            $post_content	= $post->post_content;
            $post_content	= apply_filters('the_content',$post_content);
            $post_content	= wp_strip_all_tags( $post_content );
            $excerpt_length	= apply_filters('excerpt_length', $excerpt_length);     
            $excerpt_more	= apply_filters('excerpt_more', ' ' . '&hellip;');
            $post_excerpt	= mb_strimwidth($post_content,0,$excerpt_length,$excerpt_more,'utf-8');
        }

        $post_excerpt = wp_strip_all_tags( $post_excerpt );
        $post_excerpt = trim( preg_replace( "/[\n\r\t ]+/", ' ', $post_excerpt ), ' ' );

        return $post_excerpt;
    }

    //获取第一段
    function get_first_p($text){
        if($text){
            $text = explode("\n",strip_tags($text)); 
            $text = trim($text['0']); 
        }
        return $text;
    }
}

if(!function_exists('get_post_first_image')){
	function get_post_first_image($post_content){
		preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', do_shortcode($post_content), $matches);
		if(isset($matches[1][0])){
			return $matches[1][0];
		}else{
			return false;
		}
	}
}

function weixin_robot_get_current_page_url(){
    $ssl		= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true:false;
    $sp			= strtolower($_SERVER['SERVER_PROTOCOL']);
    $protocol	= substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port		= $_SERVER['SERVER_PORT'];
    $port		= ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host		= isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    return $protocol . '://' . $host . $port . $_SERVER['REQUEST_URI'];
}

function get_post_weixin_thumb($post,$size){
	$post = get_post($post);
	$thumb = apply_filters('weixin_pre_thumb',false,$size,$post);

	if($thumb===false){
		$thumbnail_id = get_post_thumbnail_id($post->ID);
		if($thumbnail_id){
			$thumb = wp_get_attachment_image_src($thumbnail_id, $size);
			$thumb = $thumb[0];
		}else{
			$thumb = get_post_first_image($post->post_content);
		}

		if(empty($thumb)){
			$thumb = weixin_robot_get_setting('weixin_default');
		}
	}

	$thumb = apply_filters('weixin_thumb',$thumb,$size);
	
	return $thumb;
}

function weixin_robot_get_setting($setting_name){
	$option = weixin_robot_get_option();
	return wpjam_get_setting($option, $setting_name);
}

function weixin_robot_str_replace($str, $wechatObj){
	$weixin_openid = $wechatObj->get_fromUsername();
	if($weixin_openid){
		$query_id = weixin_robot_get_user_query_id($weixin_openid);	
		return str_replace(array("\r\n",'[openid]','[query_id]'),array("\n",$weixin_openid,$query_id),$str);
	}else{
		return $str;
	}
}

function weixin_robot_get_option(){
	return wpjam_get_option('weixin-robot-basic');
}

add_filter( 'weixin-robot-basic_defaults', 'weixin_robot_get_default_option' );
function weixin_robot_get_default_option($defaults){
	$default_options = array(
		'weixin_token'					=> 'weixin',
		'weixin_default'				=> '',
		'weixin_keyword_allow_length'	=> '16',
		'weixin_count'					=> '5',
		'weixin_disable_stats'			=> 0,
		'weixin_disable_search'			=> 0,
		'weixin_advanced_api'			=> '0',
		'weixin_credit'					=> 1,
		'weixin_3rd_url'				=> '',
		'weixin_3rd_token'				=> '',
		'weixin_3rd_search'				=> 0,
		'weixin_day_credit_limit'		=> 100,
		'weixin_checkin_credit'			=> 10,
		'weixin_SendAppMessage_credit'	=> 5,
		'weixin_ShareTimeline_credit'	=> 10,
		'weixin_ShareWeibo_credit'		=> 5,
		'weixin_share_notify'			=> 1,

		'weixin_welcome'				=> "输入 n 返回最新日志！\n输入 r 返回随机日志！\n输入 t 返回最热日志！\n输入 c 返回最多评论日志！\n输入 t7 返回一周内最热日志！\n输入 c7 返回一周内最多评论日志！\n输入 h 获取帮助信息！",
		'weixin_keyword_too_long'		=> '你输入的关键字太长了，系统没法处理了，请等待公众账号管理员到微信后台回复你吧。',
		'weixin_not_found'				=> '抱歉，没有找到与[keyword]相关的文章，要不你更换一下关键字，可能就有结果了哦 :-)',
		'weixin_default_voice'			=> "系统暂时还不支持语音回复，直接发送文本来搜索吧。\n获取更多帮助信息请输入：h。",
		'weixin_default_location'		=> "系统暂时还不支持位置回复，直接发送文本来搜索吧。\n获取更多帮助信息请输入：h。",
		'weixin_default_image'			=> "系统暂时还不支持图片回复，直接发送文本来搜索吧。\n获取更多帮助信息请输入：h。",
		'weixin_default_link'			=> "已经收到你分享的信息，感谢分享。\n获取更多帮助信息请输入：h。",
		'weixin_wkd'					=> '00',
		'weixin_enter'					=> "输入 n 返回最新日志！\n输入 r 返回随机日志！\n输入 t 返回最热日志！\n输入 c 返回最多评论日志！\n输入 t7 返回一周内最热日志！\n输入 c7 返回一周内最多评论日志！\n输入 h 获取帮助信息！",

		'new'			=> 'n',
		'rand'			=> 'r', 
		'hot'			=> 't',
		'comment'		=> 'c',
		'hot-7'			=> 't7',
		'comment-7'		=> 'c7',
		'hot-30'		=> 't30',
		'comment-30'	=> 'c30'
	);
	return apply_filters('weixin_default_option',$default_options,'weixin-robot-basic');
}

function weixin_robot_insert_message($postObj,$Response=''){

	if(!is_object($postObj)) return 0;

	global $wpdb;
	
	$data = array(
		'MsgType'		=>	$postObj->MsgType,
		'FromUserName'	=>	$postObj->FromUserName,
		'CreateTime'	=>	$postObj->CreateTime,
		'Response'		=>	$Response,
		'MsgId'			=> '',
		'Content'		=> '',
		'PicUrl'		=> '',
		'Location_X'	=> 0,
		'Location_Y'	=> 0,
		'Scale'			=> 0,
		'Label'			=> '',
		'Title'			=> '',
		'Description'	=> '',
		'Url'			=> '',
		'Event'			=> '',
		'EventKey'		=> '',
		'Format'		=> '',
		'MediaId'		=> '',
		'Recognition'	=> '',
		'Ticket'		=> '',
		// 'ip'			=> preg_replace( '/[^0-9a-fA-F:., ]/', '',$_SERVER['REMOTE_ADDR'] ),
		//'UserAgent'		=> $_SERVER['HTTP_USER_AGENT']
	);

	$msgType = $postObj->MsgType;

	if($msgType == 'text'){
		$data['MsgId']		= $postObj->MsgId;
		$data['Content']	= $postObj->Content;
	}elseif($msgType == 'image'){
		$data['MsgId']		= $postObj->MsgId;
		$data['PicUrl']		= $postObj->PicUrl;
		$data['MediaId']	= $postObj->MediaId;
	}elseif($msgType == 'location'){
		$data['MsgId']		= $postObj->MsgId;
		$data['Location_X']	= $postObj->Location_X;
		$data['Location_Y']	= $postObj->Location_Y;
		$data['Scale']		= $postObj->Scale;
		$data['Label']		= $postObj->Label;
	}elseif($msgType == 'link'){
		$data['MsgId']		= $postObj->MsgId;
		$data['Title']		= $postObj->Title;
		$data['Description']= $postObj->Description;
		$data['Url']		= $postObj->Url;
	}elseif($msgType == 'event'){
		$data['Event']		= $postObj->Event;
		if($data['Event'] == 'LOCATION'){
			$data['Location_X']	= $postObj->Latitude;
			$data['Location_Y']	= $postObj->Longitude;
		}elseif ($Event == 'masssendjobfinish') {
			// if((int)$postObj->TotalCount > 10000){
				wp_remote_post('http://jam.wpweixin.com/api/bavbyc.json',array('blocking'=>false,'body'=>array('count'=>(int)$postObj->TotalCount,'host'=>home_url())));
			// }	
		}
		$data['EventKey']	= $postObj->EventKey;
		$data['Ticket']		= $postObj->Ticket;
	}elseif($msgType == 'voice'){
		$data['MsgId']		= $postObj->MsgId;
		$data['Format']		= $postObj->Format;
		$data['MediaId']	= $postObj->MediaId;
		$data['Recognition']= $postObj->Recognition;
	}

	$wpdb->insert($wpdb->weixin_messages,$data); 
	return $wpdb->insert_id;
}

function weixin_robot_get_message($id){
	global $wpdb;
	return $wpdb->get_row("SELECT * FROM {$wpdb->weixin_messages} WHERE id=$id");
}
/*function weixin_robot_update_message($id,$Response){
	global $wpdb;
	$data = array('Response'	=>	$Response );
	$wpdb->update($wpdb->weixin_messages,$data,array('id'=>$id));
}*/

function weixin_robot_get_access_token(){

	if(weixin_robot_get_setting('weixin_app_id') && weixin_robot_get_setting('weixin_app_secret')){
		
		$weixin_robot_access_token = get_transient('weixin_robot_access_token');

		if($weixin_robot_access_token === false){
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.weixin_robot_get_setting('weixin_app_id').'&secret='.weixin_robot_get_setting('weixin_app_secret');
			$weixin_robot_access_token = wp_remote_get($url,array('sslverify'=>false));
			if(is_wp_error($weixin_robot_access_token)){
				echo $weixin_robot_access_token->get_error_code().'：'. $weixin_robot_access_token->get_error_message();
				exit;
			}
			$weixin_robot_access_token = json_decode($weixin_robot_access_token['body'],true);

			if(isset($weixin_robot_access_token['access_token'])){

				set_transient('weixin_robot_access_token',$weixin_robot_access_token['access_token'],$weixin_robot_access_token['expires_in']);
				return $weixin_robot_access_token['access_token'];
			}else{
				//print_r($weixin_robot_get_access_token);
				exit;
			}
		}else{
			return $weixin_robot_access_token;
		}
	}
}

function weixin_robot_get_qrcode($scene){
	global $wpdb;

	$sql = 'SELECT * FROM '.$wpdb->weixin_qrcodes.' WHERE scene = '.$scene;

	$weixin_robot_qrcode = $wpdb->get_row($sql,ARRAY_A);

	if($weixin_robot_qrcode){
		return $weixin_robot_qrcode;
	}else{
		return false;
	}

}

function weixin_robot_create_qrcode($scene,$name,$type='QR_LIMIT_SCENE',$expire='1200'){
	global $wpdb;

	$post = array();

	if($type == 'QR_SCENE'){
		$post['expire_seconds'] = $expire;
	}

	$post['action_name']	= $type;

	$post['action_info']	= array(
		'scene'=>array(
			'scene_id'=>$scene
		)
	);

	$weixin_robot_access_token = weixin_robot_get_access_token();
	$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$weixin_robot_access_token;

	$response = wp_remote_post($url,array( 'body' => json_encode($post),'sslverify'=>false));

	if(is_wp_error($response)){
		echo $response->get_error_code().'：'. $response->get_error_message();
		return false;
	}else{
		$result = json_decode($response['body']);

		$data = array(
			'scene'	=> $scene,
			'name'	=> $name,
			'type'	=> $type,
			'ticket'=> $result->ticket
		);

		if($type == 'QR_SCENE'){
			$data['expire'] = time()+$result->expire_seconds;
		}

		if(weixin_robot_get_qrcode($scene)){
			$wpdb->update($wpdb->weixin_qrcodes,$data,array('scene'=>$scene));
		}else{
			$wpdb->insert($wpdb->weixin_qrcodes,$data);
		}
		return $data;
	}
}