<?php

class DX_Seo_Relative_posts_Settings {
	
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
			'top_title' => '相关文章选项',
			'type' => 'menu',
			'page_title' => '相关文章',
			'menu_title' => '相关文章',
			'capability' => 'manage_options',
			'menu_slug' => 'dxseo_relative_posts',
			'screen_icon' => 'options-general',
			'parent_slug' => 'dx_seo'
		);
		$this->option_name = 'dxseo_relative_posts';		// Set option name	
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
		require_once( 'class-relative-help.php' );
		add_action( "load-$this->hook_suffix", array( 'DX_Seo_Relative_Help', 'init' ) );														
	}
	
	/**
	 * Content on menu page
	 */
	function menu_page() {
	?>
		<div id="settings-container" class="wrap">
			<?php screen_icon( $this->menus['screen_icon'] ); ?>
			<h2><?php echo $this->menus['top_title']; ?></h2>
			<form method="post" action="options.php">
			<?php $settings = $this->datas(); ?>
			<?php
				settings_fields( $this->option_name . '_group' );
				do_settings_sections( $this->menus['menu_slug'] );
				submit_button();
			?>
			</form>
		</div>
		<br style="clear:both;">
	<?php		
	}
	
	/**
	 * Require datas and return
	 */
	function datas() {
		$datas = array(
			'relative_posts' => array(
				'section_title' => '',
				'section_text' => '在文章页底部显示相关文章列表。',
				array( 'type' => 'select', 'name' => 'related-type', 'label' => '相关依据', 'values' => array( 'cat' => '相同分类', 'tag' => '相同标签', 'all' => '所有' ), 'des' => '相同分类：显示与当前文章所属分类相同的其它文章；相同标签：显示与当前文章所添加标签相同的其它文章；所有：显示所有其它文章。' ),
				array( 'type' => 'select', 'name' => 'orderby', 'label' => '排序方式', 'values' => array( 'ID' => 'ID', 'rand' => '随机' ), 'des' => 'ID:按文章ID降序显示；随机：文章随机显示。' ),
				array( 'type' => 'text', 'name' => 'posts-num', 'label' => '显示文章数量', 'default' => '8'  ),
				array( 'type' => 'text', 'name' => 'title', 'label' => '标题名称', 'default' => '相关文章' ),
				array( 'type' => 'text', 'name' => 'title-size', 'label' => '标题大小', 'default' => '14', 'des' => '单位：px' ),
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
		if( $hook_suffix == $this->hook_suffix )
			settings_errors();
	}
	
}

new DX_Seo_Relative_posts_Settings;