<?php
$post = $wp_query->post;
if ( in_category('1180') ) {
include(TEMPLATEPATH . '/header/homeflash.php');
}
elseif ( in_category(array(gfjpb,gljpb,qjjpb,dqljpb,zwjpb,gzsyb,shejituandui)) ) {
include(TEMPLATEPATH . '/single-sjs.php');
}
elseif ( in_category(array(osfg,zsfg,xdfg)) ) {
include(TEMPLATEPATH . '/single-anli.php');
}
else {
include(TEMPLATEPATH . '/single-def.php');
} 
?>