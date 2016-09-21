<?php

class DX_Seo_Image_Attr_Help {
	
	public $tabs = array(
		 'tips' => array( '操作提示', '<ul><li>如果不需要自动修改title，留空即可。alt同理。</li></ul>' ),
		 'reference' => array( '认识title、alt属性', '<p><b>title属性</b> - 当鼠标移到图片时会提供用户该图片的主要信息，提高用户体验。<br /><b>alt属性</b> - 图片alt属性是搜索引擎唯一能识别的图片信息，因此，在优化网站时，尽可能利用alt属性阐述图片的主题内容，该图片属性作用也不仅仅告诉搜索引擎图片的主要信息，另外在图片出现加载失败时，还能够让访客理解图片所阐述的内容！</p>'),
	);
	public $sidebar = '<p>该功能可以自动给图片添加title、alt属性，免去人工添加的繁琐工作，告诉搜索引擎图片的主要信息。</p>';

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