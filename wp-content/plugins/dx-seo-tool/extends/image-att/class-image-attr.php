<?php

class DX_Seo_Image_Att {
	
	/**
	 * Properties
	 */
	protected $menu = array(		// Set menu args
		'parent_slug' => 'dx_seo',
		'page_title' => 'image-att',
		'menu_title' => '图片自动属性',
		'menu_slug' => 'dx_seo_image_att'
	);
	protected $options_name = array(
		'web589seo_ia_title' => array( 'type' => 'text', 'label' => 'Title', 'des' => '', 'value' => '' ),
		'web589seo_ia_alt' => array( 'type' => 'text', 'label' => 'Alt', 'des' => '%title%  --当前文章的标题<br />%tag%  --当前文章的其中一个标签<br />%category%  --当前文章的其中一个分类<br />%blog%  --博客名称<br />示例：title文本框中输入&quot;%title%的图片&quot;，文章图片的title属性将自动显示为&quot;文章标题&quot;+&quot;的图片&quot;<br />', 'value' => '' ),
		'dxseo_ia_suffix' => array( 'type' => 'text', 'label' => '多图片序号后缀', 'des' => '当文章内容有多张图片时显示的后缀，%d表示自增序号，避免全部图片的title和alt使用同一名称。<br />例: 第%d张，则依次在title、alt属性值后添加 第1张,第2张......', 'value' => '' ),
		'dxseo_ia_override_disabled' => array( 'type' => 'checkbox', 'label' => '保留原值', 'value' => 'on', 'des' => '若勾选，原图片设置有title或alt属性值则保留原值。' )
	);	
	
