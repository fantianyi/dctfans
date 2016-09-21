jQuery(document).ready(function($){
	
	/* Variables */
	anchorNonce = $( '#anchor_nonce' ).val();
	loadingImg = 'show';
	checkDatas = 1;
	
	/* ------------------------------------ Execute ------------------------------------ */
	
	// datas init
	init();
	
	// update anchor
	$( '#da-button-add' ).click(function(){
		update_data();
	});
	
	// Restore defaults
	$( '#da-button-default' ).click(function(){
		cleanAll();
		refreshDatas();
	});
	
	// Form select change to ajax
	$( 'select.paginate, select.order, select.orderby' ).live( 'change', function(){
		refreshDatas();
	} );	
	
	// Click to search
	$( '.search-submit' ).click(function(){
		if( $( 'input.search-input' ).val() == '' ) {
			alert( '请输入搜索词！' );
			return;
		}
		isSearch = true;
		refreshDatas();
	});	
	
	/* Delete anchor */
	$( '.delete-anchor' ).live( 'click', function(){
		if( confirm( '确认要删除这个项目吗?操作不可逆，请谨慎操作。' ) ) {
			$(this).parents( 'tr.item' ).addClass( 'deleting' );
			$.post(
				ajaxurl, 
				{
					_nonce: anchorNonce,
					action: dxseoAnchor.pre + '_anchor_delete',
					keyword: $(this).parents( 'tr.item' ).find( '.keyword' ).text()
				}, 
				function(res){
					loadingImg = 'hide';
					refreshDatas();
					loadingImg = 'show';
				} 
			);
		}
	});	
	
	/* Click button to batch operate */
	$( '.do-action' ).click(function(){
		var sSelectValue = $(this).prev( 'select' ).val();
		if( 'delete' == sSelectValue ) {
			batchDelete();
		}			
	});
	
	/* Selected to change row class */
	$( '#the-list input.cb-select' ).live( 'change', function(){
		if( $(this).attr('checked') == 'checked' ) {
			$(this).parents( 'tr.item' ).addClass( 'item-selected' );
		} else{
			$(this).parents( 'tr.item' ).removeClass( 'item-selected' );
		}
	});
	
	/* Select all */
	$( '#dxseo-anchor .column-cb input' ).change(function(){
		if( $(this).attr('checked') == 'checked' ) {
			$('.cb-select').attr( 'checked', 'checked' );
			$( 'tr.item' ).addClass( 'item-selected' );
		} else{
			$('.cb-select').attr( 'checked', false );
			$( 'tr.item' ).removeClass( 'item-selected' );
		}		
	});
	
	/* Click to edit one */
	$( '#dxseo-anchor tr.item .edit' ).live( 'click', function(){
		click_edit_button( $(this) );
	} );
	
	/* Click to destroy datas */		
	$( '#destroy-datas' ).click(function(){
		destroy_datas();
	});
	
	/* Navigation */
	$( '#nav-wrap li.nav' ).click(function(){
		do_nav( $(this) );
	});
	
	/* Save data to change back tab */
	restore_tab();
	
	/* Click to export */
	$( '#anchor-export' ).click(function(){
		do_export();
	});
	
	/* Click to import */
	do_import();
	
	/* ------------------------------------ Functions ------------------------------------ */
	
	/* Check datas and decide to show or hide table */
	function init() {
		$.post(
			ajaxurl,
			{
				_nonce: anchorNonce,
				action: dxseoAnchor.pre + '_anchor_init'
			},
			function(res){
				if( 'yes' == res ) {
					$( 'div.operate, table.datas' ).show( 1, function(){
						paginateWrap();
						refreshDatas();					
					} );					
				}
			}
		);		
	}
	
	/* Paginate wrap */
	function paginateWrap() {
		$.post(
			ajaxurl,
			{
				_nonce: anchorNonce,
				action: dxseoAnchor.pre + '_anchor_paginate',
				search_word: $( 'input.search-input' ).val()
			},
			function(res){
				$( '.tablenav-pages' ).html(res);
				if( typeof( pageNum ) != 'undefined' )
					$( 'select.paginate' ).val( pageNum );
			}
		);
	}	
	
	/* Ajax for refresh table datas */
	function refreshDatas() {
		if( 'show' == loadingImg )
			$( '#loading-wrap' ).show();
		if( 'undefined' == typeof( isSearch ) || false == isSearch )
			pageNum = $( 'select.paginate' ).val();
		else
			pageNum = 1;
		$.post(
			ajaxurl,
			{
				_nonce: anchorNonce,
				action: dxseoAnchor.pre + '_anchor_refresh',
				order: $( 'select.order' ).val(),
				orderby: $( 'select.orderby' ).val(),
				search_word: $( 'input.search-input' ).val(),
				pagenum: pageNum
			},
			function(res) {		
				paginateWrap();
				$( '#loading-wrap' ).hide();
				$( '#the-list' ).html( res );	
			}
		);
		isSearch = false;	
	}
	
	/* Get keyword is the same or not and Set class */	
	function keyword_same( a, b ) {
		a.each(function(){
			var keyWord = $(this).find( '.keyword' ).text();
			if( keyWord == b ) {
				$(this).addClass( 'same-keyword' );
				return;
			}
		});
	}
	
	/* Check input */
	function check_input() {
		var sKeyword = $( '#da-keyword' ).val();
		var sUrl = $( '#da-url' ).val();
		var sNum = $( '#att-num' ).val();
		var sPriority = $( '#att-priority' ).val();
		if( '' == sKeyword ) {
			alert( '请输入关键词！' );
			return false;
		}
		if( '' == sUrl ) {
			alert( '请输入网址url！' );
			return false;
		}
		if( '' == sNum ) {
			alert( '请输入匹配数量！' );
			return false;
		}
		if( '' == sPriority ) {
			alert( '请输入优先级！' );
			return false;
		}				
		return true;		
	}
	
	/* click update button to add or edit data */
	function update_data() {
		var sKeyword = $( '#da-keyword' ).val();
		var sUrl = $( '#da-url' ).val();
		if( check_input() ) {
			keywordDisable = $( '#da-keyword' ).attr( 'disabled' );
			if( typeof( keywordDisable ) == 'undefined' )
				$( '#loading-wrap' ).show();
			var oItem = $( '.column-keyword .keyword:contains(' + sKeyword + ')' ).parents( 'tr.item' );
			keyword_same( oItem, sKeyword );
			searchWord = '';	
			if( 'undefined' != typeof( keywordDisable ) && 'disabled' == keywordDisable ) {
				$( '.same-keyword' ).addClass( 'editing' );
				searchWord = $( 'input.search-input' ).val();
				checkDatas = 0;
			}
			if( 'undefined' == typeof( pageNum ) )
				pageNum = 1;
								
			$.post( 
				ajaxurl, 
				{
					_nonce: anchorNonce,
					action: dxseoAnchor.pre + '_anchor_add',
					keyword: sKeyword,
					url: sUrl,
					nofollow: $( '#att-nofollow:checked' ).val(),
					blank: $( '#att-blank:checked' ).val(),
					ignore: $( '#att-ignore:checked' ).val(),
					strong: $( '#att-strong:checked' ).val(),
					num: $( '#att-num' ).val(),
					priority: $( '#att-priority' ).val(),
					order: $( 'select.order' ).val(),
					orderby: $( 'select.orderby' ).val(),
					search_word: searchWord,
					pagenum: pageNum,
					check: checkDatas				
				}, 
				function(res){
					if( 'exists' == res ) {
						alert( '关键词已存在，请在以下表格中查找编辑，或者重新输入！' );
						$( '#loading-wrap' ).hide();
					} else{
						$( 'div.operate, table.datas' ).show();				
						$( '#loading-wrap' ).hide();
						$( '#da-keyword' ).val( '' ).attr( 'disabled', false ).removeClass( 'disabled' );
						$( '#da-url' ).val( '' );
						$( '#the-list' ).html( res );
						var oItem = $( '.column-keyword .keyword:contains(' + sKeyword + ')' ).parents( 'tr.item' );
						keyword_same( oItem, sKeyword );
						$( '.same-keyword' ).find( '.cb-select' ).attr( 'checked', 'checked' );
						$( '.same-keyword' ).addClass( 'item-selected' ).removeClass( 'editing' ).removeClass( 'same-keyword' );
						if( typeof( keywordDisable ) == 'undefined' ) {
							cleanAll();
							pageNum = 1;
							paginateWrap();
						}
						$( '#destroy-datas-wrap span.message' ).hide();
						checkDatas = 1;
					}
				} 
			);		
		}
	}
	
	/* Clean all form value */
	function cleanAll() {
		$( '#da-keyword' ).val( '' ).removeClass( 'disabled' ).attr( 'disabled', false );
		$( '#da-url' ).val( '' );
		$( 'input.search-input' ).val( '' );
		$( 'select.order' ).val( 'desc' );
		$( 'select.orderby' ).val( 'time' );
		$( 'select.paginate' ).val( '1' );
		$( '#destroy-datas-wrap span.message' ).hide();
	}
	
	/* Batch delete all checked */
	function batchDelete() {
		if( typeof( $( '.cb-select:checked' ).val() ) == 'undefined' ) {
			alert( '请选择要删除的项目！' );
			return false;
		}
		
		if( confirm( '确认要删除选中的这些项目吗?操作不可逆，请谨慎操作。' ) ) {
			var sKeyword = '';
			$( '.cb-select:checked' ).each(function(){
				sKeyword += $(this).val() + '%,%';
				$(this).parents( 'tr.item' ).addClass( 'deleting' );
			});
			$.post(
				ajaxurl,
				{
					_nonce: anchorNonce,
					action: dxseoAnchor.pre + '_anchor_delete_all',
					keyword: sKeyword
				},
				function( res ){
					loadingImg = 'hide';
					refreshDatas();
					$( '#cb-select-all' ).attr( 'checked', false );
					loadingImg = 'show';
				}
			);		
		}
	}
	
	/* Click edit button */
	function click_edit_button( curObj ) {
		$( '.cb-select' ).attr( 'checked', false );
		$( 'tr.item' ).removeClass( 'item-selected' );
		curObj.parents( 'tr.item' ).find( '.cb-select' ).attr( 'checked', 'checked' ).parents( 'tr.item' ).addClass( 'item-selected' );
		$(document).scrollTop(0);
		$( '#da-url' ).focus();
		
		// Add form modify
		var obj = curObj.parents( 'tr.item' );
		var sKeyword = obj.find( 'a.keyword' ).text();
		var sUrl = obj.find( 'a.url' ).text();
		var sNofollow = obj.find( '.attr-nofollow' ).attr( 'option' );
		var sBlank = obj.find( '.attr-blank' ).attr( 'option' );
		var sIgnore = obj.find( '.attr-ignore' ).attr( 'option' );
		var sStrong = obj.find( '.attr-strong' ).attr( 'option' );
		var sNum = obj.find( '.match-num' ).val();
		var sPriority = obj.find( '.column-priority' ).text();
		$( '#da-keyword' ).val( sKeyword );
		$( '#da-url' ).val( sUrl );
		$( '.add-wrap .att input' ).attr( 'checked', false );
		if( 1 == sNofollow )
			$( '#att-nofollow' ).attr( 'checked', 'checked' );
		if( 1 == sBlank )
			$( '#att-blank' ).attr( 'checked', 'checked' );
		if( 1 == sIgnore )
			$( '#att-ignore' ).attr( 'checked', 'checked' );
		if( 1 == sStrong )
			$( '#att-strong' ).attr( 'checked', 'checked' );								
		$( '#att-num' ).val( sNum.match(/\d+/g) );
		$( '#att-priority' ).val( sPriority );
		$( '#da-keyword' ).addClass( 'disabled' ).attr( 'disabled', 'disabled' );		
	}
	
	/* Click to destroy datas */
	function destroy_datas() {
		if( confirm( '将删除所有输入的数据，操作不可逆，请谨慎操作！' ) ) {
			$( '#destroy-datas-wrap span.message' ).html( '正在删除，请稍候......' ).show();
			$.post(
				ajaxurl,
				{
					_nonce: anchorNonce,
					action: dxseoAnchor.pre + '_anchor_destroy_datas'
				},
				function(res){
					if( 'finished' == res ) {
						$( 'div.operate, table.datas' ).hide();
						$( '#destroy-datas-wrap span.message' ).html( '成功删除所有数据！' );
						pageNum = 1;
					}
				}
			);
		}
	}
	
	/* Do navigation */
	function do_nav( obj ) {
		$( '#nav-wrap li.nav' ).removeClass( 'nav-selected' );
		obj.addClass( 'nav-selected' );
		$( '.menu-wrap' ).hide();
		var sTab = obj.attr( 'tab' );
		$( sTab ).show();
	}
	
	/* Save data to change back tab */
	function restore_tab() {
		var sUpdated = $( '.updated' ).html();
		if( 'undefined' != typeof( sUpdated ) && '' != sUpdated ) {
			$( '#nav-wrap .nav' ).removeClass( 'nav-selected' );
			$( '.nav-settings' ).addClass( 'nav-selected' );
			$( '.menu-wrap' ).hide();
			$( '#settings-wrap' ).show();
		}
	}
	
	/* EXport */
	function do_export() {
		$( '#port-wrap .export .message' ).html( '<span style="color:red;">正在生成csv文件，请稍候...</span>' );
		$.post(
			ajaxurl,
			{
				_nonce: anchorNonce,
				action: dxseoAnchor.pre + '_anchor_export'
			},
			function( res ){
				if( res ) {
					$( '#port-wrap .export .message' ).html( res );
				}
			}
		);		
	}
	
	/* Upload */
	function do_import() {
	/* media upload */
	var _custom_media = true,
		_orig_send_attachment = wp.media.editor.send.attachment;
	
		$( '#anchor-import' ).live( 'click', function(e) {
			var send_attachment_bkp = wp.media.editor.send.attachment;
			wp.media.editor.send.attachment = function(props, attachment){
				if ( _custom_media ) {
					$( '#anchor-import-url' ).val(attachment.url);		// Insert url
					$( '#port-wrap .import .message' ).html( '<span style="color:red;">正在导入csv文件，请稍候...</span>' );
					$.post(
						ajaxurl,
						{
							_nonce: anchorNonce,
							action: dxseoAnchor.pre + '_anchor_import',
							import_url: $( '#anchor-import-url' ).val()
						},
						function( res ){
							if( res ) {
								$( '#port-wrap .import .message' ).html( res );
								if( '没有导入任何关键词！' != res ) {
									cleanAll();
									$( 'div.operate, table.datas' ).show();
									refreshDatas();	
								}
							}
						}
					);
				} else {
					return _orig_send_attachment.apply( this, [props, attachment] );
				};
			}	
			wp.media.editor.open( $(this) );
			return false;
		});	
	}
	
});