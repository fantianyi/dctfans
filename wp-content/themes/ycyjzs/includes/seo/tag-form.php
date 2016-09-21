<?php 
$title='tag_page_title';
$key='tag_keywords';
$des='tag_description';
$metas='tag_metas';
$val=get_option('tag_meta_key_'.$_GET['tag_ID']);
?>
<h2>标签Meta</h2>
<table class="form-table">
	<tbody>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo $title ?>">Title:</label></th>
			<td><input name="<?php echo $title ?>" id="<?php echo $title ?>" type="text" value="<?php echo esc_attr(stripslashes($val['page_title'])); ?>" size="40"></td>
		</tr>
		
		<tr class="form-field">
			<td><?php if(get_option('web589SEO_switch_search_keywords')=='on'): ?><a id="search_button">关键词建议</a><?php endif;?></td>
			<?php if(get_option('web589SEO_switch_search_keywords')=='on'): ?>
			<style type="text/css">
				#search img{margin-right:10px;}
				td.baidu_word{background-color:#f7f7f7; color:#06266F; border:1px solid #6A94D4; cursor:pointer; font-size:12px; text-align:center; padding:0;}
				td.google_word{background-color:#f7f7f7; color:#007e0c; border:1px solid #007e0c; cursor:pointer; font-size:12px; text-align:center; padding:0;}
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
								s_word:$('#name').val(),
								s_page:'tag',
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
			<td><table id="search" style="display:none;"></table></td>
			<?php endif;?>			
		</tr>		
		
		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo $key; ?>">Keywords</label></th>
			<td><input name="<?php echo $key; ?>" id="<?php echo $key; ?>" type="text" value="<?php echo $val['metakey']; ?>" size="40"></td>
		</tr>		
		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo $des; ?>">Description</label></th>
			<td><textarea name="<?php echo $des; ?>" id="<?php echo $des; ?>" rows="5" cols="50" style="width: 97%;"><?php echo stripslashes($val['description']); ?></textarea></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="<?php echo $metas; ?>">Metas</label></th>
			<td><textarea name="<?php echo $metas; ?>" id="<?php echo $metas; ?>" rows="5" cols="50" style="width: 97%;"><?php echo stripslashes($val['metas']); ?></textarea></td>
		</tr>	
	</tbody>
</table>