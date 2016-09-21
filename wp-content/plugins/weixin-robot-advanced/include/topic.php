<?php

function wpjam_topic_get_openid(){
	$current_user_id= get_current_user_id();
	return get_user_meta($current_user_id, 'wpjam_openid', true);
}

function wpjam_topic_get_weixin_user(){
	if(!wpjam_topic_get_openid())	return false;

	$wpjam_weixin_user = get_transient('wpjam_weixin_user');
	if($wpjam_weixin_user === false){
		$wpjam_weixin_user = wpjam_topic_remote_request('http://jam.wpweixin.com/api/get_user.json');
		if(is_wp_error($wpjam_weixin_user)){
			return $wpjam_weixin_user;
		}
		set_transient( 'wpjam_weixin_user', $wpjam_weixin_user, DAY_IN_SECONDS*15 );	// 15天检查一次
	}

	return $wpjam_weixin_user;
}

function wpjam_topic_get_group_list(){
	return array(
		'qiniu'		=> '七牛云存储',
		'weixin'	=> '微信机器人',
		'wpjam'		=> 'WPJAM Basic',
		'wp'		=> '其他问题'
	);
}

function wpjam_topic_remote_request($url,  $args=''){

	$openid 	= wpjam_topic_get_openid();
	$headers	= ($openid)?array('openid'=>$openid):array();

	$args = wp_parse_args( $args, array(
		'method'	=> 'GET',
		'body'		=> array(),
		'timeout'	=> 10,
		'sslverify'	=> false,
		'blocking'	=> true,	// 如果不需要立刻知道结果，可以设置为 false
		'stream'	=> false,	// 如果是保存远程的文件，这里需要设置为 true
		'filename'	=> null,	// 设置保存下来文件的路径和名字
		'headers'	=> $headers
	) );

	$method	= $args['method'];
	unset($args['method']);

	if($method == 'GET'){
		$response = wp_remote_get($url, $args);
	}elseif($method == 'POST'){
		$response = wp_remote_post($url, $args);
	}

	if(is_wp_error($response)){
		return $response;
	}

	$response = json_decode($response['body'],true);

	if(isset($response['errcode']) && $response['errcode']){
		return new WP_Error($response['errcode'],$response['errmsg']);
	}

	return $response;
}


add_filter('wpjam_pages', 'wpjam_topic_admin_pages');
add_filter('wpjam_network_pages', 'wpjam_topic_admin_pages');
function wpjam_topic_admin_pages($wpjam_pages){

	$subs = array();
	if(wpjam_topic_get_openid()){
		$subs['wpjam-topics']		= array('menu_title' => '所有问答',	'function'=>'wpjam_topics_page',	'capability' => 'read');
		$subs['wpjam-topic']		= array('menu_title' => '我要提问',	'function'=>'wpjam_topic_edit_page','capability' => 'read');
	}
		
	$wpjam_pages['wpjam-topics']	= array(
		'menu_title'	=> 'WP问答',		
		'icon'			=> 'dashicons-wordpress',
		'subs'			=> $subs,
		'capability'	=> 'read'
	);

	return $wpjam_pages;
}

function wpjam_topics_page_load(){
	global $wpjam_list_table;

	if(wpjam_topic_get_openid() == false) return;

	$action	= isset($_GET['action'])?$_GET['action']:'';

	if(in_array($action, array('add','edit','set','bulk-edit','reply'))) return;

	$columns		= array(
		'title'			=> '问题',
		'user'			=> '提问者',
		'group'			=> '分组',
		'time'			=> '提问时间',
		// 'reply_count'	=> '回复',
		'last_reply'	=> '最后回复'
	);

	$style = '
	th.column-user{width:128px;}
	td.column-user img{float:left; margin-right:6px;}
	th.column-time{width:108px;}
	th.column-group{width:120px;}
	th.column-last_reply{width:138px;}
	';

	$wpjam_list_table = wpjam_list_table( array(
		'plural'			=> 'wpjam-topics',
		'singular' 			=> 'wpjam-topic',
		'item_callback'		=> 'wpjam_topic_item',
		'views'				=> 'wpjam_topic_views',
		'per_page'			=> 20,
		'columns'			=> $columns,
		'style'				=> $style,
		// 'sortable_columns'	=> array('time'=>'time','last_reply'=>'last_reply'),
	) );
}

