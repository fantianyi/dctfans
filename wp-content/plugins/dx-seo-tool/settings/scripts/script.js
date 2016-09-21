jQuery(document).ready(function($){
	
	/* Ibutton */
	$( '.ibutton' ).iButton();
	
	/* search_keywords option show or hidden */
	sKeywordsDisplay();
	function sKeywordsDisplay() {
		doDisplay( getMetaVal(), 0 );
		$( '#web589seo_switch_meta' ).change(function(){
			doDisplay( getMetaVal(), 1000 );
		});
	}
	function getMetaVal() {
		var obj = $( '#web589seo_switch_meta:checked' );
		var val = obj.val();
		if( 'undefined' == typeof( val ) )
			return '';
		else
			return val;
	}	
	function doDisplay( metaVal, duration ) {
		var obj = $( '#dxseo-main-settings .form-table tr:eq(1)' );
		if( 'on' == metaVal ) {
			obj.show( duration );
		} else {
			obj.hide( duration );
		}
	}
	
});