<ul class="celansjt">
<?php
// 参数
$category__not_in = array(6,69,70,71); // 不包括的分类ID
$post_max = 5;

$post_tags=wp_get_post_tags($post->ID); //如果存在tag标签，列出tag相关文章
$pos=$post_max;
if($post_tags) {
foreach($post_tags as $tag) $tag_list[] .= $tag->term_id;
$args = array(
'tag__in' => $tag_list,
'category__not_in' => $category__not_in, // 不包括的分类ID
'post__not_in' => array($post->ID),
'showposts' => $pos, // 显示相关文章数量
'caller_get_posts' => 1,
'orderby' => 'modified'
);
query_posts($args);
if(have_posts()):while (have_posts()&&$pos>0) : the_post(); update_post_caches($posts); ?>
	<li><a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>' target="_blank"><?php wpjam_post_thumbnail($size=array(100,88), $crop=1,$class="wp-post-image"); ?></a><h5><?php echo mb_strimwidth( get_the_title(), 0, 50, '...', get_bloginfo( 'charset' ) ); ?></h5><span><?php the_time('Y-m-d') ?></span></li>
<?php $pos--;endwhile;wp_reset_query();endif; ?>
<?php } //end of rand by tags ?>
<?php if($pos>0): //如果tag相关文章少于5篇，那么继续以分类作为相关因素列出相关文章
$cats = wp_get_post_categories($post->ID);
if($cats){
$cat = get_category( $cats[0] );
$first_cat = $cat->cat_ID;
$args = array(
'category__in' => array($first_cat),
'category__not_in' => $category__not_in, // 不包括的分类ID
'post__not_in' => array($post->ID),
'showposts' => $pos,
'caller_get_posts' => 1,
'orderby' => 'modified'
);
query_posts($args);
if(have_posts()): while (have_posts()&&$pos>0) : the_post(); update_post_caches($posts); ?>

	<li><a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>' target="_blank"><?php wpjam_post_thumbnail($size=array(100,88), $crop=1,$class="wp-post-image"); ?></a><h5><?php echo mb_strimwidth( get_the_title(), 0, 50, '...', get_bloginfo( 'charset' ) ); ?></h5><span><?php the_time('Y-m-d') ?></span></li>

<?php $pos--;endwhile;wp_reset_query();endif; ?>
<?php } endif; //end of rand by category ?>
<?php if($pos>0){ //如果上面两种相关都还不够5篇文章，再随机挑几篇凑成5篇 ?>
<?php
$args = array(
'category__not_in' => $category__not_in, // 不包括的分类ID
'post__not_in' => array($post->ID),
'showposts' => $pos,
'orderby' => 'rand'
);
query_posts($args);
while(have_posts()&&$pos>0):the_post(); ?>

	<li><a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>' target="_blank"><?php wpjam_post_thumbnail($size=array(100,88), $crop=1,$class="wp-post-image"); ?></a><h5><?php echo mb_strimwidth( get_the_title(), 0, 50, '...', get_bloginfo( 'charset' ) ); ?></h5><span><?php the_time('Y-m-d') ?></span></li>

<?php $pos--;endwhile;wp_reset_query();?>
<?php } ?>
</ul>