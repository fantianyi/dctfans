<div id="port-wrap" class="menu-wrap">
	
	<h3>导出</h3>
	<p class="description">你可以将数据导出生成csv文件，然后用Excel等软件编辑修改，或者导出到其它站点。</p>
	<p class="export">
		<input type="button" id="anchor-export" class="button" value="导出csv文件"/>
		<span class="message"></span>
	</p>
	
	<h3>导入</h3>
	<p class="description">
		你可以将Excel等软件生成的csv文件直接导入生成数据，或者导入其它站点的数据。
		<br />
		csv文件需严格按格式输入，<a href="<?php echo plugins_url( 'example.csv', dirname( __FILE__ ) ); ?>" target="_blank">点击下载示例文件</a>。
	</p>
	<p class="import">
		<input type="button" id="anchor-import" class="button" value="导入csv文件"/>
		<span class="message"><?php if( ! function_exists( 'wp_enqueue_media' ) ): ?><span style="color:red;">导入功能仅支持3.5+版本！</span><?php endif; ?></span>
		<input type="hidden" id="anchor-import-url"/>
	</p>
	
	<h3>提示</h3>
	<ol>
		<li class="description">链接属性nofollow、新窗口打开、忽略大小写、加粗的值为1代表是，0代表否。</li>
		<li class="description">导入的数据将与原数据比较，如果关键词相同则替换，没有的关键词将新增。</li>
	</ol>
	
</div>