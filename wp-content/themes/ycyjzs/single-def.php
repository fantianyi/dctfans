<?php get_header();?>

<div class="sidbar fl">
  <div class="nylefts2 fl mt10">
	<h4>推荐文章</h4>
	<?php include( 'parts/index-recommend.php' ); ?>
  </div>
  <div class="clear"></div>
</div>

<div class="nyrights fr mt10">
	<div class="mianbaoxue"><span class="fast">当前位置：<?php $DX_EBLR->crumbs(); ?></span></div>
	<div class="nymain">
		<h2 style="background: none;"><?php the_title(); ?></h2>
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