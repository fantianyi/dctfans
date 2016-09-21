<?php require_once( 'nav.php' );	// Nav ?>
<div id="dxseo-anchor" class="menu-wrap">
<?php
	require_once( 'add.php' );		// Update data
	require( 'operate.php' );		// Edit、search、paginate
	require_once( 'table.php' );	// Datas table
?>
	<br class="clear">
</div>

<?php require_once( 'port.php' );		// Export、import datas ?>

<?php wp_nonce_field( DXSEO_PRE . '_anchor_text', 'anchor_nonce', false ); ?>