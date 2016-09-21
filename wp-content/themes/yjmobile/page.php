<?php get_header();?>
<div class="g-bd">
    <h3 class="m-btit"><?php bread_nav('>>');?></h3>
    <div class="m-con">
	<?php while( have_posts() ): the_post(); $p_id = get_the_ID(); ?>	
        <div class="u-mtit f-tc">
            <h1 class="s-wc"><?php the_title(); ?></h1>
        </div>
        <div id="content" class="m-mcon">
			<?php the_content(); ?>
			<div class="clear"></div>
		</div>
    <?php endwhile; ?>
    </div>
</div>
<?php get_footer(); ?>