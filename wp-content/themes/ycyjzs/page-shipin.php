<?php
/*
Template Name: 视频页面
*/
?>
<?php get_header();?>
<div class="sidbar fl">
<div class="nylefts1 fl mt10">
	<h4>企业视频</h4>
	<ul class="celanull">
	<li id="menu-item"><a href="http://www.ycyjzs.com/shipin">企业视频</a></li>
	</ul>
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
		<div id="news_concern"><?php the_content(); ?><div class="clear"></div></div>
	</div>
</div>

<div class="clear"></div>
</div>
</div>
<?php get_footer(); ?>