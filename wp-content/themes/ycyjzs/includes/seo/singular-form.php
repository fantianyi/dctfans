<?php
	$id=get_the_ID();
	$val=get_post_meta($id,'_web589_singular_meta',true);	
	$title='title';
	$keywords='keywords';
	$description='description';
	$metas='code';
?>

<form action="" method="post">

<p>
	<label for="<?php echo $title; ?>_meta" style="padding-right:35px;"><b>Title</b></label>
    <?php $val_title = isset($val['title']) ? $val['title'] : ''; ?>
	<input type="text" name="<?php echo $title; ?>" id="<?php echo $title; ?>_meta" value="<?php echo esc_attr($val_title); ?>" size="80" />
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
					s_word:$('#title').val(),
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

<p style="clear:both">
	<label for="<?php echo $keywords; ?>" style="padding-right:15px;"><b>Keywords</b></label>
    <?php $val_keywords = isset($val['keywords']) ? $val['keywords'] : ''; ?>
	<input type="text" name="<?php echo $keywords; ?>" id="<?php echo $keywords; ?>" value="<?php echo $val_keywords; ?>" size="80" />
</p>
<p>
	<label for="<?php echo $description; ?>" style="display:block;padding-bottom:10px;" ><b>Description</b></label>
    <?php $val_description = isset($val['description']) ? $val['description'] : ''; ?>
	<textarea name="<?php echo $description; ?>" id="<?php echo $description; ?>" cols="92" rows="3"><?php echo $val_description; ?></textarea>
</p>
<p>
	<label for="<?php echo $metas; ?>" style="display:block;padding-bottom:10px;"><b>Metas</b></label>
    <?php $val_code = isset($val['code']) ? $val['code'] : ''; ?>
	<textarea name="<?php echo $metas; ?>" id="<?php echo $metas; ?>" cols="92" rows="5"><?php echo $val_code; ?></textarea>
</p>
	<input type="hidden" name="meta_save" value="on"/>
</form>