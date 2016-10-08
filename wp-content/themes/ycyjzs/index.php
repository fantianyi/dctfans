<?php get_header(); ?>
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
    <div class="clear"></div>
        <!--<div class="ad mt10 loadimg">
            <img alt="横幅广告" src="<?php bloginfo('template_directory'); ?>/images/shouxieti.jpg"></a>
        </div>-->

<div class="abt">
<?php $cid = get_option( 'DX-Eblr-index-faq' ); include( 'parts/index-faq.php' ); ?> 
<?php include( 'parts/index-about.php' ); ?>
 
</div>

<!--员工照片滚动js-->			
<script type="text/javascript" language="javascript">
function g(o){return document.getElementById(o);}
function HoverLi4(n){
for(var i=1;i<=3;i++){g('tb4_'+i).className='normaltab4';g('tbc4_0'+i).className='undis4';}g('tbc4_0'+n).className='dis4';g('tb4_'+n).className='hovertab4';
}
</script>
<div id="top_hdm4">
<h3>
    <ul>
        <li id="tb4_1" class="hovertab4" onmouseover="x:HoverLi4(1)"><a href="#" target="_blank">创始会员</a></li>  
        <li id="tb4_2" class="normaltab4" onmouseover="x:HoverLi4(2)"><a href="#" target="_blank">机构会员</a></li> 
        <li id="tb4_3" class="normaltab4" onmouseover="x:HoverLi4(3)"><a href="#" target="_blank">个人会员</a></li>   
    </ul>
    <span><a class="fr" href="#" target="_blank" style="margin-top:10px"><img alt="更多" src="<?php bloginfo('template_directory'); ?>/images/more_ic.gif"></a> </span>
</h3>
<div class="hdm4_count">
<div class="dis4" id="tbc4_01">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody><tr>
    <td width="836" valign="top">
    <div id="demo" style="overflow:hidden; width:920px; height:250px;">
      <div id="indemo">
      <div id="demo1">
      <table width="220" border="0" align="center" cellpadding="0" cellspacing="0">
              <tbody><tr>
                <?php $cid = get_option( 'DX-Eblr-index-ygzp1' ); include( 'parts/index-tuandui.php' ); ?>
              </tr>
            </tbody></table>
       </div>
      <div id="demo2">
      <table width="220" border="0" align="center" cellpadding="0" cellspacing="0">
              <tbody><tr>
                <?php $cid = get_option( 'DX-Eblr-index-ygzp1' ); include( 'parts/index-tuandui.php' ); ?>
              </tr>
            </tbody></table>
       </div>
      </div>
    </div>
	<script>
		var speed=30
		demo2.innerHTML=demo1.innerHTML
		function Marquee1(){
		if(demo2.offsetWidth-demo.scrollLeft<=0)
		demo.scrollLeft-=demo1.offsetWidth
		else{
		demo.scrollLeft++
		}
		}
		var MyMar=setInterval(Marquee1,speed)
		demo.onmouseover=function() {clearInterval(MyMar)}
		demo.onmouseout=function() {MyMar=setInterval(Marquee1,speed)}
	</script>
    </td>
  </tr>
</tbody></table>
</div>
    <div class="undis4" id="tbc4_02">
        <table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody><tr>
                <td width="835" height="220" align="center" valign="top">
                <div id="demo222" style="OVERFLOW: hidden; WIDTH:920px; HEIGHT:250px; margin-left:10px;">
                    <table height="175" border="0" cellpadding="0" cellspace="0">
                        <tbody><tr>
                            <?php $cid = get_option( 'DX-Eblr-index-ygzp2' ); include( 'parts/index-tuandui2.php' ); ?>
                        </tr></tbody>
					</table>
                </div>
					<script>
					  var speed=30
					  demo2222.innerHTML=demo2221.innerHTML
					  function Marquee1(){
					  if(demo2222.offsetWidth-demo222.scrollLeft<=0)
					  demo222.scrollLeft-=demo2221.offsetWidth
					  else{
					  demo222.scrollLeft++
					  }
					  }
					  var MyMar=setInterval(Marquee1,speed)
					  demo222.onmouseover=function() {clearInterval(MyMar)}
					  demo222.onmouseout=function() {MyMar=setInterval(Marquee1,speed)}
					 </script>
                </td></tr>
            </tbody>
		</table>
	</div>
    <div class="undis4" id="tbc4_03">
        <table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody><tr>
                <td width="836" height="177" align="center" valign="top">
                <div id="demo9222" style="OVERFLOW: hidden; WIDTH:920px; HEIGHT:250px; margin-left:10px;">
                    <table height="175" border="0" cellpadding="0" cellspace="0">
                        <tbody><tr>
                            <?php $cid = get_option( 'DX-Eblr-index-ygzp3' ); include( 'parts/index-tuandui3.php' ); ?>
                        </tr></tbody>
					</table>
                </div>
<script>
  var speed=30
  demo92222.innerHTML=demo92221.innerHTML
  function Marquee1(){
  if(demo92222.offsetWidth-demo9222.scrollLeft<=0)
  demo9222.scrollLeft-=demo92221.offsetWidth
  else{
  demo9222.scrollLeft++
  }
  }
  var MyMar=setInterval(Marquee1,speed)
  demo9222.onmouseover=function() {clearInterval(MyMar)}
  demo9222.onmouseout=function() {MyMar=setInterval(Marquee1,speed)}
</script>
                </td></tr>
            </tbody>
		</table>
    </div>
</div>
</div>


<div class="info">
<div class="info1 fl">
	<?php $cid = get_option( 'DX-Eblr-index-news1' ); $img_id = 1; include( 'parts/index-news.php' ); ?>
</div>
<div class="info1 fl info2">
	<?php $cid = get_option( 'DX-Eblr-index-news2' ); $img_id = 2; include( 'parts/index-news.php' ); ?>
</div>
<div class="info1 fr info3">
	<?php $cid = get_option( 'DX-Eblr-index-news3' ); $img_id = 3; include( 'parts/index-news.php' ); ?>
</div>
<div class="clear"></div>
</div>
	
		
<?php // $cid = get_option( 'DX-Eblr-index-rongyu' ); include( 'parts/index-rygundong.php' ); ?>     


<div class="link_bj mt10">
    <div class="link">
        <h4><span class="fr linksp">接受PR>=2、BR>=2，流量相当，区块链相关内容类链接。</span><a>友情链接</a></h4>
        <ul class="linkul">
			<?php wp_list_bookmarks('title_li=&categorize=0&limit=40'); ?>
		</ul>
    </div>
</div>

</div>
<?php get_footer(); ?>