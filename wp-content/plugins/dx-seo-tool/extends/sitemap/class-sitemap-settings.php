<?php

class DX_Seo_Sitemap_Settings {
	
	/**
	 * Properties
	 */
	protected $menus;
	public $option_name;
	public $option_val;
	protected $hook_suffix;
	protected $section_text;
	protected $prefix = 'dxseo';
	
	/**
	 * Set propreties
	 * Class arguments
	 */
	function properties() {
		$this->menus = array(		// Set menus arguments
			'top_title' => '站点地图选项',
			'type' => 'menu',
			'page_title' => '站点地图',
			'menu_title' => '站点地图',
			'capability' => 'manage_options',
			'menu_slug' => 'dxseo_sitemap',
			'screen_icon' => 'options-general',
			'parent_slug' => 'dx_seo'
		);
		$this->option_name = 'dxseo_sitemap_generate';		// Set option name	
		$this->option_val = get_option( $this->option_name );
	}	
	
	/**
	 * Hooks
	 */
	function __construct() {
		// Properties
		$this->properties();
		// Hooks
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_notices', array( $this, 'update_notices' ) );
	}
	
	/**
	 * Add admin menu assign to page
	 */
	function add_admin_menu() {
		$this->hook_suffix = add_submenu_page( $this->menus['parent_slug'], $this->menus['page_title'], $this->menus['menu_title'], $this->menus['capability'], $this->menus['menu_slug'], array( $this, 'menu_page' ) );
		// Help
		require_once( 'class-sitemap-help.php' );
		add_action( "load-$this->hook_suffix", array( 'DX_Seo_Sitemap_Help', 'init' ) );															
	}
	
	/**
	 * Form bottom message
	 */
	function bottom_message() {
		echo '<div class="bottom-message" style="padding-top:20px;">';
		echo '<h5>注意事项：</h5>';
		echo '<ul style="list-style-type:square;margin-left:20px;">';
		echo '<li>如果之前已使用6.0.0版本生成百度sitemap的，因为sitemap文件名更改，需要在站长平台以及robots.txt文件再次更新下sitemap路径。</li>';
		echo '<li>生成sitemap后，你有两种方法向搜索引擎提交你的sitemap。<ol style="margin:10px 20px;">';
		echo '<li>到站长平台提交sitemap：<a href="http://zhanzhang.baidu.com/sitemap/index" target="_blank">百度站长平台</a>（目前百度sitemap功能仅向部分站长开放，需要邀请码，详<a href="http://zhanzhang.baidu.com/act/sitemapopen" target="_blank">http://zhanzhang.baidu.com/act/sitemapopen</a>） ---- <a href="http://zhanzhang.so.com/?m=Sitemap&a=listSite" target="_blank">360站长平台</a> ---- <a href="https://www.google.com/webmasters/tools/" target="_blank">google站长平台</a></li>';
		echo '<li>在robots.txt中添加sitemap网址。详<a href="http://www.seowhy.com/6_91_zh.html" target="_blank">http://www.seowhy.com/6_91_zh.html</a>, <a href="http://baike.baidu.com/view/1280732.htm?fromId=1011742" target="_blank">http://baike.baidu.com/view/1280732.htm?fromId=1011742</a></li>';
		echo '</ol></li>';
		echo '</ul>';
		echo '</div>';
	}
	
	/**
	 * Content on menu page
	 */
	function menu_page() {
	?>
		<div id="settings-container" class="wrap">
			<?php screen_icon( $this->menus['screen_icon'] ); ?>
			<h2 class="menu-title"><?php echo $this->menus['top_title']; ?></h2>
			<?php DX_Seo_Sitemap_Do_Sitemap::xml_notice(); ?>
			<form method="post" action="options.php">
			<?php $settings = $this->datas(); ?>
			<?php
				settings_fields( $this->option_name . '_group' );
				do_settings_sections( $this->menus['menu_slug'] );
			?>
				<p class="submit">
					<?php submit_button( '', 'primary', 'submit', false );?>
					<span style="margin-left:50px;" id="generate-baidu" class="button button-primary generate-sitemap">生成sitemap</span>
					<span style="margin-left:50px;" id="delete-baidu" class="button button-secondary delete-sitemap">删除sitemap</span>
				</p>
			</form>
		</div>
		<br style="clear:both;">
		<p id="sitemap-progress" style="color:green;margin-left:20px;"></p>
	<?php
		DX_Seo_Sitemap_Do_Sitemap::sitemap_jquery();
		$this->bottom_message();
	}
	
	/**
	 * Get taxonomies
	 */
	function get_taxes() {
		$taxes = get_taxonomies( array( '_builtin' => false ), 'objects' );
		if( $taxes ) {
			foreach( $taxes as $key => $tax ) {
				$res[ $key ] = $tax->labels->name;
			}
		}		
		$res['post_tag'] = '标签';
		$res['category'] = '分类目录';
		$res = array_reverse( $res );
		return $res;
	}
	
	/**
	 * Get posttypes
	 */
	function get_posttypes() {
		$posttypes = get_post_types( array( '_builtin' => false ), 'objects' );
		if( $posttypes ) {
			foreach( $posttypes as $key => $posttype ) {
				$res[ $key ] = $posttype->labels->name;
			}
		}
		$res['page'] = '页面';
		$res['post'] = '文章';
		$res = array_reverse( $res );
		return $res;
	}	
	
