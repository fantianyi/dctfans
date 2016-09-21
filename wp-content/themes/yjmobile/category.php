<?php get_header(); ?>
	<div class="g-bd">
    <h3 class="m-btit"><?php bread_nav('>>');?></h3>
		<div class="m-con-3">
            <ul>
            <?php while( have_posts() ): the_post(); ?>
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
            <li class="first"><?php echo mb_strimwidth( get_the_title(), 0, 40, '', get_bloginfo( 'charset' ) ); ?><span></span></li>
            </a>
			<?php endwhile; ?>
            </ul>
            <div class="clear"></div>
        </div>
	</div>
	<div class="page_navi"><?php par_pagenavi(2); ?></div> 
<?php get_footer(); ?>