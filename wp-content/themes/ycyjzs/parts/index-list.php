<h2><?php echo get_cat_name( $cid ); ?></h2><span class="more"><a href="<?php echo get_category_link( $cid ); ?>"><img style="border: none;" src="<?php bloginfo( 'template_url' ); ?>/images/more.gif" alt="查看更多" /></a></span>

<ul class="list">

	<img src="<?php bloginfo( 'template_url' ); ?>/images/news-<?php echo $img_id; ?>.jpg"/>
<?php 
	query_posts( array( 'cat'=>$cid, 'posts_per_page'=>5, 'ignore_sticky_posts'=>true ) );
	while( have_posts() ): the_post(); 
?>	
	<li>
		<a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>'><?php echo mb_strimwidth( get_the_title(),0,30, '', get_bloginfo( 'charset' ) ); ?></a>
	</li>
	
<?php endwhile; wp_reset_query(); ?>
</ul>