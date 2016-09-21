<?php get_header(); ?>
<div class="g-adv j-slide-auto">
        <div class="sclwrap_box" style="position: relative; overflow: hidden; visibility: visible; list-style: none;">
		<ul class="m-box" id="slides_control_id0" style="position: relative; overflow: hidden; -webkit-transition: left 400ms ease; transition: left 400ms ease; width: 960px; left: -320px;">
            <li style="float: left; display: block; width: 320px;"><a href="#" title="手机网站首页轮换广告1">
            <img src="<?php bloginfo('template_directory'); ?>/images/ban1.jpg" title="手机网站首页轮换广告1" width="320" height="208" alt="" class="loaded" style="height: 208px;"></a></li> 
            <li style="float: left; display: block; width: 320px;"><a href="#" title="手机网站首页轮换广告2">
            <img src="<?php bloginfo('template_directory'); ?>/images/ban2.jpg" title="手机网站首页轮换广告2" width="320" height="208" alt="" class="loaded" style="height: 208px;"></a></li>
            <li style="float: left; display: block; width: 320px;"><a href="#" title="手机网站首页轮换广告3">
            <img src="<?php bloginfo('template_directory'); ?>/images/ban3.jpg" title="手机网站首页轮换广告3" width="320" height="208" alt="" class="loaded" style="height: 208px;"></a></li>
        </ul></div>
		<ul class="m-cnt" id="pager_id0">
            <li class=""></li>
            <li class=""></li>
            <li class=""></li>
		</ul>
</div>
<div class="g-cust">
    <a href="http://www.ycyjzh.com/about" title="走进艺嘉装饰集团">
        <h2 class="m-tit shadow"><span class="u-ico">更多&gt;&gt;</span>走进艺嘉装饰集团</h2>
    </a>
    <div class="con_01">
        <a href="http://www.ycyjzh.com/about" title="江苏艺嘉装饰设计工程有限公司">
            <p class="f-tc"><img width="278" height="165" src="<?php bloginfo('template_directory'); ?>/images/gongsi.jpg" alt="江苏艺嘉装饰设计工程有限公司" class="loaded"></p>
        </a>
        <h2>江苏艺嘉装饰设计工程有限公司</h2>
        <a href="http://www.ycyjzh.com/about"><p class="anli">江苏艺嘉装饰设计工程有限公司是集咨询、家居、工装设计、施工、选材、监理、售后服务为一体的专业装饰公司，国家装饰设计乙级资质企业、国家装饰施工二级资质企业。企业下设艺嘉装饰工装事业部、国飞精品部、高力精品部、钱江方洲精品部、大庆路精品部、紫薇旗舰店等六个分支机构。</p>
        </a>
    </div>
</div>
    <div class="g-sbox j-slide-not">
            <ul class="m-cnt shadow" id="pager_id_0">
                <li class="s-tbg z-on">艺嘉作品展示</li>
                <li class="s-tbg">艺嘉设计师展示</li>
            </ul>
    <div class="sclwrap_box" style="position: relative; overflow: hidden; visibility: visible; list-style: none;">
		<div class="m-box" id="slides_control_id_0" style="position: relative; overflow: hidden; -webkit-transition: 0ms; transition: 0ms; width: 640px; left: 0px;">
		<div class="hzcont" style="float: left; display: block; width: 310px;">
			<?php $posts = get_posts( "category=13&numberposts=9" ); ?>
			<?php if( $posts ) : ?>
			<?php foreach( $posts as $post ) : setup_postdata( $post ); ?>
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php wpjam_post_thumbnail($size=array(89,55), $crop=1,$class="wp-post-image"); ?><?php echo mb_strimwidth( get_the_title(),0,30 ); ?></a>
			<?php endforeach; endif; ?>
			<p class="clear"></p>
		</div>   
		<div class="hzcontt" style="float: left; display: block; width: 310px;">
			<?php $posts = get_posts( "category=6&numberposts=6" ); ?>
			<?php if( $posts ) : ?>
			<?php foreach( $posts as $post ) : setup_postdata( $post ); ?>
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php wpjam_post_thumbnail($size=array(89,105), $crop=1,$class="wp-post-image"); ?><?php echo mb_strimwidth( get_the_title(),0,30 ); ?></a>
			<?php endforeach; endif; ?>
			<p class="clear"></p>
		</div>
		</div>
	</div>
    </div>
<div class="g-cust">
    <a href="http://www.ycyjzh.com/category/zizhi" title="资质证书">
        <h2 class="m-tit shadow"><span class="u-ico">更多&gt;&gt;</span>资质荣誉</h2>
    </a>
    <div class="con_01">
        <?php $posts = get_posts( "category=24&numberposts=2" ); ?>
		<?php if( $posts ) : ?>
		<?php foreach( $posts as $post ) : setup_postdata( $post ); ?>
            <dl>
                <dt><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php wpjam_post_thumbnail($size=array(130,84), $crop=1,$class="wp-post-image"); ?></a></dt>
                <dd><a href="<?php the_permalink(); ?>"><?php echo mb_strimwidth( get_the_title(),0,30 ); ?></a></dd>
            </dl>
        <?php endforeach; endif; ?>    
        <div class="clear"></div>
    </div>
    <p class="u-empty1 "></p>
</div>
<div class="newsss g-sbox j-slide-not">
    <ul class="m-cnt shadow spe" id="pager_id_1">
        <li class="s-tbg z-on">艺嘉动态<b></b></li>
        <li class="s-tbg">家装活动<b></b></li>
        <li class="s-tbg">装修知识<b></b></li>    
    </ul>
    <div class="sclwrap_box" style="position: relative; overflow: hidden; visibility: visible; list-style: none;">
	<div class="m-box" id="slides_control_id_1" style="position: relative; overflow: hidden; -webkit-transition: 0ms; transition: 0ms; width: 960px; left: 0px;">
        <div class="m-con z-spe" id="shk0" style="float: left; display: block; width: 310px;">
        <?php $posts = get_posts( "category=3&numberposts=5" ); ?>
		<?php if( $posts ) : ?>
		<?php foreach( $posts as $post ) : setup_postdata( $post ); ?>            
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo mb_strimwidth( get_the_title(),0,46 ); ?></a>
        <?php endforeach; endif; ?>
        </div>
            
		<div class="m-con z-spe" id="shk1" style="float: left; display: block; width: 310px;">
        <?php $posts = get_posts( "category=2&numberposts=5" ); ?>
		<?php if( $posts ) : ?>
		<?php foreach( $posts as $post ) : setup_postdata( $post ); ?>            
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo mb_strimwidth( get_the_title(),0,46 ); ?></a>
        <?php endforeach; endif; ?>   
        </div>
            
        <div class="m-con z-spe" id="shk2" style="float: left; display: block; width: 310px;">
        <?php $posts = get_posts( "category=4&numberposts=5" ); ?>
		<?php if( $posts ) : ?>
		<?php foreach( $posts as $post ) : setup_postdata( $post ); ?>            
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo mb_strimwidth( get_the_title(),0,46 ); ?></a>
        <?php endforeach; endif; ?>
        </div>
    </div>
	</div>
</div>
<?php get_footer(); ?>