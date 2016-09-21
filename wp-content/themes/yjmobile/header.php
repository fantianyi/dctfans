<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' );?>" />
<title>
<?php 
if ( is_home() ) {echo bloginfo( 'name' );$paged = get_query_var('paged'); if($paged > 1) printf(' | 第%s页',$paged);} elseif ( is_archive() ) {echo wp_title('');  if($paged > 1) printf(' | 第%s页',$paged);    echo ' | ';    bloginfo( 'name' );} elseif ( is_search() ) {echo '&quot;'.wp_specialchars($s).'&quot;的搜索结果 | '; bloginfo( 'name' );} elseif ( is_tag() ) {echo wp_title('TAG:');if($paged > 1) printf(' - 第%s页',$paged);echo ' | '; bloginfo( 'name' );}  elseif ( is_404() ) {echo '未找到内容 | '; bloginfo( 'name' );} else {echo wp_title( ' | ', false, right )  ; bloginfo( 'name' );} ?>
</title>
<link href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" rel="stylesheet" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="blue">
<meta name="format-detection" content="telephone=no">
<link rel="apple-touch-icon-precomposed" href="<?php bloginfo('template_directory'); ?>/images/touch-icon.png">
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.js"></script>
<?php wp_head(); ?>
</head>
<body>
    <div class="wrap"> 
<header class="g-hd">
    <a href="<?php echo get_option('home'); ?>" name="logo" class="m-logo">
        <img src="<?php bloginfo('template_directory'); ?>/images/logo.png" width="114" alt="" title="" class="loaded">
    </a>
    <p class="m-tel">
    <span class="u-text">十二年的行业经验</span>
        <i class="u-tel"><em class="z-ln-1 s-ln-1"></em><em class="z-ln-2 s-ln-2"></em></i>
        <a href="tel:13905105798" class="u-num s-num">13905105798</a><br> 
    </p>
</header>
<nav class="g-nav">
    <ul>
        <li><a href="http://www.ycyjzh.com/">艺嘉首页 </a></li>
        <li><a href="http://www.ycyjzh.com/about">走进艺嘉</a></li>
        <li><a href="http://www.ycyjzh.com/category/news/dongtai">企业新闻</a></li>
        <li><a href="http://www.ycyjzh.com/category/huanjing">公司环境</a></li>
        <li><a href="http://www.ycyjzh.com/category/fenbu">设计师展示</a></li>
        <li><a href="http://www.ycyjzh.com/category/anli">设计作品</a></li>
        <li><a href="http://www.ycyjzh.com/category/zizhi">荣誉资质</a></li>
        <li><a href="http://www.ycyjzh.com/contact">联系我们 </a></li>
    </ul>
</nav>