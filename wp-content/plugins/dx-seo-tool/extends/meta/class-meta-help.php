<?php

class DX_Seo_Meta_Help {
	
	public $tabs = array(
		 'tips' => array( '操作提示', '<ul><li>metas输入项可以让你十分灵活地设置meta标签，<a href="http://baike.baidu.com/view/953191.htm" target="_blank">点击阅读meta的用法</a></li><li>如果设置了静态页面，则此处的首页title、meta无效，需要在页面中设置。</li></ul>' ),
		 'reference' => array( '概念', '<p>点击阅读：<a href="http://baike.baidu.com/view/706499.htm" target="_blank">title</a>、<a href="http://baike.baidu.com/view/1195113.htm" target="_blank">keywords</a>、<a href="http://baike.baidu.com/view/538066.htm" target="_blank">description</a></p>'),
	);
	public $sidebar = '<p>该功能可以给首页、文章、页面、分类、标签、自定义post type、自定义taxonomy设置title、keywords、description标签。</p>';

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