<?php

class DX_Seo_Do_Anchor_Datas {
	
	/**
	 * Init
	 * Properties
	 */
	function __construct() {
		add_action( "load-dx-seo_page_dxseo_anchor", array( $this, "screen_options" ) );
		add_filter('set-screen-option', array( $this, 'save_screen_options' ), 10, 3 );		
		add_action( 'wp_ajax_' . DXSEO_PRE . '_anchor_init', array( $this, 'ajax_anchor_init' ) );
		add_action( 'wp_ajax_' . DXSEO_PRE . '_anchor_paginate', array( $this, 'ajax_anchor_paginate' ) );
		add_action( 'wp_ajax_' . DXSEO_PRE . '_anchor_refresh', array( $this, 'ajax_anchor_refresh' ) );
		add_action( 'wp_ajax_' . DXSEO_PRE . '_anchor_add', array( $this, 'ajax_anchor_update' ) );
		add_action( 'wp_ajax_' . DXSEO_PRE . '_anchor_delete', array( $this, 'ajax_anchor_delete' ) );
		add_action( 'wp_ajax_' . DXSEO_PRE . '_anchor_delete_all', array( $this, 'ajax_anchor_delete_all' ) );
		add_action( 'wp_ajax_' . DXSEO_PRE . '_anchor_destroy_datas', array( $this, 'ajax_anchor_destroy_datas' ) );
	}
	
	/**
	 * Screen options for per_page
	 */
	function screen_options() {
		$option = 'per_page';
		 
		$args = array(
		'label' => '每页显示',
		'default' => 20,
		'option' => DXSEO_PRE . '_anchor_per_page'
		);
		 
		add_screen_option( $option, $args );		
	}
	
	/**
	 * Save screen options
	 */
	function save_screen_options( $status, $option, $value ) {
		if( DXSEO_PRE . '_anchor_per_page' == $option )
			return $value;
	}
	
	/**
	 * Get per_page value from screen options
	 */
	static function per_page() {
		$uid = get_current_user_id();
		$per_page = get_user_meta( $uid, DXSEO_PRE . '_anchor_per_page', true );
		$per_page = empty( $per_page ) ? 20 : $per_page;
		return (int) $per_page;
	}
	
	/**
	 * Do UI
	 */
	static function do_ui() {
		require_once( 'ui/index.php' );
	}
	
	/**
	 * Init to get option values
	 */
	function init() {
		$this->datas_name = DXSEO_PRE . '_anchor_text_datas';
		$this->datas = get_option( $this->datas_name );
		$this->datas = empty( $this->datas ) ? array() : $this->datas;
		
		$this->priorities_name = DXSEO_PRE . 'anchor_text_priority';
		$priorities = get_option( $this->priorities_name );
		$this->priorities = empty( $priorities ) ? array() : $priorities;
	}
	
	/**
	 * Update data's att default values
	 */
	static function update_att_defaults() {
		$name = DXSEO_PRE . '_anchor_att_defaults';
		if( ! get_option( $name ) ) {
			update_option( $name, array( 0, 1, 1, 0, 1, 10 ) );
		}
	}
	
	/**
	 * Orderby time
	 */
	function orderby_time() {
		if( 'desc' == $this->order )
			$this->datas = array_reverse( $this->datas, true );
	}
	
	/**
	 * Orderby keyword
	 */
	function orderby_keyword() {
		foreach( $this->datas as $key => $val ) {
			$name = iconv( get_bloginfo( 'charset' ), 'GBK', $key );
			$gbk[ $name ] = $val;
		}
		if( 'desc' == $this->order )
			krsort( $gbk, SORT_STRING );
		else	
			ksort( $gbk, SORT_STRING );
		foreach( $gbk as $key => $val ) {
			$name = iconv( 'GBK', get_bloginfo( 'charset' ), $key );
			$utf[ $name ] = $val;
		}
		$this->datas = $utf;
	}
	
	/**
	 * Orderby priority
	 */
	function orderby_priority() {
		if( $this->priorities ) {
			if( 'desc' == $this->order )
				arsort( $this->priorities, SORT_NUMERIC );
			else
				asort( $this->priorities, SORT_NUMERIC );
			foreach( $this->priorities as $key => $priority ) {
				if( isset( $this->datas[ $key ] ) )
					$temp[ $key ] = $this->datas[ $key ];
			}
			if( $temp )
				$this->datas = $temp;
		}
	}
	
	/**
	 * Do search datas
	 */
	function do_search() {
		if( $this->search_word ) {
			foreach( $this->datas as $key => $data ) {
				if( stripos( $key, $this->search_word ) === false )
					unset( $this->datas[ $key ] );
			}
			$this->search_datas = $this->datas;	
		}
	}
	
	/**
	 * Get datas for paging
	 */
	function datas_paging() {
		if( $this->datas ) {
			$offset = ( $this->pagenum - 1 ) * $this->per_page;
			$this->datas = array_slice( $this->datas, $offset, $this->per_page, true );
		}
	}
	
