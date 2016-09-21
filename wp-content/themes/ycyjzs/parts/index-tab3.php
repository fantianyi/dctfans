<ul class="cf">
<?php 
	query_posts( array( 'cat'=>$cid, 'posts_per_page'=>9, 'ignore_sticky_posts'=>true ) );
	while( have_posts() ): the_post(); 
?>	
	<li><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="imgwrap"><?php wpjam_post_thumbnail($size=array(215,170), $crop=1,$class="wp-post-image"); ?></a>
		<p class="mt10"> <a href="<?php the_permalink(); ?>">【<?php echo mb_strimwidth( get_the_title(),0,30, '', get_bloginfo( 'charset' ) ); ?>】</a></p>
		<p class="pb10">设计师：<?php echo get_post_meta($post->ID,"anlizz_value",true);?></p>
	</li>
<?php endwhile; wp_reset_query(); ?>
</ul>