<?php get_header();?>

<div id="container" <?php $flash_on = get_option('DX-Eblr-flash-index'); if($flash_on[0]=='on'): ?>style="margin-top:20px;"<?php endif; ?> <?php post_class(); ?>>

	<div id="default_inside">	
		
		<p class="title"></p><span class="fast"></span>		
		<div id="default_conter">
			<h1 style="background: none; border: none;">404错误！很抱歉，该页面不存在！</h1>
			<div id="news_concern"></div>
		</div>		

	</div>

	<?php get_sidebar(); ?>
	
	<div class="clear"></div>

</div>

<?php get_footer(); ?>