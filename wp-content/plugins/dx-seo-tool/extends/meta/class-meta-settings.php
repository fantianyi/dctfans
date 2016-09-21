<?php

class DX_Seo_Meta_Settings {
	
	/**
	 * Properties
	 */
	protected $menu = array(		// Set menu args
		'parent_slug' => 'dx_seo',
		'page_title' => 'meta',
		'menu_title' => 'meta设置',
		'menu_slug' => 'dx_seo_meta'
	);
	protected $global_options = array(		// Set global options args
		'dxseo_title_paged' => array( 'label' => 'title添加分页后缀',  'type' => 'checkbox' ),			
		'dxseo_title_suffix' => array( 'label' => '内页title添加后缀文本', 'type' => 'checkbox', 'des' => '后缀文本显示在分隔符之后' ),
		'dxseo_title_tail' => array( 'label' => '后缀文本', 'type' => 'text', 'des' => '留空则显示站点标题。' ),
		'dxseo_title_sep' => array( 'label' => 'title分隔符', 'type' => 'text', 'des' => '设置title与后缀文本、分页后缀等的分隔符。' ),
		'web589_auto_description' => array( 'label' => '自动文章description', 'des' => '自动获取文章内容作为文章description，若勾选开启则在下面设置截取的字节数。', 'type' => 'checkbox'),
		'web589_auto_description_num' => array( 'label' => '获取文章内容字节数', 'des' => '1汉字==2字节', 'type' => 'text' ),
		'web589_auto_keywords' => array( 'label' => '自动文章keywords', 'des' => '自动获取文章标签作为文章keywords，开启后在下面设置标签数量。', 'type' => 'checkbox' ),
		'keywords_num' => array( 'label' => '标签最大数量', 'des' => '设置作为文章keywords的标签数量，留空则获取所有文章标签。', 'type' => 'text' ),
		'posttypes' => array(),
		'taxonomies' => array(),
	);
	protected $index_options = array(		// Set index options args
		'aiosp_home_title' => array( 'label' => 'Title', 'type' => 'text' ),
		'aiosp_home_keywords' => array( 'label' => 'Keywords', 'type' => 'text' ),
		'aiosp_home_description' => array( 'label' => 'Description', 'type' => 'textarea' ),
		'aiosp_home_metas' => array( 'label' => 'Metas', 'type' => 'textarea', 'des' => '自定义meta标签，例：&lt;meta name=&quot;copyright&quot; content=&quot;wordpress&quot; /&gt;' ),
	);		
	
