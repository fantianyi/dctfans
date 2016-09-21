<div class="abt_lf fl">
  <div class="pub_top">
   <a class="fr" title="更多<?php echo get_cat_name( $cid ); ?>" href="<?php echo get_category_link( $cid ); ?>" target="_blank"><img alt="更多<?php echo get_cat_name( $cid ); ?>" src="<?php bloginfo('template_directory'); ?>/images/more_ic.gif"></a> 
   <h3><a href="<?php echo get_category_link( $cid ); ?>" target="_blank"><?php echo get_cat_name( $cid ); ?></a></h3>
  </div>
  <div id="went_con" class="abt_lf_ct" style="overflow: hidden; width: 265px; height: 447px;">
  <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
	<tbody>
		<tr><td>
		<?php 
		query_posts( array( 'cat'=>$cid, 'posts_per_page'=>8, 'ignore_sticky_posts'=>true ) );
		while( have_posts() ): the_post(); 
		?>	
 		 <dl>
			<dt><a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>'><?php echo mb_strimwidth( get_the_title(),0,40, '', get_bloginfo( 'charset' ) ); ?></a></dt>
			<dd><?php echo mb_strimwidth(strip_tags($post->post_content),0,100,'...');?></dd>
		</dl>
		<?php endwhile; wp_reset_query(); ?>
		</td></tr>
	</tbody>
   </table>
   </div>
</div>
  <script type="text/javascript">
	if ($("#went_con dl").length > 3)
		new Marquee("went_con", 0, 1, 265, 447, 50, 0, 52); 
  </script>