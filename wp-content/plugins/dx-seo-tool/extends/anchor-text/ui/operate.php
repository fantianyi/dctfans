<div id="operate-wrap" class="tablenav top operate">

	<div id="batch" class="alignleft actions">
		<select name="action">
			<option value="-1">批量操作</option>
			<option value="delete">删除</option>
		</select>
		<input type="button" name="" id="do-action" class="button action do-action" value="应用">
	</div>
	
	<div id="order-wrap" class="alignright actions">
		<span>排序：</span>
		<select class="order">
			<option value="desc">降序</option>
			<option value="asc">升序</option>
		</select>
		<span>排序方式：</span>
		<select class="orderby">
			<option value="time">时间</option>
			<option value="keyword">关键词</option>
			<option value="priority">优先级</option>
		</select>		
	</div>	

	<p class="search-box">
		<label class="screen-reader-text" for="search-input">搜索:</label>
		<input type="search" id="search-input" class="search-input" name="s" value="">
		<input type="button" name="" id="search-submit" class="button search-submit" value="搜索">
	</p>
	
	<div class="tablenav-pages"></div>

	<br class="clear">
</div>