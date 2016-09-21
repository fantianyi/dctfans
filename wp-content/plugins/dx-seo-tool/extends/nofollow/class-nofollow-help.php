<?php

class DX_Seo_Nofollow_Help {
	
	public $tabs = array(
		 'tips' => array( '操作提示', '<p>标签云往往造成一个页面的链接过多，建议给标签云的标签链接添加nofollow。</p>' ),
		 'reference' => array( '认识nofollow', '<p>nofollow 是一个HTML标签的属性值。这个标签的意义是告诉搜索引擎&quot;不要追踪此网页上的链接&quot;或&quot;不要追踪此特定链接&quot;。Nofollow标签的作用有两方面，简单的说，一是不给链接投票，降低此链接的权重，避免当前页面的权重流失。二是使添加nofollow的部分内容不参与网站排名，便于集中网站权重。</p>'),
	);
	public $sidebar = '<p>该功能可以给文章内容中的外链自动添加nofollow属性值，避免页面权重的流失。</p>';

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