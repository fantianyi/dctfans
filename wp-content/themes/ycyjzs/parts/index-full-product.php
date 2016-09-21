<?php $cid = get_option( 'DX-Eblr-index-6' ); ?>

<div id="product">

	<h2><?php echo get_cat_name( $cid ); ?></h2><span class="more"><a href="<?php echo get_category_link( $cid ); ?>"><img style="border: none;" src="<?php bloginfo( 'template_url' ); ?>/images/more.gif" alt="查看更多" /></a></span>
	
	<div id="commend">
	
		<div id="scroll">
		
			<div id="scroll-box">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr align="center">
					<?php query_posts( array( 'cat'=>$cid, 'ignore_sticky_posts'=>true, 'posts_per_page'=>10 ) ); while( have_posts() ): the_post(); ?>
						<td class="scrollpic"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo $DX_EBLR->tmb(); ?>
			<h3><?php echo mb_strimwidth( get_the_title(), 0, 20, '', get_bloginfo( 'charset' ) ); ?></h3></a></td>		
					<?php endwhile; wp_reset_query(); ?>
					</tr>
				</table>
			</div>
				
		<script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/js/scroll.js"></script>
		<script type="text/javascript">
		var sc=new Scroll("scroll-box",963,198,20,0);
		sc.play();
		</script>
		
		</div>

	</div>
	
</div>