function wpjam_topic_views($views){
	global $wpdb, $current_admin_url;
	
	$view			= isset($_GET['view']) ? $_GET['view'] : '';
	$current_group	= isset($_GET['group']) ? $_GET['group'] : '';

	$class = (empty($view) && empty($current_group)) ? 'class="current"':'';
	$views['all'] = '<a href="'.$current_admin_url.'" '.$class.'>所有提问</a>';

	$group_list = wpjam_topic_get_group_list();

	foreach ($group_list as $group => $group_name) {
		$class			= ($current_group == $group) ? 'class="current"':'';
		$views[$group]	= '<a href="'.$current_admin_url.'&group='.$group.'" '.$class.'>'.$group_name.'</a>';
	}

	$class = ($view == 'mine') ? 'class="current"':'';
	$views['mine'] = '<a href="'.$current_admin_url.'&view=mine" '.$class.'>我的提问</a>';

	return $views;
}

function wpjam_topics_page(){

	if(current_user_can('manage_options') && isset($_GET['action']) && $_GET['action'] == 'del-openid'){
		delete_user_meta( get_current_user_id(), 'wpjam_openid' );
	}

	if(wpjam_topic_get_openid()){
		$action = isset($_GET['action'])?$_GET['action']:'';

		if($action == 'reply' ){
			wpjam_topic_reply_page();
		}else{
			wpjam_topic_list_page();
		}
	}else{
		wpjam_topic_setting_page();
	}
}

function wpjam_topic_setting_page($title='', $description=''){
	global $current_admin_url;
	$form_fields = array(
		'qrcode'	=> array('title'=>'二维码',	'type'=>'view'),
		'code'		=> array('title'=>'验证码',	'type'=>'text',	'description'=>'验证码10分钟内有效！'),
		'scene'		=> array('title'=>'scene',	'type'=>'hidden'),
	);

	$nonce_action	= 'wpjam-topic';

	$current_user_id= get_current_user_id();

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
		$data		= wpjam_get_form_post($form_fields, $nonce_action, 'read');

		$response	= wpjam_topic_remote_request('http://jam.wpweixin.com/api/bind.json', array(
			'method'	=>'POST',
			'body'		=> $data
		));

		if(is_wp_error($response)){
			wpjam_admin_add_error($response->get_error_message(), 'error');
		}else{
			$wpjam_openid = $response['openid'];
			update_user_meta($current_user_id, 'wpjam_openid', $wpjam_openid);
			delete_transient('wpjam_weixin_user');
			wp_redirect($current_admin_url);
			exit;
		}
		
	}else{
		$key		= md5(home_url().'_'.$current_user_id);
		$response	= wpjam_topic_remote_request('http://jam.wpweixin.com/api/get_qrcode.json?key='.$key);

		if(is_wp_error($response)){
			wpjam_admin_add_error($response->get_error_message(), 'error');
			wpjam_admin_add_error($response->get_error_message(), 'error');
		}else{
			$qrcode = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$response['ticket'];
			$form_fields['qrcode']['value']	= '<img srcset="'.$qrcode.' 2x" src="'.$qrcode.'" style="max-width:350px;" />';
			$form_fields['scene']['value']	= $response['scene'];
		}
	}

	$form_url		= $current_admin_url;

	$title			= ($title)?$title:'WordPress 问答';
	$description	= ($description)?$description:'<p>开始提问之前，需要绑定你的微信号。<br />请使用微信扫描下面的二维码，获取验证码之后提交即可完成绑定！</p>';
	
	echo '<h1>'.$title.'</h1>';
	echo $description;
	?>
	<style type="text/css">
	.form-table { max-width:640px; }
	.form-table th {width:60px; }
	</style>
	<?php

	wpjam_form($form_fields, $form_url, $nonce_action, '提交');
}

