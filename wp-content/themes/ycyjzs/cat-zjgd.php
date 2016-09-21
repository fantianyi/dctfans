<?php get_header();?>

<div class="sidbar fl">
<div class="nylefts1 fl mt10">
	<h4>在线工地</h4>
	<?php 
		$top_nav = wp_nav_menu( array( 'theme_location'=>'sgtjcl', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'celanull', 'echo'=>false, 'after'=>'' ) );
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
		<div class="con Recruitment">
            <div class="title">
                <em class="g2s">楼盘</em>
                <em class="g3s">客户</em>
                <em class="g4s">设计面积</em>
                <em class="g5s">项目经理</em>
                <em class="g6s">阶段</em>
            </div>
			<div class="clear"></div>
			<?php while( have_posts() ): the_post(); ?>
            <div class="zhaoping">
				<em class="z2s"><?php the_title(); ?></em>
				<em class="z3s"><?php echo get_post_meta($post->ID,"zjgdkh_value",true);?></em>
				<em class="z4s"><?php echo get_post_meta($post->ID,"zjgdmj_value",true);?></em>
				<em class="z5s"><?php echo get_post_meta($post->ID,"zjgdjl_value",true);?></em>
				<em class="z6s"><?php echo get_post_meta($post->ID,"zjgdjd_value",true);?></em>
            </div>
			<?php endwhile; ?>
        </div>
		<div class="page_navi"><?php par_pagenavi(9); ?></div>  
		<div class="clear"></div>
	</div>
</div>

<div class="clear"></div>
</div>
</div>
<?php get_footer(); ?>