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
		<div class="con Recruitment">
            <div class="title">
                <em class="g2">职位名称</em>
                <em class="g3">工作地点</em>
                <em class="g4">招聘人数</em>
                <em class="g5">发布时间</em>
                <em class="g6">查看详情</em>
            </div>
			<div class="clear"></div>
			<?php while( have_posts() ): the_post(); ?>
            <div class="zhaoping">
				<em class="z2"><?php the_title(); ?></em>
				<em class="z3"><?php echo get_post_meta($post->ID,"didian_value",true);?></em>
				<em class="z4"><?php echo get_post_meta($post->ID,"renshu_value",true);?>人</em>
				<em class="z5"><?php the_time('Y-m-d'); ?></em>
				<em class="z6"><?php echo get_post_meta($post->ID,"beizhu_value",true);?></em>
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