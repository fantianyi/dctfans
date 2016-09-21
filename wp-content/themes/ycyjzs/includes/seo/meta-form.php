<?php
	
	$title = 'aiosp_home_title';
	$keywords = 'aiosp_home_keywords';
	$description = 'aiosp_home_description';
	$metas = 'aiosp_home_metas';	
	$auto_des = 'web589_auto_description';
	$auto_des_num = 'web589_auto_description_num';
	$tag = 'web589_auto_keywords';
	$suffix = 'dxseo_title_suffix';
	$page_num = 'dxseo_title_paged';
	$sep = 'dxseo_title_sep';
	
	if( isset($_POST['update_index_meta']) && $_POST['update_index_meta'] ){
		$val=array(
			$title => isset($_POST[$title]) ? $_POST[$title] : '',
			$keywords => isset($_POST[$keywords]) ? $_POST[$keywords] : '',
			$description => isset($_POST[$description]) ? $_POST[$description] : '',
			$metas => isset($_POST[$metas]) ? $_POST[$metas] : '',
			$auto_des => isset($_POST[$auto_des]) ? $_POST[$auto_des] : '',
			$auto_des_num => isset($_POST[$auto_des_num]) ? $_POST[$auto_des_num] : '',
			$tag => isset($_POST[$tag]) ? $_POST[$tag] : '',
			$sep => isset($_POST[$sep]) ? $_POST[$sep] : '',
			$suffix => isset($_POST[$suffix]) ? $_POST[$suffix] : '',
			$page_num => isset($_POST[$page_num]) ? $_POST[$page_num] : ''
		);
		update_option('aioseop_options',$val);
	}
	
?>

<style type="text/css">
	#container{width:688px;background-color:#f7f7f7;border:solid 1px #ddd; float:left;padding:20px; }
	.hidden{display:none;}
	h3{background-color:#CCC;padding:5px;}
	#function{margin-top:-7px;}
	textarea{width:600px;height:50px;}
	.wrap h2{margin-bottom:20px;}
</style>

<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.on:radio').click(function(){
			$(this).nextAll('.toggle').fadeIn('slow');
		});
		$('.off:radio').click(function(){
			$(this).nextAll('.toggle').fadeOut('slow');
		});		
	});
</script>

