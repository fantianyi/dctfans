<ul id="tags_related">
<?php
global $post;
$post_tags = wp_get_post_tags($post->ID);
if ($post_tags) {
  foreach ($post_tags as $tag) {
    // 获取标签列表
    $tag_list[] .= $tag->term_id;
  }

  // 随机获取标签列表中的一个标签
  $post_tag = $tag_list[ mt_rand(0, count($tag_list) - 1) ];

  // 该方法使用 query_posts() 函数来调用相关文章，以下是参数列表
  $args = array(
        'tag__in' => array($post_tag),
        'category__not_in' => array(NULL),  // 不包括的分类ID
        'post__not_in' => array($post->ID),
        'showposts' => 10,                           // 显示相关文章数量
        'caller_get_posts' => 1
    );
  query_posts($args);

  if (have_posts()) {
    while (have_posts()) {
      the_post(); update_post_caches($posts); ?>
    <li><a href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php wpjam_post_thumbnail($size=array(210,160), $crop=1,$class="wp-post-image"); ?></a><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
<?php
    }
  }
  else {
    echo '<li>* 暂无相关信息</li>';
  }
  wp_reset_query(); 
}
else {
  echo '<li>* 暂无相关信息</li>';
}
?>
</ul>