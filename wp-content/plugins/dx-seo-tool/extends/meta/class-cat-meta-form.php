<?php
class DX_Seo_Tax_Options {
	
	/**
	 * Properties
	 */
	protected $name = '';
	
	/**
	 * Hook
	 */
	function __construct() {
		$settings = get_option( 'aioseop_options' );
		$hooks = isset( $settings['taxonomies'] ) ? (array) $settings['taxonomies'] : array( 'category', 'post_tag' );	
		if( $hooks ) {
			foreach( $hooks as $hook ) {
				add_action( $hook . '_edit_form', array( $this, 'form' ), 999, 2 );
				add_action( 'edit_' . $hook, array( $this, 'update' ) );		
			}
		}
	}
	
	/**
	 * Do option name
	 */
	function do_option_name( $taxonomy ) {
		switch( $taxonomy ) {
			case 'category' : $name = 'cat_meta_key'; break;
			default : $name = 'tag_meta_key'; break;
		}
		return $name;		
	}
	
	/**
	 * Add edit category form
	 */
	function form( $tag, $taxonomy ) {
		if( isset( $_GET['action'] ) && isset( $_GET['taxonomy'] ) && 'edit' == $_GET['action'] && $taxonomy ==$_GET['taxonomy'] ):
			$this->name = $this->do_option_name( $taxonomy );
			$options = get_option( $this->name . '_' . $tag->term_id );			
			$title = isset( $options['page_title'] ) && $options['page_title'] ? $options['page_title']: '';
			$keywords = isset( $options['metakey'] ) && $options['metakey'] ? $options['metakey']: '';	
			$description = isset( $options['description'] ) && $options['description'] ? $options['description']: '';
			$metas = isset( $options['metas'] ) && $options['metas'] ? $options['metas']: '';	
?>
			<div id="dxseo-<?php echo $taxonomy; ?>-wrap">
				<h3>Meta设置</h3>
				<table class="form-table">
					<tbody>
						<tr class="form-field">
							<th scope="row" valign="top"><label for="<?php $this->field_id( 'page_title' ); ?>">Title</label></th>
							<td>
								<input name="<?php $this->field_name( 'page_title' ); ?>" id="<?php $this->field_id( 'page_title' ); ?>" class="seo-title" type="text" value="<?php echo esc_attr( $title ); ?>" style="width:80%;">
								<?php do_action( 'dxseo_search_click' ); ?>
							</td>
						</tr>
						<tr class="form-field">
							<th scope="row" valign="top"><label for="<?php $this->field_id( 'metakey' ); ?>">Keywords</label></th>
							<td><input name="<?php $this->field_name( 'metakey' ); ?>" id="<?php $this->field_id( 'metakey' ); ?>" class="seo-keywords" type="text" value="<?php echo esc_attr( $keywords ); ?>" style="width:80%;"></td>
						</tr>
						<tr class="form-field">
							<th scope="row" valign="top"><label for="<?php $this->field_id( 'description' ); ?>">Description</label></th>
							<td><textarea name="<?php $this->field_name( 'description' ); ?>" id="<?php $this->field_id( 'description' ); ?>" style="width:80%; height: 70px;" class="large-text"><?php echo $description; ?></textarea></td>
						</tr>
						<tr class="form-field">
							<th scope="row" valign="top"><label for="<?php $this->field_id( 'metas' ); ?>">Metas</label></th>
							<td><textarea name="<?php $this->field_name( 'metas' ); ?>" id="<?php $this->field_id( 'metas' ); ?>" style="width:80%; height: 70px;" class="large-text"><?php echo $metas; ?></textarea></td>
						</tr>								
					</tbody>
				</table>
			</div>
<?php
		endif;
	}
	
	/**
	 * Update category edit data
	 */
	function update( $cid ) {
		global $taxonomy;
		$name = $this->do_option_name( $taxonomy );
		update_option( $name . '_' . $cid, stripslashes_deep( $_POST[ $name ] ) );	
	}
	
	/**
	 * Get field id
	 */
	function field_id( $id, $echo = true ) {
		$id = $this->name . '-' . $id;
		if( true == $echo )
			echo $id;
		else
			return $id;
	}
	
	/**
	 * Get field name
	 */
	function field_name( $name, $echo = true ) {
		$name = $this->name . "[$name]";
		if( true == $echo )
			echo $name;
		else
			return $name;
	}		
	
}


new DX_Seo_Tax_Options;