	/**
	 * Hook
	 */
	function __construct() {
		$this->settings = get_option( 'aioseop_options' );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_notices', array( $this, 'update_notices' ) );
		add_filter( 'the_content', array( $this, 'content_attr' ), 6000 );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'attachment_attr' ) );
	}
	
	/**
	 * Add admin menu assign to page
	 */
	function add_admin_menu() {
		$this->hook_suffix = add_submenu_page( $this->menu['parent_slug'], $this->menu['page_title'], $this->menu['menu_title'], 'manage_options', $this->menu['menu_slug'], array( $this, 'menu_page' ) );
		// Help
		require_once( 'class-image-attr-help.php' );
		add_action( "load-$this->hook_suffix", array( 'DX_Seo_Image_Attr_Help', 'init' ) );		
	}
	
	/**
	 * Content on menu page
	 */
	function menu_page() {
	?>
		<div class="wrap">
			<?php screen_icon( 'options-general' ); ?>
			<h2>图片自动属性选项</h2>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'dxseo_image_att_group' );
				do_settings_sections( 'dxseo_image_att_section' );
				submit_button();
			?>
			</form>		
		</div>
	<?php
		/* do_action( 'dxseo_contact_message' ); */	
	}
	
	/**
	 * Register settings
	 */
	function register_settings() {
		add_settings_section( 'dxseo_image_att_section', '', array( $this, 'section' ), 'dxseo_image_att_section' );
		foreach( $this->options_name as $key => $val ) {
			register_setting( 'dxseo_image_att_group', $key, array( $this, 'sanitize' ) );
			add_settings_field( $key, $val['label'], array( $this, 'fields' ), 'dxseo_image_att_section', 'dxseo_image_att_section', array( 'label_for' => $key, 'type' => $val['type'], 'des' => $val['des'], 'setting' => get_option( $key ), 'value' => $val['value']  ) );
		}	
	}
	
	/**
	 * Sanitize the option's value
	 */
	function sanitize( $input ) {
		return $input;
	}
	
	/**
	 * Settings section content
	 */
	function section() {
		echo '';
	}
	
	/**
	 * Add settings fields
	 */
	function fields( $args ) {
		extract( $args );
		switch( $type ) {
			case 'text': {
				echo '<input type="text" name="' . $label_for . '" id="' . $label_for . '" class="regular-text" value="' . esc_attr( $setting ) . '"/>';
				break;
			}
			case 'checkbox': {
				echo '<input type="checkbox" name="' . $label_for . '" id="' . $label_for . '" class="" value="' . esc_attr( $value ) . '" ' . checked( $setting, $value, false ) . '/>';
				break;
			}			
		}
		if( $des )
			echo '<p class="description">' . $des . '</p>';
	}
	
	/**
	 * Show notices when update
	 */	
	function update_notices() {
		global $hook_suffix;
		if( $hook_suffix == $this->hook_suffix )
			settings_errors();
	}
	
	/**
	 * Get tag name
	 */
	function get_tag() {
		global $post;
		$tags = get_the_tags( $post->ID );
		if( $tags ){
			$tag = array_shift( $tags );
			$tag = $tag->name;		
		}
		$tag = isset( $tag ) ? $tag : '';
		return $tag;	
	}
	
	/**
	 * Get category name
	 */
	function get_cat() {
		global $post;
		$cats = get_the_category( $post->ID );
		if( $cats ){
			$cat = array_shift( $cats );
			$cat = $cat->name;		
		}
		$cat = isset( $cat ) ? $cat : '';
		return $cat;	
	}
	
	/**
	 * Do title attr
	 */
	function title_attr( $title ) {
		if( ! empty( $this->setting_title ) ) {
			$t = ( 'on' == $this->override_disabled && $title ) ? $title : $this->setting_title;
			$title = str_replace( $this->search, $this->replace, $t );
		}
		return $title;
	}
	
	/**
	 * Do alt attr
	 */
	function alt_attr( $alt ) {
		if( ! empty( $this->setting_alt ) ) {
			$a = ( 'on' == $this->override_disabled && $alt ) ? $alt : $this->setting_alt;
			$alt = str_replace( $this->search, $this->replace, $a );
		}
		return $alt;
	}
	
	/**
	 * Title suffix
	 */
	function title_suffix( $i ) {
		if( $this->suffix ) {
			$suffix = str_replace( '%d', $i, $this->suffix );
			return ' ' . trim( $suffix );
		}
		return '';
	}
	
	/**
	 * Get Properties
	 */
	function get_properties() {
		$this->setting_title = get_option( 'web589seo_ia_title' );
		$this->setting_alt = get_option( 'web589seo_ia_alt' );
		$this->setting_suffix = get_option( 'dxseo_ia_suffix' );
		$this->search = array( '%title%', '%tag%', '%category%', '%blog%' );
		$this->replace = array( get_the_title(), $this->get_tag(), $this->get_cat(), get_bloginfo( 'name' ) );
		$this->override_disabled = get_option( 'dxseo_ia_override_disabled' );	
		$this->suffix = get_option( 'dxseo_ia_suffix' );	
	}
		
	/**
	 * Do content attr
	 */
	function content_attr( $content ) {
		if( ! is_admin() ) {
			
			// Arguments
			$this->get_properties();			
			$num = preg_match_all( '/<img.*?>/i', $content, $matches );
			
			// One image
			if( 1 == $num ) {
				
				// Get original title and alt
				preg_match( '/<img.*?title=[\"\'](.*?)[\"\'].*?>/', $content, $match_title );
				$title = isset( $match_title[1] ) ? $match_title[1] : '';				
				preg_match( '/<img.*?alt=[\"\'](.*?)[\"\'].*?>/', $content, $match_alt );
				$alt = isset( $match_alt[1] ) ? $match_alt[1] : '';
				
				// Clear title and alt
				$content = preg_replace( '/(<img.*?) title=["\'].*?["\']/i', '${1}', $content );
				$content = preg_replace( '/(<img.*?) alt=["\'].*?["\']/i','${1}', $content );				
				
				// Replace
				$content = preg_replace( '/<img/i', '<img' . ' title="' . esc_attr( $this->title_attr( $title ) ) . '" alt="' . esc_attr( $this->alt_attr( $alt ) ) . '"', $content, 1 );
			}
			
			// multi images add suffix
			if( 1 < $num ) {
				$temp = '*@@##@@*';
				for( $i = 1; $i <= $num; $i++ ) {
					
					// Get original title and alt
					preg_match( '/<img.*?>/', $content, $match_img );
					$img = isset( $match_img[0] ) ? $match_img[0] : '';
					preg_match( '/<img.*?title=[\"\'](.*?)[\"\'].*?>/', $img, $match_title );
					$title = isset( $match_title[1] ) ? $match_title[1] : '';				
					preg_match( '/<img.*?alt=[\"\'](.*?)[\"\'].*?>/', $img, $match_alt );
					$alt = isset( $match_alt[1] ) ? $match_alt[1] : '';			
					
					// Set suffix
					$title_suffix = $this->title_attr( $title ) ? $this->title_suffix( $i ) : '';
					$alt_suffix = $this->alt_attr( $alt ) ? $this->title_suffix( $i ) : '';
					
					// Clear title and alt
					if( $title )
						$content = preg_replace( '/(<img.*?) title=["\'].*?["\']/i', '${1}', $content, 1 );
					if( $alt )
						$content = preg_replace( '/(<img.*?) alt=["\'].*?["\']/i','${1}', $content, 1 );				
					
					// Replace to temp
					$replace = '<' . $temp . ' title="' . $this->title_attr( $title ) . $title_suffix . '" alt="' . $this->alt_attr( $alt ) . $alt_suffix . '"';					
					$content = preg_replace( '/<img/i', $replace, $content, 1 );
				}
				
				$content = str_replace( $temp, 'img', $content );	
			}
		}
		
		return $content;		
	}
	
	/**
	 * Do attachment attr
	 */
	function attachment_attr( $attr ) {		
		if( ! is_admin() ) {
			$this->get_properties();
			
			$title = isset( $attr['title'] ) ? $attr['title'] : '';
			$attr['title'] = $this->title_attr( $title );
			
			$alt = isset( $attr['alt'] ) ? $attr['alt'] : '';
			$attr['alt'] = $this->alt_attr( $alt );
		}
		return $attr;		
	}
	
}

new DX_Seo_Image_Att;