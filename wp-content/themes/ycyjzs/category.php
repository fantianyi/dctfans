<?php

$tmp = get_option( '_dxwp_cat_template_'.get_query_var('cat') );

switch( $tmp ){
	case 'anli': include( 'cat-anli.php' ); break;
	case 'fenbu': include( 'cat-fenbu.php' ); break;
	case 'zzhj': include( 'cat-zzhj.php' ); break;
	case 'zhaopin': include( 'cat-zhaopin.php' ); break;
	case 'zjgd': include( 'cat-zjgd.php' ); break;
	case 'khxs': include( 'cat-khxs.php' ); break;
	default : include( 'cat-default.php' ); break;
}