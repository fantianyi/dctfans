<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' );?>" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php
	if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
	wp_enqueue_script( 'jquery' );
	wp_head();
?>
<link href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" rel="stylesheet" />
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js" type="text/javascript"></script>
<?php if ( is_single() ) { ?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/fancybox/fancybox.css" />   
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/fancybox/fancybox.js"></script>  
<script type="text/javascript">  
    $(document).ready(function() {  
        $(".fancybox").fancybox();  
    });  
</script>
<?php } ?> 
<?php if ( is_home() ) { ?>
<script src="<?php bloginfo('template_directory'); ?>/js/ScrollPic.js" type="text/javascript"></script><!--产品轮播-->
<script src="<?php bloginfo('template_directory'); ?>/js/MSClass.js" type="text/javascript"></script><!--文字滚动-->
<script src="<?php bloginfo('template_directory'); ?>/js/tab3.js" type="text/javascript"></script><!--首页右侧tab-->
<script src="<?php bloginfo('template_directory'); ?>/js/ScrollPicLeft.js" type="text/javascript"></script><!--公司场景左右-->
<?php } ?>
</head>
<body>
<div class="top">
    <div class="header">
        <div class="h_top">
            <span class="fr">
				<a title="快速访问DECENT中国协会官网" href="<?php bloginfo('template_directory'); ?>/Shortcut.php">收藏本站 </a>|
				<a title="在线留言" href="<?php echo get_option('home'); ?>/contact">联系我们</a>|
				<a title="帮助中心" href="<?php echo get_option('home'); ?>/category/news/wenda">帮助中心</a>|
				<a title="网站地图" href="<?php echo get_option('home'); ?>/sitemap.xml" target="_blank">网站地图</a>
				<a rel="nofollow" title="新浪微博" href="http://weibo.com/DECENT" target="_blank"><img alt="新浪微博" src="<?php bloginfo('template_directory'); ?>/images/xl.gif"></a>
				<a rel="nofollow" title="腾讯微博" href="http://t.qq.com/DECENT" target="_blank"><img alt="腾讯微博" src="<?php bloginfo('template_directory'); ?>/images/tx.gif"></a>
			</span>
			您好！欢迎来到 <?php bloginfo('name'); ?> 官方网站！
		</div>
        <div class="h_nr fl">
            <div class="h_logo fl">
                <span class="fl"><a href="<?php bloginfo( 'url' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"/><img src="<?php echo get_option( 'DX-Eblr-logo' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" /></a></span>
            </div>
        </div>
		<div id="navbar">
			<?php if(function_exists('wp_nav_menu')) {wp_nav_menu(array('theme_location'=>'main','menu_id'=>'nav','container'=>'ul'));}?>
			<script type="text/javascript"> jQuery(document).ready(function($) {$('#nav li').hover(function() {$('ul', this).slideDown(100)},function() {$('ul', this).slideUp(100)});});</script><!--二级导航js-->
		</div>
    </div>
	<div class="clear"></div>
</div>
<?php include( 'headerpd.php' );?>