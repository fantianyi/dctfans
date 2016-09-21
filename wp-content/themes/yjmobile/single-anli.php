<?php get_header();?>
<div class="g-bd">
    <h3 class="m-btit"><?php bread_nav('>>');?></h3>
    <div class="m-con">
	<?php while( have_posts() ): the_post(); $p_id = get_the_ID(); ?>	
        <div class="anlixinxi">
			<span>作品名：<b><?php the_title(); ?></b></span>
			<span>作品属性：<b><?php the_category(', ') ?></b></span>
			<span>设计师：<b><a href="http://www.ycyjzs.com/tag/<?php echo get_post_meta($post->ID,"anlizz_value",true);?>"><?php echo get_post_meta($post->ID,"anlizz_value",true);?></a></b></span>
			<div class="clear"></div>
		</div>
        <div id="content" class="m-mcon">
			<?php the_content(); ?>
			<div class="clear"></div>
			<a style="float: left;font-size: 16px;line-height: 35px;">分享到：</a><div class="bdsharebuttonbox"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div>
			<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
		</div>
    <?php endwhile; ?>
    </div>
</div>
<?php get_footer(); ?>