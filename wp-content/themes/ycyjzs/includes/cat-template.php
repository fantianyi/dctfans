<?php

//add edit category form action
add_action( 'edit_category_form', '_dxwp_category_template_form' );

function _dxwp_category_template_form($tag){
	if( $_GET['tag_ID']!= 0 ){
		$templates= array(
			'default'=>'默认模板',
			'fenbu'=>'分部模板',
			'anli'=>'案例模板',
			'zhaopin'=>'招聘模板',
			'zjgd'=>'在建工地模板',
			'khxs'=>'客户心声模板',
			'zzhj'=>'资质环境模板'
		);
		$tmp_val= get_option( '_dxwp_cat_template_'.$tag->term_id );
?>

<table class="form-table">
	<tbody>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="_dxwp_cat_template">模板</label></th>
			<td>
				<select id="_dxwp_cat_template" name="_dxwp_cat_template">
					<?php foreach( $templates as $key=>$name ): ?>
					<option value="<?php echo $key; ?>" <?php selected( $key, $tmp_val ); ?>><?php echo $name; ?></option>
					<?php endforeach; ?>
				</select>
				<span class="description">选择该分类目录所使用的模板。</span>
				<input type="hidden" name="_dxwp_cat_template_on" value="yes" />
			</td>
		</tr>
	</tbody>
</table>

<?php
	}
}


//save cate template data
add_action( 'edit_category', '_dxwp_save_category_template' );

function _dxwp_save_category_template($cat_id){
	if( $_POST['_dxwp_cat_template_on']=='yes' ){
		update_option( '_dxwp_cat_template_'.$cat_id, $_POST['_dxwp_cat_template'] );
	}
}