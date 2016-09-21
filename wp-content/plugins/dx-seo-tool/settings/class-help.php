<?php

class DX_Seo_Help {
	
	public $tabs = array(
		 'operate' => array( '操作提示', '<ul><li>在全局选项中根据自己的情况开启各功能模块，保存后将显示对应模块的子菜单选项，使用十分方便。</li><li>开启关键词建议功能则在title出现&quot;关键词建议&quot;文本按钮</li></ul>' ),
		 'reference' => array( '认识seo', '<p>SEO（Search Engine Optimization），汉译为搜索引擎优化。搜索引擎优化是一种利用搜索引擎的搜索规则来提高目的网站在有关搜索引擎内的排名的方式。SEO目的理解是：为网站提供生态式的自我营销解决方案，让网站在行业内占据领先地位，从而获得品牌收益。SEO可分为站外SEO和站内SEO两种。<br /><a href="http://baike.baidu.com/view/1047.htm" target="_blank">点击阅读什么是seo</a></p>'),
	);
	public $sidebar = '<p>dx-seo插件是一款强大的多功能插件，能够有效提高网站的权重以及排名。</p>';

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