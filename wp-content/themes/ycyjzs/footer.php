<div class="foot">
	<div class="foott">
    <div class="footer">
        <div class="fnav">
            <?php 
			$top_nav = wp_nav_menu( array( 'theme_location'=>'dibu', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'dibuul', 'echo'=>false, 'after'=>'<span>&nbsp;&nbsp;|&nbsp;</span>' ) );
			$top_nav = str_replace( "<span>&nbsp;&nbsp;|&nbsp;</span></li>\n</ul>", "</li>\n</ul>", $top_nav );
			echo $top_nav;
			?>
        </div>
        <div class="f_nr">
            <div class="f_wz fl">
				<?php echo stripslashes( get_option( 'DX-Eblr-foot-1' ) ); ?>
			</div>
        </div>
    </div>
	</div>
</div>

<!-- 返回顶部 -->
<div style="display: none;" id="gotop"></div>
<script type='text/javascript'>
    backTop=function (btnId){
        var btn=document.getElementById(btnId);
        var d=document.documentElement;
        var b=document.body;
        window.onscroll=set;
        btn.onclick=function (){
            btn.style.display="none";
            window.onscroll=null;
            this.timer=setInterval(function(){
                d.scrollTop-=Math.ceil((d.scrollTop+b.scrollTop)*0.1);
                b.scrollTop-=Math.ceil((d.scrollTop+b.scrollTop)*0.1);
                if((d.scrollTop+b.scrollTop)==0) clearInterval(btn.timer,window.onscroll=set);
            },10);
        };
        function set(){btn.style.display=(d.scrollTop+b.scrollTop>450)?'block':"none"}
    };
    backTop('gotop');
</script>
<!-- 返回顶部END -->
	<script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/js/js.js"></script>
	<?php if ( is_home() ) { ?>
	<script src="<?php bloginfo( 'template_url' ); ?>/js/hd.js" type="text/javascript"></script>
	<script src="<?php bloginfo( 'template_url' ); ?>/js/zizhi.js" type="text/javascript"></script>
	<script>
    $(function () {
      $("#slider1").responsiveSlides({
        maxwidth: 220,
        speed: 800
      });
    });
	</script>
	<?php } ?>
<?php wp_footer(); ?>
</body>
</html>