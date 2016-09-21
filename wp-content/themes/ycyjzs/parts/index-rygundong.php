	<div class="abt_ct"> 
		<h4 class="abt_btm_top">
			<a href="<?php echo get_category_link( $cid ); ?>" title="<?php echo get_cat_name( $cid ); ?>"><?php echo get_cat_name( $cid ); ?></a>
		</h4><span class="more"><a href="<?php echo get_category_link( $cid ); ?>"><img style="border: none;" src="<?php bloginfo( 'template_url' ); ?>/images/more_ic.gif" alt="查看更多" /></a></span>
		<div class="abt_btm">
			<a href="javascript:" class="to_lf fl" id="Left_d"><img src="<?php bloginfo('template_directory'); ?>/images/to_lf.gif" alt=""></a>
		<div style="width: 900px;margin-left: 8px; overflow: hidden;" id="ISL" class="fl">
			<div style="float: left;">
			<?php query_posts( array( 'cat'=>$cid, 'posts_per_page'=>8, 'ignore_sticky_posts'=>true ) );while( have_posts() ): the_post(); ?>
                <dl>
                    <dt><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank"><?php wpjam_post_thumbnail($size=array(208,150), $crop=1,$class="wp-post-image"); ?></a></dt>
                    <dd>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank">
                            <?php echo mb_strimwidth( get_the_title(),0,30, '', get_bloginfo( 'charset' ) ); ?></a></dd>
                </dl>
            <?php endwhile; wp_reset_query(); ?>
			</div>
		</div>
			<a href="javascript:" class="to_rt fr" id="Right_d"><img src="<?php bloginfo('template_directory'); ?>/images/to_rt.gif" alt=""></a>
		<div class="clear"></div>
		</div>
<script language="javascript" type="text/javascript">
    var scrollPic_02 = new ScrollPic();
    scrollPic_02.scrollContId = "ISL"; // 内容容器ID
    scrollPic_02.arrLeftId = "Left_d"; //左箭头ID
    scrollPic_02.arrRightId = "Right_d"; //右箭头ID
    scrollPic_02.frameWidth = 900; //显示框宽度
    scrollPic_02.pageWidth = 233; //翻页宽度
    scrollPic_02.speed = 8; //移动速度(单位毫秒，越小越快)
    scrollPic_02.space = 20; //每次移动像素(单位px，越大越快)
    scrollPic_02.autoPlay = true; //自动播放
    scrollPic_02.autoPlayTime = 7; //自动播放间隔时间(秒)
    scrollPic_02.initialize(); //初始化						
</script>
	</div>