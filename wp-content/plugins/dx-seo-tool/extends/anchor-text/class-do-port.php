<?php
/**
 * Export and import datas
 * csv file 
 */
class DX_Seo_Do_Anchor_Port {
	
	/**
	 * Hooks
	 */
	function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( DXSEO_PRE . '_anchor_delete_port_file', array( $this, 'delete_port_file' ) );
		add_action( 'wp_ajax_' . DXSEO_PRE . '_anchor_export', array( $this, 'ajax_do_export' ) );
		add_action( 'wp_ajax_' . DXSEO_PRE . '_anchor_import', array( $this, 'ajax_do_import' ) );
	}
	
	/**
	 * Admin enqueue scripts
	 */
	function admin_enqueue_scripts( $hook ) {
		if( 'dx-seo_page_dxseo_anchor' == $hook && function_exists( 'wp_enqueue_media' ) )
			wp_enqueue_media();
	}
	
	/**
	 * Init
	 */
	function init() {
		$this->datas_name = DXSEO_PRE . '_anchor_text_datas';
		$this->datas = get_option( $this->datas_name );
		$this->datas = empty( $this->datas ) ? array() : $this->datas;
		$this->priorities_name = DXSEO_PRE . 'anchor_text_priority';
		$priorities = get_option( $this->priorities_name );
		$this->priorities = empty( $priorities ) ? array() : $priorities;
		$uploads = wp_upload_dir();
		$this->upload_dir = $uploads['basedir'];
		$this->upload_url = $uploads['baseurl'];
		$export = 'dxseo-anchor-text-export.csv';
		$this->export_file = $this->upload_dir . '/' . $export;
		$this->export_url = $this->upload_url . '/' . $export;
	}
	
	/**
	 * Delete export and import files
	 */
	function delete_port_file() {
		$this->init();
		
		if( file_exists( $this->export_file ) )
			@unlink( $this->export_file );
			
		$ids = get_option( DXSEO_PRE . '_anchor_import_file_ids' );	
		if( $ids ) {
			foreach( $ids as $id ) {
				wp_delete_attachment( $id, true );
			}
			delete_option( DXSEO_PRE . '_anchor_import_file_ids' );
		}
	}	
	
	/**
	 * Do datas for export
	 */
	function datas_for_export() {
		$this->export = '';
		if( $this->datas ) {			
			$this->export = "关键词,网址url,nofollow,新窗口打开,忽略大小写,加粗,匹配数量,优先级\n";
			$this->datas = array_reverse( $this->datas, true );
			foreach( $this->datas as $key => $data ) {
				$this->export .= $key;
				if( $data ) {
					foreach( $data as $att ) {
						$this->export .= ',' . $att;
					}
				}
				$this->export .= "\n";
			}
			$this->export = trim( $this->export, "\n" );
		}
	}
	
	/**
	 * Save export file
	 */
	function save_file() {
		if( $this->export ) {
			$this->export = iconv( get_bloginfo( 'charset' ), 'GBK', $this->export );
			$size = file_put_contents( $this->export_file, $this->export );
		}
	}
	
	/**
	 * Out export message
	 */
	function export_message() {
		$this->init();
		if( empty( $this->datas ) ) {
			echo '<span style="color:red;">数据为空，请输入数据后再导出！</span>';
			return;
		}
		if( file_exists( $this->export_file ) ) {
			$size = number_format( @filesize( $this->export_file ) / 1024, 2 );
			date_default_timezone_set( get_option( 'timezone_string' ) );
			$time = date( 'Y-m-d H:i:s', @filemtime( $this->export_file ) );
			echo "生成时间：<b>$time</b>； 文件大小：<b>$size KB</b>；<a href='$this->export_url' target='_blank'>点击下载文件</a>";
		}
	}	
	
	/**
	 * Ajax do export
	 */
	function ajax_do_export() {
		if( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], DXSEO_PRE . '_anchor_text'  ) )
			die();
			
		$this->init();	
		$this->datas_for_export();
		$this->save_file();
		$this->export_message();
		die();	
	}
	
	/**
	 * Check upload file
	 */
	function check_import_filetype() {
		if( $this->import_url ) {
			$this->import_file = str_replace( $this->upload_url, $this->upload_dir, $this->import_url );
			if( ! preg_match( '/\.csv$/', $this->import_file ) ) {
				die( '<span style="color:red">请导入csv文件</span>' );
			}
		}
	}
	
	/**
	 * Csv file code convert
	 */
	function code_convert( $content ) {
		$encode = mb_detect_encoding( $content, array( 'UTF-8', 'GB2312', 'GBK', 'Unicode', 'Big5' ) );
		return iconv( $encode, 'UTF-8', $content );
	}
	
	/**
	 * Check import datas
	 */
	function check_import_datas( $datas ) {
		if( ! isset( $datas[1] ) || empty( $datas[1] ) )
			die( '<span style="color:red;">数据为空！</span>' );
		if( count( explode( ',', $datas[1] ) ) != 8 )
			die( '<span style="color:red;">数据输入格式不正确，请参照示例文件！</span>' );
	}
	
	/**
	 * Sanitize
	 */
	function sanitize( $input, $type ) {
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
	 * Generate datas array from import file
	 */
	function generate_datas( $content ) {
		$this->new_num = 0;
		$this->replace_num = 0;	
		if( $content ) {
			$datas = explode( "\n", $content );
			if( $datas ) {
				unset( $datas[0] );
				if( $datas ) {
					
					$this->check_import_datas( $datas );	// Check datas
					
					$datas = array_reverse( $datas, true );
					foreach( $datas as $data ) {
						if( empty( $data ) )
							continue;
							
						$item = explode( ',', $data );
						$keyword = $this->sanitize( $item[0], 'keyword' );
						$url = $this->sanitize( $item[1], 'url' );
						$nofollow = $this->sanitize( $item[2], 'num' );
						$blank = $this->sanitize( $item[3], 'num' );
						$ignore = $this->sanitize( $item[4], 'num' );
						$strong = $this->sanitize( $item[5], 'num' );
						$num = $this->sanitize( $item[6], 'num' );
						$priority = $this->sanitize( $item[7], 'priority' );
						
						if( isset( $this->datas[ $keyword ] ) ) {
							$this->replace_num++;
						} else {
							$this->new_num++;
						}
						
						// Update datas
						$this->datas[ $keyword ] = array( $url, $nofollow, $blank, $ignore, $strong, $num, $priority );
						$this->priorities[ $keyword ] = $priority;
						update_option( $this->datas_name, $this->datas, true );
						update_option( $this->priorities_name, $this->priorities, true );																	
					}
					
				}
			}
		}
	}
	
	/**
	 * Do datas for import
	 */
	function datas_for_import() {
		if( file_exists( $this->import_file ) ) {
			$content = @file_get_contents( $this->import_file );
			$content = $this->code_convert( $content );
			$this->generate_datas( $content );
		}
	}
	
	/**
	 * Output import message
	 */
	function import_message() {
		$sum = $this->new_num + $this->replace_num;
		if( $sum > 0 )
			echo "共导入 $sum 个关键词，新增 $this->new_num 个关键词，替换 $this->replace_num 个关键词！";
		else
			echo '没有导入任何关键词！';
	}
	
	/**
	 * Get attachment id from url
	 */
	function get_attachment_id_from_url( $attachment_url = '' ) {
		global $wpdb;
		$attachment_id = false;	 
		if ( '' == $attachment_url )
			return;	 
		if ( false !== strpos( $attachment_url, $this->upload_url ) ) {	 
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
			$attachment_url = str_replace( $this->upload_url . '/', '', $attachment_url );
			$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
		}	 
		return $attachment_id;
	}
	
	/**
	 * Save import files attachment id to option
	 */
	function save_import_attachment_ids() {
		$this->import_url = isset( $_POST['import_url'] ) ? $_POST['import_url'] : '';
		if( $this->import_url ) {
			$aid = $this->get_attachment_id_from_url( $this->import_url );			
			if( $aid ) {
				$imports = get_option( DXSEO_PRE . '_anchor_import_file_ids' );
				$imports[] = $aid;
				update_option( DXSEO_PRE . '_anchor_import_file_ids', $imports );
			}
		}
	}		
	
	/**
	 * Ajax do import
	 */
	function ajax_do_import() {
		if( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], DXSEO_PRE . '_anchor_text'  ) )
			die();
			
		$this->init();
		$this->save_import_attachment_ids();
		$this->check_import_filetype();	
		$this->datas_for_import();
		$this->import_message();
		die();	
	}	
	
}

new DX_Seo_Do_Anchor_Port;