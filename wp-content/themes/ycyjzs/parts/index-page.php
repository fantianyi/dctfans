<?php 
	$pid = get_option( 'DX-Eblr-index-about' );
	query_posts( array( 'ignore_sticky_posts'=>true, 'p'=>$pid, 'posts_per_page'=>1, 'post_type'=>'page' ) );
	while( have_posts() ): the_post();
?>
 <div class="pub_top">
  <a class="fr" title="<?php echo get_the_title( $pid ); ?>" href="<?php the_permalink(); ?>" target="_blank"><img alt="<?php echo get_the_title( $pid ); ?>" src="<?php bloginfo('template_directory'); ?>/images/more_ic.gif"></a> 
  <h3><a href="<?php the_permalink(); ?>" target="_blank"><?php echo get_the_title( $pid ); ?></a></h3>
 </div>
 <div class="abt_rt_ct">
  <div class="abt_rt_up">
   <dl>
     <dt><embed src="<?php echo get_option( 'DX-Eblr-index-aboutsp' ); ?>" allowfullscreen="true" quality="high" width="285" height="203" align="middle" allowscriptaccess="always" type="application/x-shockwave-flash"> </dt>
     <dd>
     <h4><a href="<?php the_permalink(); ?>" target="_blank"><?php bloginfo('name'); ?>简介</a></h4>
		<p>&nbsp;&nbsp;&nbsp; <?php echo get_the_excerpt(); ?></p>
		<span><a title="查看详细" href="<?php the_permalink(); ?>" target="_blank">【查看详细】</a></span> 
  		<div class="clear"></div>
     </dd>
   </dl>
  </div>
<?php endwhile; wp_reset_query(); ?>