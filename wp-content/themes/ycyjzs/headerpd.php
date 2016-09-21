<?php
$post = $wp_query->post;
if ( is_home() ) {
include(TEMPLATEPATH . '/header/homeflash.php');
}
elseif ( is_page(array(about,wenhua,licheng)) ) {
include(TEMPLATEPATH . '/header/about.php');
}
elseif ( is_category(array(huanjing,zizhi,zhaopin)) ) {
include(TEMPLATEPATH . '/header/about.php');
}
elseif ( is_category( array(news,dongtai,huodong,zishi,wenda) ) ) {
include(TEMPLATEPATH . '/header/news.php');
}
elseif ( is_category( array(fenbu,gfjpb,gljpb,qjjpb,dqljpb,zwqjd,gzsyb) ) ) {
include(TEMPLATEPATH . '/header/fenbu.php');
}
elseif ( is_category( array(anli,fengge,huxing,mianji,kongjian) ) ) {
include(TEMPLATEPATH . '/header/anli.php');
}
elseif ( is_page('shipin') ) {
include(TEMPLATEPATH . '/header/shipin.php');
}
elseif ( is_page('liucheng') ) {
include(TEMPLATEPATH . '/header/fuwu.php');
}
elseif ( is_category( array(khxs,) ) ) {
include(TEMPLATEPATH . '/header/fuwu.php');
}
else {
include(TEMPLATEPATH . '/header/default.php');
} 
?>