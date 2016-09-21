<div id="datas-wrap">

	<table class="wp-list-table widefat fixed datas" cellspacing="0">
		
		<thead>
			<tr>
				<th scope="col" id="cb" class="manage-column column-cb" style=""><label class="screen-reader-text" for="cb-select-all-1">全选</label><input id="cb-select-all" type="checkbox"></th>
				<th scope="col" id="serial" class="manage-column" style=""><span>序号</span><span class="sorting-indicator"></span></th>
				<th scope="col" id="keyword" class="manage-column" style=""><span>关键词</span><span class="sorting-indicator"></span></th>
				<th scope="col" id="url" class="manage-column" style="">网址url</th>
				<th scope="col" id="att" class="manage-column" style="">链接属性</th>
				<th scope="col" id="priority" class="manage-column" style=""><span>优先级</span><span class="sorting-indicator"></span></th>
			</tr>
		</thead>
			
		<tfoot>	</tfoot>
			
		<tr id="loading-wrap"><td colspan="6" align="center"><img class="loading" src="<?php echo plugins_url( 'loading.gif', __FILE__ ); ?>"/></td></tr>	
		<tbody id="the-list"></tbody>
			
	</table>

</div>