<div class="wrap">

	<div id="icon-options-general" class="icon32"><br></div><h2>SEO META 选项</h2>
	
	<div id="container">
	<form action="" method="post">
		<div id="feature">
			<h3 id="function">功能</h3>
			<?php $value=get_option('aioseop_options');?>
				<p>
					<label for="<?php echo $auto_des; ?>">自动获取文章内容作为文章description标签：</label>
					<input class="on" type="radio" name="<?php echo $auto_des; ?>" id="<?php echo $auto_des; ?>" value="on" <?php echo ($value[$auto_des]=='on') ? 'checked' : ''; ?>/>开启 
					<input class="off" type="radio" name="<?php echo $auto_des; ?>" id="<?php echo $auto_des; ?>" value="off" <?php echo ($value[$auto_des]=='off') ? 'checked' : ''; ?>/>关闭 
					<label class="toggle <?php echo ($value[$auto_des]=='off') ? 'hidden' : ''; ?>" for="<?php echo $auto_des_num; ?>" >字节数：</label>
					<input class="toggle" <?php echo ($value[$auto_des]=='off') ? 'hidden' : ''; ?> type="text" name="<?php echo $auto_des_num; ?>" id="<?php echo $auto_des_num; ?>" value="<?php echo $value[$auto_des_num]; ?>" size="20" />
				</p>
				<p>
					<label for="<?php echo $tag; ?>">自动获取文章标签作为文章keyword标签：</label>
					<input type="radio" name="<?php echo $tag; ?>" id="<?php echo $tag; ?>" value="on" <?php echo ($value[$tag]=='on') ? 'checked' : ''; ?>/>开启 
					<input type="radio" name="<?php echo $tag; ?>" id="<?php echo $tag; ?>" value="off" <?php echo ($value[$tag]=='off') ? 'checked' : ''; ?>/>关闭 
				</p>              
 				<p>
					<label for="<?php echo $suffix; ?>">内页title添加站点标题后缀：</label>
                    <?php $val_suffix = isset($value[$suffix]) ? $value[$suffix] : ''; ?>
					<input type="checkbox" name="<?php echo $suffix; ?>" id="<?php echo $suffix; ?>" value="on" <?php checked( $val_suffix,'on' ); ?>/> 开启
				</p>
  				<p>
					<label for="<?php echo $page_num; ?>">title添加分页后缀：</label>
                    <?php $val_page_num = isset($value[$page_num]) ? $value[$page_num] : ''; ?>
					<input type="checkbox" name="<?php echo $page_num; ?>" id="<?php echo $page_num; ?>" value="on" <?php checked( $val_page_num,'on' ); ?>/> 开启
				</p>
 				<p>
					<label for="<?php echo $sep; ?>">title后缀分隔符：</label>
                    <?php $val_sep = isset($value[$sep]) ? $value[$sep] : ' | '; ?>
					<input type="text" name="<?php echo $sep; ?>" id="<?php echo $sep; ?>" value="<?php echo $val_sep; ?>"/>
                    <span style="color:gray;"> 若勾选后缀则使用此分隔符。</span>
				</p>                                                          						
		</div>
	
		<div id="meta">
			<h3>首页meta</h3>
			
			<p>
				<label for="<?php echo $title; ?>" style="padding-right:35px;"><b>Title</b></label>
				<input type="text" name="<?php echo $title; ?>" id="<?php echo $title; ?>" value="<?php echo esc_attr(stripslashes($value[$title])); ?>" size="80" />
				<?php if(get_option('web589SEO_switch_search_keywords')=='on'): ?><a id="search_button">关键词建议</a><?php endif;?>
			</p>
			
			<?php if(get_option('web589SEO_switch_search_keywords')=='on'): ?>
			<style type="text/css">
				#search img{margin-right:10px;}
				td.baidu_word{background-color:#f7f7f7; color:#06266F; border:1px solid #6A94D4; cursor:pointer; font-size:12px; text-align:center;}
				td.google_word{background-color:#f7f7f7; color:#007e0c; border:1px solid #007e0c; cursor:pointer; font-size:12px; text-align:center;}
				#search_button{cursor:pointer; margin-left:5px;}
			</style>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$('#search_button').click(function(){
						$('#search').css('display','block');
						$('#search').html('<img src="<?php echo plugins_url('/extensions/2.0/loading.gif',__FILE__);?>" width="15" height="15" />');
						$.post(
							'<?php echo plugins_url('/extensions/2.0/keywords_ajax.php',__FILE__);?>',
							{
								s_word:'<?php echo get_bloginfo('name'); ?>',
								s_page:'home',
								s_charset:"<?php bloginfo('charset')?>",
								s_baidu:'<?php echo plugins_url('/extensions/2.0/baidu.gif',__FILE__)?>',
								s_google:'<?php echo plugins_url('/extensions/2.0/google.png',__FILE__)?>'
							},
							function(data){
								$('#search').html(data);
							}
						);
					});
				});
			</script>
			<table id="search" style="display:none;"></table>
			<?php endif;?>			
			
			<p>
				<label for="<?php echo $keywords; ?>" style="padding-right:15px;"><b>Keywords</b></label>
				<input type="text" name="<?php echo $keywords; ?>" id="<?php echo $keywords; ?>" value="<?php echo $value[$keywords]; ?>" size="80" />
			</p>
			<p>
				<label for="<?php echo $description; ?>" style="display:block;padding-bottom:10px;" ><b>Description</b></label>
				<textarea name="<?php echo $description; ?>" id="<?php echo $description; ?>" ><?php echo stripslashes($value[$description]); ?></textarea>
			</p>
			<p>
				<label for="<?php echo $metas; ?>" style="display:block;padding-bottom:10px;"><b>Metas</b></label>
				<textarea name="<?php echo $metas; ?>" id="<?php echo $metas; ?>" ><?php echo stripslashes($value[$metas]); ?></textarea>
				<div style="color:gray;">自定义meta标签，例：&lt;meta name=&quot;copyright&quot; content=&quot;wordpress&quot; /&gt;</div>
			</p>
				<input type="hidden" name="meta_save" value="on"/>
				<?php submit_button('更新','primary','update_index_meta');?>
		</div>
	</form>
	</div>
	
	<div style="float:left;width:200px;margin-left:20px;border: 1px solid #DDD;background-color: #F7F7FF;padding:10px;display:none;">
		<?php do_action('web589_admin_side_show');?>
	</div>

</div>

<div style="clear:both;"></div>

<?php do_action( 'DXSEO_form_bottom' ); ?>