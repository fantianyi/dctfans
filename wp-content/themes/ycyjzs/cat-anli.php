<?php get_header();?>

<div class="sidbar fl">
<div class="nylefts1 fl mt10">
	<h4>经典案例</h4>
	<?php 
		$top_nav = wp_nav_menu( array( 'theme_location'=>'anlicl', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'celanull', 'echo'=>false, 'after'=>'' ) );
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
		<div class="anlifenlei">
			<div class="xzfg">
				<strong>选择风格：</strong>
				<?php 
				$top_nav = wp_nav_menu( array( 'theme_location'=>'fenggedxx', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'anliul', 'echo'=>false, 'after'=>'' ) );
				$top_nav = str_replace( "</li>\n</ul>", "</li>\n</ul>", $top_nav );
				echo $top_nav;
				?>
			</div>
			<div class="xzfg">
				<strong>选择户型：</strong>
				<?php 
				$top_nav = wp_nav_menu( array( 'theme_location'=>'huxingdxx', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'anliul', 'echo'=>false, 'after'=>'' ) );
				$top_nav = str_replace( "</li>\n</ul>", "</li>\n</ul>", $top_nav );
				echo $top_nav;
				?>
			</div>
			<div class="xzfg">
				<strong>选择面积：</strong>
				<?php 
				$top_nav = wp_nav_menu( array( 'theme_location'=>'mianjidxx', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'anliul', 'echo'=>false, 'after'=>'' ) );
				$top_nav = str_replace( "</li>\n</ul>", "</li>\n</ul>", $top_nav );
				echo $top_nav;
				?>
			</div>
			<div class="xzfg">
				<strong>选择空间：</strong>
				<?php 
				$top_nav = wp_nav_menu( array( 'theme_location'=>'kjdxx', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'anliul', 'echo'=>false, 'after'=>'' ) );
				$top_nav = str_replace( "</li>\n</ul>", "</li>\n</ul>", $top_nav );
				echo $top_nav;
				?>
			</div>
			<div class="clear"></div>
		</div>
		<ul class="anlipic">
			<?php while( have_posts() ): the_post(); ?> 
			<li>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php wpjam_post_thumbnail($size=array(210,170), $crop=1,$class="wp-post-image"); ?></a>
			<p>名称：<a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>'><?php the_title(); ?></a><br><span>设计师：<a href="http://www.ycyjzh.com/tag/<?php echo get_post_meta($post->ID,"anlizz_value",true);?>" target="_blank"><?php echo get_post_meta($post->ID,"anlizz_value",true);?></a></span></p>
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