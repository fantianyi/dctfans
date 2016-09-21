<?php get_header();?>
<?php while( have_posts() ): the_post(); $p_id = get_the_ID(); ?>
<div class="sidbar fl">
<div class="nylefts1sjs fl mt10">
	<h4><?php echo get_post_meta($post->ID,"sjsmz_value",true);?></h4>
	<?php wpjam_post_thumbnail($size=array(200,300), $crop=1,$class="wp-post-image"); ?>
	<div class="sjszl">
		<span><b>设计师名：</b><?php echo get_post_meta($post->ID,"sjsmz_value",true);?></span>
		<span><b>职位级别：</b><?php echo get_post_meta($post->ID,"sjszw_value",true);?></span>
		<span><b>收费标准：</b><?php echo get_post_meta($post->ID,"sjssfbz_value",true);?></span>
		<span><b>联系方式：</b><?php echo get_post_meta($post->ID,"sjslx_value",true);?></span>
		<span><b>设计理念：</b><?php echo get_post_meta($post->ID,"sjsln_value",true);?></span>
		<span><b>服务过的楼盘：</b><?php echo get_post_meta($post->ID,"fwlp_value",true);?></span>
		<div class="clear"></div>
	</div>
</div>
</div>
<div class="nyrights fr mt10">
	<div class="mianbaoxue"><span class="fast">当前位置：<?php $DX_EBLR->crumbs(); ?></span></div>
	<div class="nymain">
		<div class="sjsjies"><?php echo get_post_meta($post->ID,"sjsmz_value",true);?>的介绍说明</div>
		<div id="news_concern"><?php the_content(); ?><div class="clear"></div></div>
		<div class="sjsjies"><?php echo get_post_meta($post->ID,"sjsmz_value",true);?>的作品赏析</div>
		<?php include( 'parts/index-xiangguan.php' ); ?>
		<div class="clear"></div>
	</div>
</div>
<?php endwhile; ?>
<div class="clear"></div>
</div>
</div>
<?php get_footer(); ?>