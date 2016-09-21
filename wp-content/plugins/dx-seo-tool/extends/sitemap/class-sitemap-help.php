<?php

class DX_Seo_Sitemap_Help {
	
	public $tabs = array(
		 'reference' => array( '认识sitemap', '<p>Sitemap 可方便管理员通知搜索引擎他们网站上有哪些可供抓取的网页。最简单的 Sitepmap 形式，就是 XML 文件，在其中列出网站中的网址以及关于每个网址的其他元数据（上次更新的时间、更改的频率以及相对于网站上其他网址的重要程度为何等），以便搜索引擎可以更加智能地抓取网站。</p>'),
	);
	public $sidebar = '<p>该功能够自动生成xml格式的站点地图，用于指引搜索引擎快速、全面的抓取或更新网站上内容及处理错误信息。兼容百度、google、360等主流搜索引擎。。</p>';

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