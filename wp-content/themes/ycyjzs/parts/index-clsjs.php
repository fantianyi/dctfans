<ul class="celansjs">
<?php 
	query_posts( array( 'cat'=>$cid, 'posts_per_page'=>4, 'ignore_sticky_posts'=>true ) );
	while( have_posts() ): the_post(); 
?>
	<li><a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>' target="_blank"><?php wpjam_post_thumbnail($size=array(94,140), $crop=1,$class="wp-post-image"); ?></a><h5><?php echo mb_strimwidth( get_the_title(), 0, 20, '', get_bloginfo( 'charset' ) ); ?></h5></li>
<?php endwhile; wp_reset_query(); ?>

<span class="fr"><a href="<?php echo get_category_link( $cid ); ?>" style="color: #040300;" target="_blank">&gt;&gt;更多</a></span>
</ul>