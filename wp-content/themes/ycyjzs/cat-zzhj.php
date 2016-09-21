<?php get_header();?>

<div class="sidbar fl">
<div class="nylefts1 fl mt10">
	<h4>关于艺嘉</h4>
	<?php 
		$top_nav = wp_nav_menu( array( 'theme_location'=>'pagecl', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'celanull', 'echo'=>false, 'after'=>'' ) );
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
		<ul class="anlipic">
			<?php while( have_posts() ): the_post(); ?> 
			<li>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php wpjam_post_thumbnail($size=array(210,170), $crop=1,$class="wp-post-image"); ?></a>
			<p><a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>'><?php the_title(); ?></a></p>
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