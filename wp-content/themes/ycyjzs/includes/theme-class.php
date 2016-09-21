<?php

class DX_Eblr{
	
	//hook
	function __construct(){
		add_action( 'init', array( $this, 'register_sidebars' ) );	//sidebar
		add_action( 'init', array( $this, 'register_tmb' ) );		//thumbnail
		add_filter( 'excerpt_length', array( $this, 'excerpt_length' ) );		//excerpt length
		add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );			//excerpt more
		add_action( 'init', array( $this, 'register_nav_menus' ) );		//nav menus
		add_filter( 'wp_title', array( $this, 'theme_title' ), 10, 3 );		//theme title
	}
	
	//register nav menus
	function register_nav_menus(){
		register_nav_menus( array(
			'main'=>'主导航',
			'remen'=>'热门关键词',
			'dibu'=>'底部导航(最多十个)',
			'fengge'=>'按风格分类',
			'huxing'=>'按户型分类',
			'mianji'=>'按面积分类',
			'kongjian'=>'按空间分类',
			'pagecl'=>'所有页面的侧栏',
			'newscl'=>'所有新闻栏目的侧栏',
			'fenbucl'=>'所有分部栏目的侧栏',
			'tdflcl'=>'所有团队分类的侧栏',
			'anlicl'=>'所有案例的侧栏',
			'fenggedxx'=>'风格的多选项',
			'huxingdxx'=>'户型的多选项',
			'mianjidxx'=>'面积的多选项',
			'kjdxx'=>'空间的多选项',
			'sgtjcl'=>'施工图解的侧栏',
			'fwlccl'=>'服务流程的侧栏',
			'zhaoscl'=>'招商加盟的侧栏',
		) );
	}	
	
	//register sidebar
	function register_sidebars(){
		$arg1 = array(
			'id' => 'left-sidebar',
			'name' => '左侧边栏',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>'
		);
		$arg2 = array(
			'name'          => '底部 %d',
			'id'            => "bottom",
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => '</h4>'
		);
		register_sidebar( $arg1 );
		register_sidebars( 4, $arg2 );
	}
	
	//excerpt length
	function excerpt_length( $len ){
		return 180;
	}
	
	//excerpt more
	function excerpt_more( $more ){
		return ' ... ';
	}	
	
	//register post thumbnail
	function register_tmb(){
		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' );
		}	
	}
	
	//get post thumbnail
	function tmb( $size="thumbnail" ){
		global $post;
		$args=array('post_type'=>'attachment','post_mime_type'=>'image','post_parent'=>$post->ID,'order'=>'asc');
		$images=get_children($args);
		if(has_post_thumbnail()) return get_the_post_thumbnail($post->ID,$size);
		else if($images){
			$attachment_id=key($images);
			return wp_get_attachment_image($attachment_id,$size);
		}
		else {
			$preg="/(<img )([^>]*)(>)/"; 
			$content=$post->post_content;
			preg_match($preg,$content,$img);
			return $img[0];
		}	
	}
		
	//crumbs
	function crumbs( $sep=' &gt;&gt; ',$home='首页' ){
		$par=$this->get_parrents($sep);
		if(!empty($par)){
			$num=count($par);
			$m=1;
			echo '<a href="'.get_bloginfo('url').'">'.$home.'</a>'.$sep;
			foreach($par as $link=>$name){
				if($m==$num) echo $name;
				else echo '<a href="'.$link.'">'.$name.'</a>'.$sep;
				$m++;
			}
		}
	}
	function get_parrents($sep){
		if(is_category()){
			$par=get_ancestors(get_query_var('cat'),'category');
			$num=count($par);
			for($i=$num;$i>=1;$i--){
				$j=$i-1;
				$id=$par[$j];
				$array[get_category_link($id)]=get_cat_name($id);
			}
			$array[get_category_link(get_query_var('cat'))]=get_cat_name(get_query_var('cat'));
		}
		if(is_page()){
			$par=get_ancestors(get_the_ID(),'page');
			$num=count($par);
			for($i=$num;$i>=1;$i--){
				$j=$i-1;
				$id=$par[$j];
				$page=get_page($id);
				$array[get_page_link($id)]=$page->post_title;
			}
			$cur_page=get_page(get_the_ID());
			$array[get_page_link(get_the_ID())]=$cur_page->post_title;		
		}
		if(is_single()){
			$cats=get_the_category();
			foreach($cats as $cat){
				foreach($cats as $child){
					if(!cat_is_ancestor_of($cat,$child)) $id=$cat->cat_ID;
				}
			}		
			$par=get_ancestors($id,'category');
			$num=count($par);
			for($i=$num;$i>=1;$i--){
				$j=$i-1;
				$p_id=$par[$j];
				$array[get_category_link($p_id)]=get_cat_name($p_id);
			};
			$array[get_category_link($id)]=get_cat_name($id);						
			$array[get_permalink()]=get_the_title();
		}
		if(is_tag()){
			$tag=get_tag(get_query_var('tag_id'));
			$array[]=$tag->name;
		}
		if(is_day() ||is_month() ||is_year()){
			$array[]=wp_title('',false);
		}
		if(is_search()){
			$array[]=get_query_var('s');
		}	
		return $array;
	}	
	
	//custom background
	function custom_background(){
		global $wp_version;
		if ( version_compare( $wp_version, '3.4', '>=' ) ){
			$defaults = array(
				'default-color'          => 'cccccc',
				'default-image'          => '',
				'wp-head-callback'       => '_custom_background_cb',
				'admin-head-callback'    => '',
				'admin-preview-callback' => ''
			);
			add_theme_support( 'custom-background', $defaults ); 
		}
		else add_custom_background();
	}
	
	//theme title filter
	function theme_title( $title, $sep, $seplocation ) {
		global $paged, $page;
		if ( is_feed() )
			return $title;
		// Add the site name.
		$title .= get_bloginfo( 'name' );
		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";
		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );
		return $title;	
	}	

}

$DX_EBLR = new DX_Eblr();