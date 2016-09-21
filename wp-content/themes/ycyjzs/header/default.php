<div class="bigbeijing">
	<div class="content">
	<div class="sou">
        <div class="s_inp02 fr">
            <form role="search" method="get" class="shInput" action="<?php echo get_option('home'); ?>">
			<div><input type="text" name="s" id="s"><input type="submit" id="searchsubmit" value=""></div>
			</form>
		</div>
        <div class="p fl">
            <b style="float: left;padding-right: 10px;">热门搜索关键词：</b>
			<?php 
			$top_nav = wp_nav_menu( array( 'theme_location'=>'remen', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'remen', 'echo'=>false, 'after'=>'' ) );
			$top_nav = str_replace( "</li>\n</ul>", "</li>\n</ul>", $top_nav );
			echo $top_nav;
			?>
        </div>
    </div>
	<div class="pageheter">
		<img src="<?php bloginfo('template_directory'); ?>/images/defaultheader.jpg">
	</div>
	