	/**
	 * Datas filter
	 */
	function get_datas() {
		$this->init();
		if( empty( $this->datas ) )
			return;
		
		// Set variables	
		$this->order = ( isset( $_POST['order'] ) && $_POST['order'] ) ? $_POST['order'] : 'desc';
		$this->orderby = ( isset( $_POST['orderby'] ) && $_POST['orderby'] ) ? $_POST['orderby'] : 'time';
		$this->search_word = ( isset( $_POST['search_word'] ) && $_POST['search_word'] ) ? $_POST['search_word'] : '';
		$this->per_page = self::per_page();
		$this->pagenum = ( isset( $_POST['pagenum'] ) && $_POST['pagenum'] ) ? (int) $_POST['pagenum'] : 1;
		
		// Do sort
		switch( $this->orderby ) {
			case 'keyword': $this->orderby_keyword(); break;
			case 'priority': $this->orderby_priority(); break;
			default: $this->orderby_time();
		}
		
		// do search
		$this->do_search();
		
		// Do paging
		$this->datas_paging();
	}
	
	/**
	 * Paginate
	 */
	function paginate() {
		$this->get_datas();
		if( isset( $this->search_word ) &&  $this->search_word )
			$datas = $this->search_datas;
		else
			$datas = get_option( DXSEO_PRE . '_anchor_text_datas' );
		$count = ( empty( $datas ) ) ? 0 : count( $datas );
		echo '<span class="displaying-num">共 <b>' . $count . '</b> 个关键词</span>';
		echo '<span>第<select class="paginate">';
		$num = 1;
		if( $datas ) {
			$num = ceil( $count / $this->per_page );
		}
		for( $i = 1; $i <= $num; $i++ ) {
			echo '<option value="' . $i . '">' . $i . '</option>';
		}
		echo '</select>页</span>';
	}
	
	/**
	 * Refresh table datas
	 */
	function refresh_table() {
		$this->get_datas();
		
		if( empty( $this->datas ) ) {
			echo '<td colspan="6" align="center" style="height:43px;vertical-align:middle;color:red;">很抱歉，该页没有找到任何结果！</td>';
			return;
		}
		
		$serial = ( $this->pagenum - 1 ) * $this->per_page + 1;
		foreach( $this->datas as $key => $data ):
			$keyword = $key;
			$url = isset( $data[0] ) ? $data[0] : '';
			$nofollow = isset( $data[1] ) ? $data[1] : 0;
			$blank = isset( $data[2] ) ? $data[2] : 1;
			$ignore = isset( $data[3] ) ? $data[3] : 1;
			$strong = isset( $data[4] ) ? $data[4] : 0;
			$num = isset( $data[5] ) ? $data[5] : 1;
			$priority = isset( $data[6] ) ? $data[6] : 10;	
?>
		<tr id="anchor-<?php echo $serial; ?>" class="item" valign="top">
			
			<th scope="row" class="check-column">
				<label class="screen-reader-text" for="cb-select-<?php echo $serial; ?>">选择</label>
				<input id="cb-select-<?php echo $serial; ?>" class="cb-select" type="checkbox" name="anchor-select[]" value="<?php echo $keyword; ?>"/>				
			</th>
			
			<td class="column-serial"><?php echo $serial; ?></td>
			
			<td class="column-keyword">
				<strong><a class="keyword" href="<?php echo $url; ?>" target="_blank"><?php echo $keyword; ?></a></strong>
				<div class="row-actions">
					<span class="edit">编辑</span> | 
					<span class="inline hide-if-no-js delete-anchor">删除</span>
				</div>
			</td>
		
			<td class="column-url"><a class="url" href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></td>
	
			<td class="column-att">
				<?php 
					$class_nofollow = ( 1 == $nofollow ) ? '' : 'no-checked';
					$class_blank = ( 1 == $blank ) ? '' : 'no-checked';
					$class_ignore = ( 1 == $ignore ) ? '' : 'no-checked';
					$class_strong = ( 1 == $strong ) ? '' : 'no-checked';
				?>
				<input type="button" class="attr-nofollow button <?php echo $class_nofollow; ?>" value="nofollow" option="<?php echo $nofollow; ?>">
				<input type="button" class="attr-blank button <?php echo $class_blank; ?>" value="新窗口" option="<?php echo $blank; ?>">
				<input type="button" class="attr-ignore button <?php echo $class_ignore; ?>" value="忽略大小写" option="<?php echo $ignore; ?>">
				<input type="button" class="attr-strong button <?php echo $class_strong; ?>" value="加粗" option="<?php echo $strong; ?>">
				<input type="button" class="button match-num" value="匹配数量：<?php echo $num; ?>">
			</td>
									
			<td class="column-priority"><strong><?php echo $priority; ?></strong></td>		
		</tr>
<?php
			$serial++;
		endforeach;
	}
	
