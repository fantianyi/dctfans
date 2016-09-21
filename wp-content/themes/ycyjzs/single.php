<?php
$post = $wp_query->post;
if ( in_category('180') ) {
include(TEMPLATEPATH . '/header/homeflash.php');
}
elseif ( in_category(array(gfjpb,gljpb,qjjpb,dqljpb,zwjpb,gzsyb,founder)) ) {
include(TEMPLATEPATH . '/single-sjs.php');
}
elseif ( in_category(array(osfg,zsfg,xdfg,keting,bieshu,)) ) {
include(TEMPLATEPATH . '/single-anli.php');
}
else {
include(TEMPLATEPATH . '/single-def.php');
} 
?>