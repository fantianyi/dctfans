<DIV class="abt_rt_btm">
<A id="xcLeft" class="fl to_lf" href="javascript:void(0)"><IMG alt="向右" src="<?php bloginfo('template_directory'); ?>/images/to_lf2.gif"></A> 
<DIV id="xc_con" class="fl">
<?php 
	query_posts( array( 'cat'=>$cid, 'posts_per_page'=>8, 'ignore_sticky_posts'=>true ) );
	while( have_posts() ): the_post(); 
?>
<DL><DT><A href="<?php the_permalink(); ?>" target="_blank"><?php wpjam_post_thumbnail($size=array(162,138), $crop=1,$class="wp-post-image"); ?></A></DT><DD><A href="<?php the_permalink(); ?>" target="_blank"><?php echo mb_strimwidth( get_the_title(),0,30, '', get_bloginfo( 'charset' ) ); ?></A> </DD></DL>
<?php endwhile; wp_reset_query(); ?>
</DIV>
<A id="xcRight" class="fr to_rt" href="javascript:void(0)"><IMG alt="向左" src="<?php bloginfo('template_directory'); ?>/images/to_rt2.gif"></A> 
<DIV class="clear"></DIV>
</DIV>
<SCRIPT language=javascript type=text/javascript>
	new ScrollPicleft('xc_con', 'xcRight', 'xcLeft', 570, 190, 10, 10, true, 3);
</SCRIPT>
</DIV>