	/**
	 * Ajax for check datas
	 */
	function ajax_anchor_init() {
		if( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], DXSEO_PRE . '_anchor_text'  ) )
			die();
		$this->init();
		if( ! empty( $this->datas ) )
			echo 'yes';
		die();	
	}
	
	/**
	 * Ajax for paginate
	 */
	function ajax_anchor_paginate() {
		if( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], DXSEO_PRE . '_anchor_text'  ) )
			die();
		$this->paginate();
		die();		
	}	
	
	/**
	 * Ajax for refresh table datas
	 */
	function ajax_anchor_refresh() {
		if( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], DXSEO_PRE . '_anchor_text'  ) )
			die();
		$this->refresh_table();		
		die();		
	}
	
	/**
	 * Sanitize input
	 */
	function sanitize_input( $input, $type ) {
		switch( $type ) {
			case 'keyword': {
				$input = str_replace( array( "\'", '\"', '\\' ), '', $input );
				$input = (string) sanitize_text_field( $input );
				break;
			}
			case 'url': $input = (string) esc_url( $input ); break;
			case 'num': $input = (int) absint( $input ); break;
			case 'priority': $input = (int) absint( $input ); break;
		}
		return $input;
	}
	
	/**
	 * Check datas exists or not
	 */
	function datas_exists( $input ) {
		if( $this->priorities && $input ) {
			$keywords = array_keys( $this->priorities );
			if( in_array( $input, $keywords ) )
				return true;
		}
		return false;
	}
	
	/**
	 * Ajax for add to datas
	 */
	function ajax_anchor_update() {
		if( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], DXSEO_PRE . '_anchor_text'  ) )
			die();
		
		// Get $_POST value
		$keyword = isset( $_POST['keyword'] ) ? $this->sanitize_input( $_POST['keyword'], 'keyword' ) : '';
		$url = isset( $_POST['url'] ) ? $this->sanitize_input( $_POST['url'], 'url' ) : '';
		$nofollow = isset( $_POST['nofollow'] ) ? $_POST['nofollow'] : 0;
		$blank = isset( $_POST['blank'] ) ? $_POST['blank'] : 0;
		$ignore = isset( $_POST['ignore'] ) ? $_POST['ignore'] : 0;
		$strong = isset( $_POST['strong'] ) ? $_POST['strong'] : 0;
		$num = isset( $_POST['num'] ) ? $this->sanitize_input( ( $_POST['num'] ), 'num' ) : 1;
		$priority = isset( $_POST['priority'] ) ? $this->sanitize_input( $_POST['priority'], 'priority' ) : 0;
		$check = isset( $_POST['check'] ) ? $_POST['check'] : 0;
		
		// Update option for datas
		if( ! empty( $keyword ) && ! empty( $url ) ) {
			$this->init();
			if( $check && $this->datas_exists( $keyword ) )
				die( 'exists' );			// Check datas exists	
			$this->datas[ $keyword ] = array( $url, $nofollow, $blank, $ignore, $strong, $num, $priority );
			update_option( $this->datas_name, $this->datas );			
			$this->priorities[ $keyword ] = $priority;
			update_option( $this->priorities_name, $this->priorities );
		}
		$this->refresh_table();
		
		// Update option for att defaults
		update_option( DXSEO_PRE . '_anchor_att_defaults', array( $nofollow, $blank, $ignore, $strong, $num, $priority ) );
		
		die();
	}
	
	/**
	 * Ajax for delete anchor from datas
	 */
	function ajax_anchor_delete() {
		if( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], DXSEO_PRE . '_anchor_text'  ) )
			die();
			
		$this->init();	
		$keyword = isset( $_POST['keyword'] ) ? $_POST['keyword'] : '';
		if( $this->datas && $keyword && isset( $this->datas[ $keyword ] ) ) {
			unset( $this->datas[ $keyword ] );
			update_option( $this->datas_name, $this->datas );
			if( isset( $this->priorities[ $keyword ] ) )
				unset( $this->priorities[ $keyword ] );
			update_option( $this->priorities_name, $this->priorities );
		}
		die();		
	}
	
	/**
	 * Ajax for delete anchor from datas
	 */
	function ajax_anchor_delete_all() {
		if( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], DXSEO_PRE . '_anchor_text'  ) )
			die();
		
		$this->init();
		$keyword = isset( $_POST['keyword'] ) ? trim( $_POST['keyword'], '%,%' ) : '';
		if( $keyword && $this->datas) {
			$keywords = explode( '%,%', $keyword );
			if( $keywords ) {
				foreach( $keywords as $key ) {
					if( isset( $this->datas[ $key ] ) )
						unset( $this->datas[ $key ] );
					if( isset( $this->priorities[ $key ] ) )
						unset( $this->priorities[ $key ] );
				}
				update_option(  $this->datas_name, $this->datas  );
				update_option( $this->priorities_name, $this->priorities );	
			}
		}
		die();	
	}
	
	/**
	 * Ajax for destroy all datas
	 */
	function ajax_anchor_destroy_datas() {
		if( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], DXSEO_PRE . '_anchor_text'  ) )
			die();
		$this->init();
		delete_option( $this->datas_name );	
		delete_option( $this->priorities_name );
		delete_option( DXSEO_PRE . '_anchor_att_defaults' );
		die( 'finished' );
	}
	
}

new DX_Seo_Do_Anchor_Datas;