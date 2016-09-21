<?php

class DX_Seo_Anchor_Settings {
	
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
			'top_title' => '自动锚文本',
			'type' => 'menu',
			'page_title' => '自动锚文本',
			'menu_title' => '自动锚文本',
			'capability' => 'manage_options',
			'menu_slug' => 'dxseo_anchor',
			'screen_icon' => 'options-general',
			'parent_slug' => 'dx_seo'
		);
		$this->option_name = DXSEO_PRE . '_anchor_text_settings';		// Set option name	
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
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}
	
	/**
	 * Add admin menu assign to page
	 */
	function add_admin_menu() {
		$this->hook_suffix = add_submenu_page( $this->menus['parent_slug'], $this->menus['page_title'], $this->menus['menu_title'], $this->menus['capability'], $this->menus['menu_slug'], array( $this, 'menu_page' ) );
		// Help
		require_once( 'class-anchor-help.php' );
		add_action( "load-$this->hook_suffix", array( 'DX_Seo_Anchor_Help', 'init' ) );	
	}
	
	/**
	 * Content on menu page
	 */
	function menu_page() {
?>
		<div id="settings-container" class="wrap">
			<?php screen_icon( $this->menus['screen_icon'] ); ?>
			<h2 class="menu-title"><?php echo $this->menus['top_title']; ?></h2>
			<form method="post" action="options.php">
				<?php
					do_action( DXSEO_PRE . '_anchor_delete_port_file' );	// Delete import and export files
					DX_Seo_Do_Anchor_Datas::update_att_defaults();		// Update url attributes default values
					DX_Seo_Do_Anchor_Datas::do_ui();		// UI
				?>
				<div id="settings-wrap" class="menu-wrap">
					<?php 
						settings_fields( $this->option_name . '_group' );
						do_settings_sections( $this->menus['menu_slug'] );
						submit_button();
					?>			
				</div>
			</form>
		</div>
		<br style="clear:both;">
		<p id="sitemap-progress" style="color:green;margin-left:20px;"></p>
<?php
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
		$res['page'] = '页面内容(page)';
		$res['post'] = '文章内容(post)';
		$res = array_reverse( $res );
		return $res;
	}		
	
	/**
	 * Set datas and return
	 */
	function datas() {
		$datas = array(
			'global' => array(
				'section_title' => '',
				'section_text' => '',
				array( 'type' => 'checkbox', 'name' => 'application-page', 'label' => '应用的页面', 'values' => $this->get_posttypes(), 'des' => '例：如勾选文章，则仅在文章页(post)应用锚文本功能，支持自定义post type，默认为文章页。', 'default' => 'post' ),
				array( 'type' => 'text', 'name' => 'max-num', 'label' => '锚文本最大数量', 'default' => '8', 'des' => '控制自动添加链接的总数量，文章手工添加的链接不计算在内。<br />8表示最多添加8个链接锚文本，-1表示不限制。<br />建议一篇文章的链接不宜太多，避免权重流失。' ),
				array( 'type' => 'text', 'name' => 'class', 'label' => '链接添加class属性值', 'des' => '添加class属性值后你可以在css中控制锚文本的样式，如：dxseo-anchor。' ),
				array( 'type' => 'text', 'name' => 'tags-filter', 'label' => '忽略的标签', 'des' => '设置需要忽略的html标签，插件将不给包含在这些标签里面的关键词替换成锚文本。多个用英文逗号分隔，例：pre,blockquote。<br />内置已忽略a、img、embed、iframe标签。' ),
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
		if( ! isset( $input['application-page'] ) )
			$input['application-page'] = array();
		if( ! isset( $input['application-page'] ) )
			$input['application-page'] = array();
		if( ! isset( $input['link-itself'] ) )
			$input['link-itself'] = array();
		if( isset( $input['max-num'] ) && -1 != $input['max-num'] )
			$input['max-num'] = absint( $input['max-num'] );
		if( isset( $input['class'] ) )
			$input['class'] = sanitize_text_field( $input['class'] );
		if( isset( $input['tags-filter'] ) )
			$input['tags-filter'] = trim( sanitize_text_field( $input['tags-filter'] ), ',' );					
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
	
	/**
	 * Admin scripts enqueue
	 */
	function admin_enqueue_scripts( $hook ) {
		if( $this->hook_suffix == $hook ) {
			wp_enqueue_script( DXSEO_PRE . '_anchor_admin_js', plugins_url( 'scripts/anchor.js', __FILE__ ), '', '', true );
			wp_localize_script( DXSEO_PRE . '_anchor_admin_js', 'dxseoAnchor', array( 'pre' => DXSEO_PRE ) );
			wp_enqueue_style( DXSEO_PRE . '_anchor_admin_css', plugins_url( 'scripts/anchor.css', __FILE__ ) );
		}
	}
	
}

new DX_Seo_Anchor_Settings;