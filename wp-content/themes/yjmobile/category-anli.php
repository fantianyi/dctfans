<?php get_header(); ?>
	<div class="g-bd">
    <h3 class="m-btit"><?php bread_nav('>>');?></h3>
		<div class="m-con-p">
            <ul>
            <?php while( have_posts() ): the_post(); ?>
            <li><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php wpjam_post_thumbnail($size=array(144,100), $crop=1,$class="wp-post-image"); ?></a><br>
            <?php echo mb_strimwidth( get_the_title(), 0, 40, '', get_bloginfo( 'charset' ) ); ?></li>
			<?php endwhile; ?>
            </ul>
            <div class="clear"></div>
        </div>
	</div>
	<div class="page_navi"><?php par_pagenavi(2); ?></div> 
<?php get_footer(); ?>