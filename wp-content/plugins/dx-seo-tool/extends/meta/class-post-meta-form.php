<?php

class DX_Seo_Postmeta {
	
	/**
	 * Properties
	 */
 	protected $prefix = 'dxseo';
	// add_meta_box arguments
	protected $id = 'meta';
	protected $title = '自定义seo meta';
	protected $types = array();
	protected $context = 'normal';
	protected $priority = 'high';
	protected $key = '_web589_singular_meta';	// Post meta key
	
	/**
	 * Hook
	 */
	function __construct() {
		$this->id = $this->prefix . '-' . $this->id;
		$settings = get_option( 'aioseop_options' );
		$this->types = isset( $settings['posttypes'] ) ? (array) $settings['posttypes'] : array( 'post', 'page' );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}
	
	/**
	 * Add meta box
	 */
	function add_meta_box() {
		if( $this->types ) {
			foreach( $this->types as $type ) {
				add_meta_box( $this->id, $this->title, array( $this, 'meta_form' ), $type, $this->context, $this->priority );
			}
		}
	}
	
	/**
	 * Save meta data
	 */
	function save( $post_id ) {
		// Check			
		if( !isset( $_POST[ $this->id . '_wpnonce'] ) || !wp_verify_nonce( $_POST[ $this->id . '_wpnonce'], $this->id . '_nonce_action' ) )
			return;
		// Update
		update_post_meta( $post_id, $this->key, $_POST[ $this->key ] );
	}
	
	/**
	 * Get field id
	 */
	function field_id( $id, $echo = true ) {
		$id = $this->id . '_' . $id;
		if( true == $echo )
			echo $id;
		else
			return $id;
	}
	
	/**
	 * Get field name
	 */
	function field_name( $name, $echo = true ) {
		$name = $this->key . "[$name]";
		if( true == $echo )
			echo $name;
		else
			return $name;
	}
	
	/**
	 * Css
	 */
	function css() {
?>
<style type="text/css">
#dxseo-meta label { width: 100px; display: inline-block; }
#dxseo-meta textarea { vertical-align: middle; }
#dxseo-meta input, #dxseo-meta textarea { width: 50%; }
#dxseo-meta textarea { height: 60px; }
</style>
<?php
	}
		
	/**
	 * Meta form
	 */
	function meta_form( $post ) {
		wp_nonce_field( $this->id . '_nonce_action', $this->id . '_wpnonce' );
		$pid = $post->ID;
		$val = get_post_meta( $pid, $this->key, true );
		$name = $this->key;
		$title = isset( $val['title'] ) ? $val['title'] : '';
		$keywords = isset( $val['keywords'] ) ? $val['keywords'] : '';
		$description = isset( $val['description'] ) ? $val['description'] : '';
		$metas = isset( $val['code'] ) ? $val['code'] : '';
		$this->css();
?>
		<p>
			<label for="<?php $this->field_id( 'title' ); ?>">Title</label>
			<input type="text" id="<?php $this->field_id( 'title' ); ?>" class="seo-title" name="<?php $this->field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			<?php do_action( 'dxseo_search_click' ); ?>
		</p>
		<p>
			<label for="<?php $this->field_id( 'keywords' ); ?>">Keywords</label>
			<input type="text" id="<?php $this->field_id( 'keywords' ); ?>" class="seo-keywords" name="<?php $this->field_name( 'keywords' ); ?>" value="<?php echo esc_attr( $keywords ); ?>"/>
		</p>
		<p>
			<label for="<?php $this->field_id( 'description' ); ?>">Description</label>
			<textarea id="<?php $this->field_id( 'description' ); ?>" name="<?php $this->field_name( 'description' ); ?>"><?php echo $description; ?></textarea>
		</p>
		<p>
			<label for="<?php $this->field_id( 'code' ); ?>">Metas</label>
			<textarea id="<?php $this->field_id( 'code' ); ?>" name="<?php $this->field_name( 'code' ); ?>"><?php echo $metas; ?></textarea>
		</p>						
<?php
	}	
	
}

new DX_Seo_Postmeta;