	/**
	 * Hook
	 */
	function __construct() {
		$this->settings = get_option( 'aioseop_options' );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_notices', array( $this, 'update_notices' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}
	
	/**
	 * Add admin menu assign to page
	 */
	function add_admin_menu() {
		$this->hook_suffix = add_submenu_page( $this->menu['parent_slug'], $this->menu['page_title'], $this->menu['menu_title'], 'manage_options', $this->menu['menu_slug'], array( $this, 'menu_page' ) );
		// Help
		require_once( 'class-meta-help.php' );
		add_action( "load-$this->hook_suffix", array( 'DX_Seo_Meta_Help', 'init' ) );		
	}
	
	/**
	 * Content on menu page
	 */
	function menu_page() {
	?>
		<div class="wrap">
			<?php screen_icon( 'options-general' ); ?>
			<h2>Seo Meta 选项</h2>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'aioseop_options_group' );
				do_settings_sections( 'dxseo_meta_section' );
				submit_button();
			?>
			</form>
		</div>
	<?php
		/* do_action( 'dxseo_contact_message' ); */	
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
	 * Get taxonomies and posttypes
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
	 * Register settings
	 */
	function register_settings() {
		register_setting( 'aioseop_options_group', 'aioseop_options', array( $this, 'sanitize' ) );
		add_settings_section( 'dxseo_meta_section_global', '全局设置', array( $this, 'section' ), 'dxseo_meta_section' );
		add_settings_section( 'dxseo_meta_section_index', '首页META', array( $this, 'section' ), 'dxseo_meta_section' );
		$this->global_options['posttypes'] = array(
			'label' => '选择post types', 'type' => 'checkboxes', 'values' => $this->get_posttypes(), 'des' => '勾选需要应用meta功能的post types，默认为文章以及页面。例：如不勾选&quot;文章&quot;则文章页不使用meta功能。', 'default' => array( 'post', 'page' )
		);		
		$this->global_options['taxonomies'] = array(
			'label' => '选择taxonomies', 'type' => 'checkboxes', 'values' => $this->get_taxes(), 'des' => '勾选需要应用meta功能的taxonomies，默认为分类目录以及标签。', 'default' => array( 'category', 'post_tag' )
		);		
		$values = array();
		$default = '';
		foreach( $this->global_options as $key => $val ) {
			$des = '';
			extract( $val );
			$setting = isset( $this->settings[ $key ] ) ? $this->settings[ $key ]: '';
			add_settings_field( $key, $label, array( $this, 'fields' ), 'dxseo_meta_section', 'dxseo_meta_section_global', array( 'label_for' => $key, 'des' => $des, 'type' => $type, 'setting' => $setting, 'values' => $values, 'default' => $default ) );
		}
		foreach( $this->index_options as $key => $val ) {
			$des = '';
			extract( $val );
			$setting = isset( $this->settings[ $key ] ) ? $this->settings[ $key ]: '';
			add_settings_field( $key, $label, array( $this, 'fields' ), 'dxseo_meta_section', 'dxseo_meta_section_index', array( 'label_for' => $key, 'des' => $des, 'type' => $type, 'setting' => $setting ) );
		}		
	}
	
	/**
	 * Sanitize the option's value
	 */
	function sanitize( $input ) {
		if( ! isset( $input['taxonomies'] ) )
			$input['taxonomies'] = array();
		if( ! isset( $input['posttypes'] ) )
			$input['posttypes'] = array();			
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
				$class = '';
				if( 'aiosp_home_title' == $label_for )
					$class = ' seo-title';
				if( 'aiosp_home_keywords' == $label_for )
					$class = ' seo-keywords';					
				echo '<input type="text" name="aioseop_options[' . $label_for . ']" id="' . $label_for . '" class="regular-text' . $class . '" value="' . esc_attr( $setting ). '" />';
				break;
			}
			case 'checkbox': {				
				echo '<input type="checkbox" name="aioseop_options[' . $label_for . ']" id="' . $label_for . '" value="on" ' . checked( 'on', $setting, false ) . ' /> 开启';
				break;
			}
			case 'checkboxes': {
				foreach( $values as $key => $value ) {
					$setting = isset( $this->settings[ $label_for ] ) ? $this->settings[ $label_for ]: $default;
					echo '<input type="checkbox" name="aioseop_options[' . $label_for . '][]" id="' . $label_for . '" value="' . $key . '" ' . checked( true, in_array( $key, $setting ), false ) . ' /> ' . $value . ' ';
				}
				break;
			}			
			case 'textarea': echo '<textarea id="' . $label_for . '" class="" name="aioseop_options[' . $label_for . ']">' . $setting . '</textarea>'; break;
		}
		if( 'aiosp_home_title' == $label_for )
			do_action( 'dxseo_search_click' );
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
	 * Admin enqueue scripts
	 */
	function admin_enqueue_scripts( $hook ) {
		if($hook != $this->hook_suffix  )
			return;
		wp_enqueue_style( 'dxseo-meta-style', plugins_url( 'meta.css', __FILE__ ) );
		/* wp_enqueue_script( 'dxseo-meta-js', plugins_url( 'meta.js', __FILE__ ) ); */
	}
	
}

new DX_Seo_Meta_Settings;