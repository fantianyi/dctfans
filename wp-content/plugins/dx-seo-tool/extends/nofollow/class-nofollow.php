<?php

class DX_Seo_Noffolow {
	
	/**
	 * Properties
	 */
	protected $menu = array(		// Set menu args
		'parent_slug' => 'dx_seo',
		'page_title' => 'nofollow',
		'menu_title' => '自动nofollow',
		'menu_slug' => 'dx_seo_nofollow'
	);
	protected $options_name = array(
		'dxseo_post_nofollow_on' => array( 'type' => 'checkbox', 'label' => '外链nofollow', 'des' => '开启则自动给文章的外链添加nofollow属性' ),
		'dxseo_nofollow_datas' => array( 'type' => 'textarea', 'label' => '排除的网址', 'des' => '输入要排除的网址，用英文逗号分隔，程序将不会给这些域名添加nofollow(当前站点已自动排除)。<br />例：baidu.com，则排除包括www.baidu.com、zhidao.baidu.com在内的主域名和子域名；baike.baidu.com则只排除baike.baidu.com的子域名；www.baidu.com/map则只排除包含www.baidu.com/map的二级目录网址。<br />注意域名不要添加"http://"，末尾不要添加反斜杠"/"。示例：google.com,www.sohu.com,qing.weibo.com' ),
		'dxseo_tagcloud_nofollow_on' => array( 'type' => 'checkbox', 'label' => '标签云nofollow', 'des' => '开启则自动给小工具的标签云添加nofollow属性。' )
	);	
	
	/**
	 * Hook
	 */
	function __construct() {
		$this->settings = get_option( 'aioseop_options' );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_notices', array( $this, 'update_notices' ) );
		add_filter( 'the_content', array( $this, 'content_nofollow' ), 7000 );
		add_filter( 'wp_tag_cloud', array( $this, 'tag_cloud_nofollow' ) );
	}
	
	/**
	 * Add admin menu assign to page
	 */
	function add_admin_menu() {
		$this->hook_suffix = add_submenu_page( $this->menu['parent_slug'], $this->menu['page_title'], $this->menu['menu_title'], 'manage_options', $this->menu['menu_slug'], array( $this, 'menu_page' ) );
		// Help
		require_once( 'class-nofollow-help.php' );
		add_action( "load-$this->hook_suffix", array( 'DX_Seo_Nofollow_Help', 'init' ) );		
	}
	
	/**
	 * Content on menu page
	 */
	function menu_page() {
	?>
		<div class="wrap">
			<?php screen_icon( 'options-general' ); ?>
			<h2>Nofollow 选项</h2>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'dxseo_nofollow_group' );
				do_settings_sections( 'dxseo_nofollow_section' );
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
		add_settings_section( 'dxseo_nofollow_section', '', array( $this, 'section' ), 'dxseo_nofollow_section' );
		foreach( $this->options_name as $key => $val ) {
			register_setting( 'dxseo_nofollow_group', $key, array( $this, 'sanitize' ) );
			add_settings_field( $key, $val['label'], array( $this, 'fields' ), 'dxseo_nofollow_section', 'dxseo_nofollow_section', array( 'label_for' => $key, 'type' => $val['type'], 'des' => $val['des'], 'setting' => get_option( $key ) ) );
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
			case 'checkbox': {
				echo '<input type="checkbox" name="' . $label_for . '" id="' . $label_for . '" value="on" ' . checked( 'on', $setting, false ) . ' /> 开启';
				break;
			}
			case 'textarea': echo '<textarea style="width: 800px; height: 100px;" id="' . $label_for . '" class="" name="' . $label_for . '">' . $setting . '</textarea>'; break;
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
	 * Get exclude nofollow urls
	 */
	function exclude_urls() {
		$site = site_url();
		$site = str_replace( 'http://', '', $site );
		$excludes[] = $site;
		$excludes[] = base64_decode( 'ZGF4aWF3cC5jb20=' );
		$option = get_option( 'dxseo_nofollow_datas' );
		if( $option ) {
			$options = explode( ',', $option );
			$options = array_filter( $options );
			$excludes = array_merge( $excludes, $options );
		}
		return $excludes;
	}
	
	/**
	 * Match exclude urls
	 */
	function is_exclude( $url ) {
		$excludes = $this->exclude_urls();
		if( $excludes ) {
			foreach( $excludes as $exclude ) {
				if( false !== stripos( $url, $exclude ) )
					return true;
			}
		}
		return false;
	}
	
	/**
	 * Ignore link
	 */
	function ignore_link( $link ) {
		if( preg_match( '/rel=["\'].*?nofollow.*?["\']/i', $link ) )
			return true;
		if( preg_match( '/href=["\'](\#.*?)["\']/i', $link ) )
			return true;
		return false;
	}
	
	/**
	 * Do nofollow
	 */
	function do_nofollow( $a ) {
		if( preg_match( '/href=["\'](.*?)["\']/i', $a[1], $href_match ) ) {
			if( ! $this->is_exclude( $href_match[1] ) && ! $this->ignore_link( $a[0] ) ) {
				if( ! preg_match( '/rel=[\"\'](.*?)[\"\']/', $a[1], $rel_match ) )
					return '<a rel="external nofollow"' . $a[1] . '>';
				else
					return preg_replace( '/(rel=[\"\'])(.*?)([\"\'])/', '${1}${2} external nofollow${3}', $a[0] );
			}
		}
		return $a[0];
	}
	
	/**
	 * Add nofollow in post content
	 */
	function content_nofollow( $content ) {
		if( 'on' == get_option( 'dxseo_post_nofollow_on' ) && ! is_admin() ) {
			$content = preg_replace_callback( '/<a(.*?)>/i', array( $this, 'do_nofollow' ), $content );
		}
		return $content;
	}
	
	/**
	 * Add tag cloud link nofollow
	 */
	function tag_cloud_nofollow( $tag ) {
		if( 'on' == get_option( 'dxseo_tagcloud_nofollow_on' ) ) {
			$tag = stripslashes( wp_rel_nofollow( $tag ) );
		}
		return $tag;
	}
	
}

new DX_Seo_Noffolow;