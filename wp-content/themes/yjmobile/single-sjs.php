<?php get_header();?>
<div class="g-bd">
    <h3 class="m-btit"><?php bread_nav('>>');?></h3>
    <div class="m-con">
	<?php while( have_posts() ): the_post(); $p_id = get_the_ID(); ?>	
<div class="nylefts1sjs fl mt10">
	<h4><?php echo get_post_meta($post->ID,"sjsmz_value",true);?></h4>
	<?php wpjam_post_thumbnail($size=array(140,200), $crop=1,$class="wp-post-image"); ?>
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
	<div class="clear"></div>
        <div id="content" class="m-mcon">
		<div class="nymain">
			<div class="sjsjies"><?php echo get_post_meta($post->ID,"sjsmz_value",true);?>的介绍说明</div>
			<div id="news_concern"><?php the_content(); ?><div class="clear"></div></div>
			<div class="sjsjies"><?php echo get_post_meta($post->ID,"sjsmz_value",true);?>的作品赏析</div>
			<?php include( 'xiangguan.php' ); ?>
			<div class="clear"></div>
		</div>
			<a style="float: left;font-size: 16px;line-height: 35px;">分享到：</a><div class="bdsharebuttonbox"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div>
			<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
		</div>
    <?php endwhile; ?>
    </div>
</div>
<?php get_footer(); ?>