function wpjam_topic_list_page(){
	global $wpjam_list_table,  $current_admin_url;

	echo '<h2>WP问答<a title="我要提问" class="add-new-h2" href="'.admin_url('admin.php?page=wpjam-topic').'">我要提问</a></h2>';

	$view		= isset($_GET['view']) ? $_GET['view'] : '';
	$s			= isset($_GET['s']) ? $_GET['s'] : '';
	$group		= isset($_GET['group']) ? $_GET['group'] : '';
	$paged		= isset($_GET['paged'])?$_GET['paged']:1;
	$orderby	= isset($_GET['orderby'])?$_GET['orderby']:'last_reply_time';
	$order		= isset($_GET['order'])?$_GET['order']:'DESC';

	$args		= compact('paged','view','s','group','orderby', 'order');
	$url		= add_query_arg($args, 'http://jam.wpweixin.com/api/get_topics.json');

	$wpjam_topics = wpjam_topic_remote_request($url);

	if(is_wp_error( $wpjam_topics )){
		wpjam_admin_add_error($wpjam_topics->get_error_message(),'error');
		$total	= 0;
		$topics	= array();
	}else{
		$total	= $wpjam_topics['total'];
		$topics	= $wpjam_topics['topics'];
	}

	$wpjam_list_table->prepare_items($topics, $total);
	$wpjam_list_table->display();
}

function wpjam_topic_item($item){
	global $current_admin_url;

	$group_list	= wpjam_topic_get_group_list();

	$reply_count			= ($item['reply_count'])?'（'.$item['reply_count'].'）':'';

	$item['user']			= '<img src="'.str_replace('/0', '/46', $item['user']['avatar']).'" alt="'.$item['user']['nickname'].'" width="24" />'.$item['user']['nickname'];
	$item['time']			= human_time_diff($item['time']).'前';
	$item['last_reply']		= ($item['last_reply_openid'])?$item['last_reply_user']['nickname'].' (<a href="'.$current_admin_url.'&action=reply&id='.$item['id'].'">'.human_time_diff($item['last_reply_time']).'前'.'</a>)':'';
	$item['title']			= '<a href="'.$current_admin_url.'&action=reply&id='.$item['id'].'">'.$item['title'].$reply_count.'</a>';
	$item['reply_count']	= '<a href="'.$current_admin_url.'&action=reply&id='.$item['id'].'">'.$item['reply_count'].'</a>';
	$item['group']			= ($item['group'])?'<a href="'.$current_admin_url.'&group='.$item['group'].'">'.$group_list[$item['group']].'</a>':'';
	return $item;
}

