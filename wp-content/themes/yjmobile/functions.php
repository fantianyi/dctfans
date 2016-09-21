<?php

//翻页
function par_pagenavi($range = 9){  
    global $paged, $wp_query;  
    if ( !$max_page ) {$max_page = $wp_query->max_num_pages;}  
    if($max_page > 1){if(!$paged){$paged = 1;}  
    if($paged != 1){echo "<a href='" . get_pagenum_link(1) . "' class='extend' title='跳转到首页'> 返回首页 </a>";}  
    previous_posts_link(' 上一页 ');  
    if($max_page > $range){  
        if($paged < $range){for($i = 1; $i <= ($range + 1); $i++){echo "<a href='" . get_pagenum_link($i) ."'";  
        if($i==$paged)echo " class='current'";echo ">$i</a>";}}  
    elseif($paged >= ($max_page - ceil(($range/2)))){  
        for($i = $max_page - $range; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";  
        if($i==$paged)echo " class='current'";echo ">$i</a>";}}  
    elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){  
        for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){echo "<a href='" . get_pagenum_link($i) ."'";if($i==$paged) echo " class='current'";echo ">$i</a>";}}}  
    else{for($i = 1; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";  
    if($i==$paged)echo " class='current'";echo ">$i</a>";}}  
    next_posts_link(' 下一页 ');  
    if($paged != $max_page){echo "<a href='" . get_pagenum_link($max_page) . "' class='extend' title='跳转到最后一页'> 最后一页 </a>";}}  
} 

//面包靴
function bread_nav($sep = ' > '){
    echo '<div><a href="'. home_url() .'" title="首页">首页</a>';
    if ( is_category() ){    //如果是栏目页面
        global $cat;        
        echo $sep . get_category_parents($cat, true, $sep) . '列表';
    }elseif ( is_page() ){    //如果是自定义页面
        echo $sep . get_the_title();
    }elseif ( is_single() ){    //如果是文章页面
        $categories = get_the_category();
        $cat = $categories[0];
        echo $sep . get_category_parents($cat->term_id, true, $sep) . 正文; 
    }
    echo '</div>';
}