<?php get_header();?>

<div class="sidbar fl">
<div class="nylefts1 fl mt10">
	<h4>客户心声</h4>
	<?php 
		$top_nav = wp_nav_menu( array( 'theme_location'=>'fwlccl', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'celanull', 'echo'=>false, 'after'=>'' ) );
		$top_nav = str_replace( "</li>\n</ul>", "</li>\n</ul>", $top_nav );
		echo $top_nav;
	?>
</div>
<div class="nylefts2 fl mt10">
	<h4>设计团队</h4>
	<?php $cid = get_option( 'DX-Eblr-index-clsjs' ); include( 'parts/index-clsjs.php' ); ?>
</div>
<div class="nylefts2 fl mt10">
	<h4>推荐设计</h4>
	<?php $cid = get_option( 'DX-Eblr-index-clsjt' ); include( 'parts/index-clsjt.php' ); ?>
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
	</div>
</div>

<div class="clear"></div>
</div>
</div>
<?php get_footer(); ?>