function wpjam_topic_reply_page(){
	global $current_admin_url;

	$group_list	= wpjam_topic_get_group_list();

	$topic_id = isset($_GET['id'])?$_GET['id']:'';
	if(!$topic_id)	wp_die('id 不能为空');

	$form_fields = array(
		'content'	=> array('title'=>'',	'type'=>'textarea', 'dsecription'=>''),
	);

	$nonce_action	= 'wpjam-reply';

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
		$data		= wpjam_get_form_post($form_fields, $nonce_action);
		$content	= $data['content'];

		$comment	= compact('topic_id','content');

		$response	= wpjam_topic_remote_request('http://jam.wpweixin.com/api/reply.json', array(
			'method'	=> 'POST',
			'body'		=> compact('topic_id','content'),
		));

		if(is_wp_error( $response )){
			wpjam_admin_add_error($response->get_error_message(),'error');
		}else{
			$redirect_url = $current_admin_url.'&action=reply&id='.$topic_id.'#reply-'.$response['id'];
			wp_redirect($redirect_url);
			exit;
		}
	}

	$wpjam_topic = wpjam_topic_remote_request('http://jam.wpweixin.com/api/get_topic.json?id='.$topic_id);

	if(is_wp_error( $wpjam_topic )){
		wpjam_admin_add_error($wpjam_topics->get_error_message(),'error');
	}

	?>

	<style type="text/css">
		.topic-avatar{ float:left; margin:1em 1em 0 0; }
		.topic-content, .reply-content, .replies{ max-width:640px; }
		.topic-content:after{content: "."; display: block; height: 0; clear: both; visibility: hidden;}
		.topic-content pre, .reply-content pre{ background: #eaeaea; background: rgba(0,0,0,.07); white-space: pre-wrap; word-wrap: break-word; padding:8px; }
		.topic-content code, .reply-content code{ background: none; }
		.topic-content img{max-width: 640px; }
		.topic-meta{margin: 1em 0 2em; }
		.wrap h1 a{text-decoration:none;}
		/*.wrap h3 { margin-top:30px; }*/
		ul.replies li { padding:1px 1em; margin:1em 0; background: #fff;}
		ul.replies li.alternate{background: #f9f9f9;}
		.reply-meta, .reply-content{margin: 1em 0;}
		.reply-avatar { float:left; margin-right:1em; }
		.form-table textarea { max-width:640px; margin:-1em 0; }
		.form-table th{padding:10px 0;}
	</style>

	<h1><a href="<?php echo $current_admin_url;?>">WP问答</a> / <small><a href="<?php  echo $current_admin_url.'&group='.$wpjam_topic['group'];?>"><?php echo isset($group_list[$wpjam_topic['group']])?$group_list[$wpjam_topic['group']]:$wpjam_topic['group']; ?></a></small></h1>

	<div class="topic-avatar"><img src="<?php echo str_replace('/0', '/132', $wpjam_topic['user']['avatar']); ?>" width="60" alt="<?php echo $wpjam_topic['user']['nickname'];?>" /></div>

	<h2><?php echo convert_smilies($wpjam_topic['title'])?></h2>

	<div class="topic-meta">
		<span class="topic-author"><?php echo $wpjam_topic['user']['nickname'];?></span>
		- <span class="topic-time"><?php echo human_time_diff($wpjam_topic['time']); ?>前</span>
		<?php if(time()-$wpjam_topic['time'] < 10*MINUTE_IN_SECONDS && (wpjam_topic_get_openid() == $wpjam_topic['openid']) ){?>
		- <span class="edit"><a href="<?php echo admin_url('admin.php?page=wpjam-topic').'&action=edit&id='.$topic_id; ?>">编辑</a></span> -
		<?php } ?>
	</div>

	<div class="topic-content">
		<?php echo wpautop(convert_smilies($wpjam_topic['content']));?>
		<?php if($wpjam_topic['images'] && ($images = maybe_unserialize(wp_unslash($wpjam_topic['images'])))){
			foreach ($images as $image ) {
				echo '<img src="'.$image.'" />'."\n";
			}
		}?>
		<?php echo ($wpjam_topic['modified'])?'<p><i><small>最后编辑于'.human_time_diff($wpjam_topic['modified']).'前</small></i></p>':'';?>
	</div>

	<?php if($wpjam_topic['replies']){?>
	<h3><?php echo ($wpjam_topic['reply_count'])?$wpjam_topic['reply_count'].'条回复':'暂无回复'; ?></h3>
	<ul class="replies">
		<?php foreach ($wpjam_topic['replies'] as $wpjam_reply) { $alternate = empty($alternate)?'alternate':'';?>
		<li id="reply-<?php echo $wpjam_reply['id']; ?>" class="<?php echo $alternate; ?>">
			<div class="reply-meta">
				<span class="reply-avatar"><img src="<?php echo str_replace('/0', '/132', $wpjam_reply['user']['avatar']); ?>" width="48" alt="<?php echo $wpjam_reply['user']['nickname'];?>" /></span>
				<span class="reply-author"><?php echo $wpjam_reply['user']['nickname'];?></span> - <span class="reply-time"><?php echo human_time_diff($wpjam_reply['time']); ?>前</span>
			</div>
			<div class="reply-content">
				<?php echo wpautop(convert_smilies($wpjam_reply['content']));?>
			</div>
		</li>
		<?php } ?>
	</ul>
	<?php } ?>

	<h3>我要回复</h3>

	<?php

	$form_url		= $current_admin_url.'&action=reply&id='.$topic_id;
	$action_text	= '回复';
	
	 
	wpjam_form($form_fields, $form_url, $nonce_action, $action_text);
}

function wpjam_topic_edit_page(){
	global $current_admin_url;

	$topic_id		= $id = isset($_GET['id'])?$_GET['id']:0;
	$action			= isset($_GET['action'])?$_GET['action']:'add';

	$group_list 	= wpjam_topic_get_group_list();
	$group_list 	= array_merge(array(0=>' '), $group_list);

	$group_value	= isset($_GET['group'])?$_GET['group']:'';

	$form_fields = array(
		'group'		=> array('title'=>'分组',	'type'=>'select',	'options'=>$group_list, 'value'=>$group_value),
		'title'		=> array('title'=>'标题',	'type'=>'text'),
		'content'	=> array('title'=>'内容',	'type'=>'textarea',	'rows'=>10,'description'=>'不支持 HTML 标签，代码请放入[code][/code]中。<br />尽量输入相关的地址，否则无法分析和回答你的问题。'),
		'images'	=> array('title'=>'相关图片',	'type'=>'mu-img',	'description'=>'如果文字无法描述你的问题，请添加截图。'	),
	);

	$nonce_action	= ($action == 'add')?'wpjam-topic-add':'wpjam-topic-edit-'.$topic_id;

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
		$data		= wpjam_get_form_post($form_fields, $nonce_action);
		$title		= $data['title'];
		$content	= $data['content'];
		$group		= $data['group'];

		if($images = $data['images']){
			$image_urls = array();

			foreach ($images as $image_id) {
				$image = wp_get_attachment_image_src($image_id,'full');
				$image_urls[]	= $image[0];
			}
			$images = maybe_serialize($image_urls);
		}

		$response	= wpjam_topic_remote_request('http://jam.wpweixin.com/api/topic.json', array(
			'method'	=> 'POST',
			'body'		=> compact('title','content','group','images','id'),
		));

		if(is_wp_error( $response )){
			wpjam_admin_add_error($response->get_error_message(),'error');
		}else{
			$redirect_url = admin_url('admin.php?page=wpjam-topics&views=mine').'&action=reply&id='.$response['id'];
			wp_redirect($redirect_url);
			exit;
		}
	}

	if($action == 'edit' && $topic_id){
		$wpjam_topic = wpjam_topic_remote_request('http://jam.wpweixin.com/api/get_topic.json?id='.$topic_id);
		if(is_wp_error( $wpjam_topic )){
			wpjam_admin_add_error($wpjam_topics->get_error_message(),'error');
		}else{
			if(wpjam_topic_get_openid() != $wpjam_topic['openid']){
				wp_die('你没有权限修改该贴！');
			}

			if(time() - $wpjam_topic['time'] > 10*MINUTE_IN_SECONDS){
				wp_die('超过10分钟，不能再修改！');
			}
			foreach ($form_fields as $key => $form_field) {
				$form_fields[$key]['value'] = $wpjam_topic[$key];
			}
		}
	}

	$form_url		= ($action == 'add')?$current_admin_url:$current_admin_url.'&action=edit&id='.$topic_id;
	$action_text	= ($action == 'add')?'提问':'编辑';

	?>
	<h1>我要提问</h1>
	<style type="text/css">
	.form-table { max-width:640px; }
	.form-table th {width:60px; }
	</style>
	<?php

	wpjam_form($form_fields, $form_url, $nonce_action, $action_text);
}


