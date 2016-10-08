<?php get_header();?>

<div class="sidbar fl">
  <div class="nylefts1 fl mt10">
	<h4>推荐文章</h4>
	<?php include( 'parts/index-recommend.php' ); ?>
  </div>
  <div class="clear"></div>
</div>

<div class="nyrights fr mt10">
	<div class="mianbaoxue"><span class="fast">当前位置：<?php $DX_EBLR->crumbs(); ?></span></div>
	<div class="nymain">
		<div id="news-content">
		  <ul>
			<?php while( have_posts() ): the_post(); ?> 
			<li>
			<h2><a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>'><?php the_title(); ?></a></h2>
			<span>发表日期：<?php the_time('Y-m-d'); ?></span><span>阅读次数：<?php if(function_exists('custom_the_views') ) custom_the_views($post->ID); ?>次</span>
			<p><?php echo mb_strimwidth(strip_tags($post->post_content),0,250,'...');?><span style="color:#A15501;">阅读详情 »</span></p>
			</li>
			<?php endwhile; ?>
		  </ul>
		</div>
	<div class="page_navi"><?php par_pagenavi(9); ?></div> 
	</div>
</div>
<div class="clear"></div>
</div>
</div>
<?php get_footer(); ?>