	/**
	 * Set datas and return
	 */
	function datas() {
		$datas = array(
			'relative_posts' => array(
				'section_title' => '',
				'section_text' => '<span>自动生成xml文件，遵循Sitemap协议，用于指引搜索引擎快速、全面的抓取或更新网站上内容及处理错误信息。兼容百度、google、360等主流搜索引擎。</span><span style="display:block; margin-top: 10px;">注意：参数需要保存后才生效，请设置完参数保存后再点击&quot;生成sitemap&quot;按钮。</span>',
				array( 'type' => 'text', 'name' => 'sitemap-file', 'label' => '文件名', 'default' => 'sitemap', 'des' => '输入网站地图文件名，注意不带文件类型后缀！一般保留默认名即可。' ),
				array( 'type' => 'checkbox', 'name' => 'baidu-post-types', 'label' => '生成链接类型 - post type', 'values' => $this->get_posttypes(), 'des' => '例：如果仅希望生成文章的sitemap，则只勾选文章即可。' ),
				array( 'type' => 'checkbox', 'name' => 'baidu-taxonomies', 'label' => '生成链接类型 - taxonomy', 'values' => $this->get_taxes() ),
				array( 'type' => 'text', 'name' => 'baidu-num', 'label' => '生成链接数量', 'default' => '500', 'des' => '链接数越大所占用的资源也越大，根据自己的服务器配置情况设置数量。最新发布的文章首先排在最前。 -1 表示所有。<br />此数量仅指post type的数量总和，不包括taxonomy，勾选的taxonomy是全部生成链接。' ),
				array( 'type' => 'checkbox', 'name' => 'baidu-auto-update', 'label' => '自动更新', 'values' => array( 'on' => '' ), 'des' => '勾选则发布新文章或者删除文章时自动更新sitemap。' )
			),
		);
		return $datas;
	}
	
	/**
	 * Register settings
	 */
	function register_settings() {
		register_setting( $this->option_name . '_group', $this->option_name, array( $this, 'sanitize' ) );
		// Get datas
		$datas = $this->datas();
		if( empty( $datas ) )
			return;
		// Foreach datas to add section and field
		foreach( $datas as $section_id => $data ) {
			$section_id = $this->option_name . '_' . $section_id;
			$section_title = isset( $data['section_title'] ) ? $data['section_title'] : '';
			$this->section_text[] = isset( $data['section_text'] ) ? $data['section_text'] : '';
			add_settings_section( $section_id, $section_title, array( $this, 'section' ), $this->menus['menu_slug'] );		// Add section
			$i = 1;
			if( empty( $data ) )
				continue;
			foreach( $data as $field ) {
				if( ! is_array( $field ) )
					continue;
				$label = $field['label'];
				$field[ 'label_for' ] = isset( $field['id'] ) ? $this->prefix . '-' . $field['id'] : $this->prefix . '-' . $field['name'];
				add_settings_field( $this->option_name . '_field_id_' . $i, $label, array( $this, 'fields' ), $this->menus['menu_slug'], $section_id, $field );		// Add field
				$i++;				
			}
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
		static $j = 0;
		echo '<p class="section-text">' .$this->section_text[ $j ] . '</p>';
		$j++;
	}
	
	/**
	 * Do fields
	 */
	function do_fields( $option_name, $option_value, $args ) {
		$default = '';		
		extract( $args );
		$id = isset( $id ) ? $this->prefix . '-' . $id : $this->prefix . '-' . $name;
		$value = isset( $option_value[ $name ] ) ? $option_value[ $name ] : $default;
		$name = $this->option_name . '[' .$name . ']';
		switch( $type ) {
			case 'text': {
				echo '<input type="text" name="' . $name. '" id="' . $id . '" class="regular-text" value="' . $value . '"/>';
				break;
			}
			case 'select': {
				echo '<select id="' . $id . '" class="" name="' . $name. '">';
				if( $values ) {
					foreach( $values as $key => $text ) {
						echo '<option value="' . $key . '" ' . selected( $key, $value, false ) . '>' . $text . '</option>';
					}
				}
				echo '</select>';
				break;
			}
			case 'checkbox': {
				foreach( $values as $val => $text ) {
					echo '<input type="checkbox" id="' . $id . '" class="" name="' . $name . '[]" value="' . esc_attr( $val ) . '" ' . checked( in_array( $val, (array) $value ), true, false ) . '/> <span class="checkbox-span">' . $text . '</span> ';
				}
				break;
			}
			case 'textarea': {
				echo '<textarea type="textarea" id="' . $id . '" class="large-text code" name="' . $name . '" >' . $value . '</textarea>'; 
				break;
			}
		}		
	}
	
	/**
	 * Add settings fields
	 */
	function fields( $args ) {
		echo '<div class="field-wrap field-wrap-' . $args['type'] . '">';
		$this->do_fields( $this->option_name, $this->option_val, $args);
		echo '</div>';
		$des = isset( $args['des'] ) ? $args['des'] : '';
		if( $des )
			echo '<p class="description">' . $des . '</p>';
	}
	
	/**
	 * Show notices when update
	 */	
	function update_notices() {
		global $hook_suffix;
		if( $hook_suffix == $this->hook_suffix ) {
			settings_errors();
		}
			
	}
	
}

new DX_Seo_Sitemap_Settings;