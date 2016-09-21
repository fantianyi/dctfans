<?php

class DX_Seo_Anchor_Help {
	
	public $tabs = array(
		 'introduce' => array( '数据说明', '<p><b>关键词</b> - 关键词是唯一值，不可重复，如果你从csv文件中导入数据，相同的关键词将覆盖。关键词不可输入&lt;&gt;&quot;等特殊字符，否则可能出现异常。</p><p><b>网址url</b> - 给关键词添加此超链接</p><p><b>添加nofollow</b> - 勾选则给该链接添加nofollow属性值，通常用于外链或者次要的内链。</p><p><b>新窗口打开</b> - 勾选则点击链接在新窗口打开页面。</p><p><b>忽略大小写</b> - 勾选则不区分英文大小写匹配，适用于含英文的关键词。</p><p><b>加粗</b> - 勾选则给关键词加粗，即添加strong标签，适用于一些重要的锚文本。</p><p><b>匹配数量</b> - 例：3表示最大匹配前3个关键词。</p><p><b>优先级</b> - 优先级数值越高的将先被匹配。例：百度 10， 百度云 100，则优先给百度云添加链接。</p>' ),
		 'operate' => array( '数据操作', '<p>插件提供强大的数据操作功能，你可以新增数据，然后对数据进行编辑、删除、排序、查找、分页等操作。<b>点击页面右上角的&quot;显示选项&quot;可以设置每页数据显示的数量。</b></p>' ),
		 'reference' => array( '认识锚文本', '<p><b>锚文本概念</b> - 和超链接类似，超链接的代码是锚文本，把关键词做一个链接，指向别的网页，这种形式的链接就叫作锚文本。</p><p><b>锚文本的作用</b> - 以锚文本建设站内链接，能提高搜索引擎的收录与网站权重。</p><p><b>扩展阅读</b> - <a href="http://baike.baidu.com/view/19075.htm" target="_blank">锚文本详解</a>， <a href="http://baike.baidu.com/view/1927625.htm" target="_blank">内链详解</a></p>'),
	);
	public $sidebar = '<p>该功能可以检索文章内容，匹配到预设的关键词时自动转化成锚文本，大大提高seo的工作效率，有效提高网站关键词权重以及排名。</p>';

	static public function init()
	{
		$class = __CLASS__ ;
		new $class;
	}

	public function __construct()
	{
		$this->add_tabs();
	}

	public function add_tabs()
	{
		$screen = get_current_screen();
		foreach ( $this->tabs as $id => $data )
		{
			$screen->add_help_tab( array(
				 'id'       => $id,
				 'title'    => $data[0],
				 'content'  => $data[1],				 
			) );
		}
		$screen->set_help_sidebar( $this->sidebar );
	}
	
}