<?php get_header();?>

<div class="sidbar fl">
<div class="nylefts1 fl mt10">
	<h4>家装业务</h4>
	<?php 
		$top_nav = wp_nav_menu( array( 'theme_location'=>'fenbucl', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'celanull', 'echo'=>false, 'after'=>'' ) );
		$top_nav = str_replace( "</li>\n</ul>", "</li>\n</ul>", $top_nav );
		echo $top_nav;
	?>
</div>
<div class="nylefts1 fl mt10">
	<h4>团队分类</h4>
	<?php 
		$top_nav = wp_nav_menu( array( 'theme_location'=>'tdflcl', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'celanull', 'echo'=>false, 'after'=>'' ) );
		$top_nav = str_replace( "</li>\n</ul>", "</li>\n</ul>", $top_nav );
		echo $top_nav;
	?>
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
		<?php echo category_description(); ?>
		<div class="clear"></div>
		<div class="shejishizhanshi">团队展示</div>
		<ul class="fbsjs">
			<?php while( have_posts() ): the_post(); ?> 
			<li>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php wpjam_post_thumbnail($size=array(140,210), $crop=1,$class="wp-post-image"); ?></a>
			<p><a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>'><?php echo get_post_meta($post->ID,"sjsmz_value",true);?></a><span><?php echo get_post_meta($post->ID,"sjszw_value",true);?></span></p>
			</li>
			<?php endwhile; ?>
		</ul>
		<div class="page_navi"><?php par_pagenavi(9); ?></div>  
		<div class="clear"></div>
	</div>
</div>

<div class="clear"></div>
</div>
</div>
<?php get_footer(); ?>