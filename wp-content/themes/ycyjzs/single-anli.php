<?php get_header();?>

<div class="sidbar fl">
<div class="nylefts1 fl mt10">
	<h4>新闻动态</h4>
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
<div class="clear"></div>
</div>

<div class="nyrights fr mt10">
	<div class="mianbaoxue"><span class="fast">当前位置：<?php $DX_EBLR->crumbs(); ?></span></div>
	<div class="nymain">
		<div class="anlixinxi">
			<span>作品名：<b><?php the_title(); ?></b></span>
			<span>作品属性：<b><?php the_category(', ') ?></b></span>
			<span>设计师：<b><a target="_blank" href="http://www.ycyjzh.com/tag/<?php echo get_post_meta($post->ID,"anlizz_value",true);?>"><?php echo get_post_meta($post->ID,"anlizz_value",true);?></a></b>  ← 点击了解设计师更多作品</span>
			<div class="clear"></div>
		</div>
		<div id="news_concern"><?php the_content(); ?><div class="clear"></div></div>
		<ul id="up_down">
			<li>【<?php previous_post_link( '上一篇：%link', '%title', true );?>】</li>
			<li>【<?php next_post_link( '下一篇：%link', '%title', true );?>】</li>
		</ul>
		<div class="clear"></div>
	</div>
</div>

<div class="clear"></div>
</div>
</div>
<?php get_footer(); ?>