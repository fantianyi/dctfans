<?php get_header();?>

<div class="sidbar fl">
<div class="nylefts2 fl mt10">
	<h4>委员会</h4>
	<?php $cid = get_option( 'DX-Eblr-index-clsjs' ); include( 'parts/index-clsjs.php' ); ?>
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