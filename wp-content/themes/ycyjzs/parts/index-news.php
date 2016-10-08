<div class="pub_top">
<a class="fr" title="<?php echo get_cat_name( $cid ); ?>" href="<?php echo get_category_link( $cid ); ?>" target="_blank">
<img alt="<?php echo get_cat_name( $cid ); ?>" src="<?php bloginfo('template_directory'); ?>/images/more_ic.gif"></a> 
<h3><a href="<?php echo get_category_link( $cid ); ?>" target="_blank"><?php echo get_cat_name( $cid ); ?></a></h3>
</div>
<div class="info_ct">
<!--<a class="info_img"><img src="<?php bloginfo( 'template_url' ); ?>/images/news-<?php echo $img_id; ?>.jpg" width="283" height="96"></a>-->
<ul>
<?php 
	query_posts( array( 'cat'=>$cid, 'posts_per_page'=>7, 'ignore_sticky_posts'=>true ) );
	while( have_posts() ): the_post(); 
?>      
<li><span><?php the_time('Y-m-d'); ?></span><a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" target="_blank"><?php echo mb_strimwidth( get_the_title(),0,32, '', get_bloginfo( 'charset' ) ); ?></a></li>
<?php endwhile; wp_reset_query(); ?>         
</ul>
</div>