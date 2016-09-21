<?php 
/*
Plugin Name: auto-save-images
Description: 自动保持远程图片到本地，并且自动生成缩略图。
Version: 1.0.1
*/

class DX_Auto_Save_Images{

	function __construct(){		
		
		//filter and action hook
		add_filter( 'content_save_pre',array($this,'post_save_images') );	//save images
		add_action( 'admin_menu', array( $this, 'menu_page' ) );		//menu page
		add_filter( 'intermediate_image_sizes_advanced', array( $this, 'remove_tmb' ) );	//remove tmb
		add_action( 'submitpost_box', array( $this, 'submit_box' ) );	//submit_box
		add_action( 'submitpage_box', array( $this, 'submit_box' ) );	//submit_box
	}
	
	//save post exterior images
	function post_save_images( $content ){
		if( ($_POST['save'] || $_POST['publish']) && ($_POST['DS_switch']!='not_save') ){
			set_time_limit(240);
			global $post;
			$post_id=$post->ID;
			$preg=preg_match_all('/<img.*?src="(.*?)"/',stripslashes($content),$matches);
			if($preg){
				$i = 1;
				foreach($matches[1] as $image_url){
					if(empty($image_url)) continue;
					$pos=strpos($image_url,get_bloginfo('url'));
					if($pos===false){
						$res=$this->save_images($image_url,$post_id,$i);
						$replace=$res['url'];
						$content=str_replace($image_url,$replace,$content);
					}
					$i++;
				}
			}
		}
		remove_filter( 'content_save_pre', array( $this, 'post_save_images' ) );
		return $content;
	}
	
	//save exterior images
	function save_images($image_url,$post_id,$i){
		$file=file_get_contents($image_url);
		$filename=basename($image_url);
		$options = get_option( 'dx-auto-save-images-options' );
		if( $options['chinese']=='yes' ){
		  preg_match( '/(.*?)(\.\w+)$/', $filename, $match );
		  $im_name = md5($match[1]).$match[2];		
		}
		else $im_name = $filename;
		$res=wp_upload_bits($im_name,'',$file);
		$attach_id = $this->insert_attachment($res['file'],$post_id);
		if( $options['post-tmb']=='yes' && $i==1 ){
			set_post_thumbnail( $post_id, $attach_id );
		}
		return $res;
	}
	
	//insert attachment
	function insert_attachment($file,$id){
		$dirs=wp_upload_dir();
		$filetype=wp_check_filetype($file);
		$attachment=array(
			'guid'=>$dirs['baseurl'].'/'._wp_relative_upload_path($file),
			'post_mime_type'=>$filetype['type'],
			'post_title'=>preg_replace('/\.[^.]+$/','',basename($file)),
			'post_content'=>'',
			'post_status'=>'inherit'
		);
		$attach_id=wp_insert_attachment($attachment,$file,$id);
		$attach_data=wp_generate_attachment_metadata($attach_id,$file);
		wp_update_attachment_metadata($attach_id,$attach_data);
		return $attach_id;
	}
	
	//menu page
	function menu_page(){
		add_menu_page( 'DX-auto-save-images','自动保存远程图片', 'manage_options', 'DX-auto-save-images', array( $this, 'options_form' ), plugins_url( 'icon.png', __FILE__ ) );
	}
	
	//options form
	function options_form(){
		$options = $this->save_options();
		include( 'options-form.php' );
	}
	
	//form bottom action
	function form_bottom(){
?>	
<?php
	}
	
	//save options
	function save_options(){
		if( $_POST['submit'] ){
			$data=array(
				'tmb' => $_POST['tmb'],
				'chinese' => $_POST['chinese'],
				'switch' => $_POST['switch'],
				'post-tmb' => $_POST['post-tmb']
			);
			update_option( 'dx-auto-save-images-options', $data );
		}
		return get_option( 'dx-auto-save-images-options' );
	}
	
	//remove tmb
	function remove_tmb( $sizes ){
		$options = get_option( 'dx-auto-save-images-options' );
		if( $options['tmb']=='yes' ){
			$sizes = array();
		}
		return $sizes;
	}
	
	//get_sample_permalink_html
	function submit_box(  ){
		$options = get_option( 'dx-auto-save-images-options' );
		if( $options['switch'] == 'yes' ){
			echo '<span style="padding-bottom:5px;display:inline-block;"><input type="checkbox" name="DS_switch" value="not_save"/> 不保存远程图片.</span>';
		}
	}

}

//new
new DX_Auto_Save_Images();