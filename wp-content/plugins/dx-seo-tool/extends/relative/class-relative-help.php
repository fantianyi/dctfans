<?php

class DX_Seo_Relative_Help {
	
	public $tabs = array(
		 'reference' => array( '相关性', '<p>在文章内容的底部显示相关文章，提高当前文章的相关性，也可加强网站的内链建设。</p>'),
	);
	public $sidebar = '';

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