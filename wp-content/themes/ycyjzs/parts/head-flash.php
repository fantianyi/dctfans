<div id="inbanboxx">
<div id="inban">
  <ul id="banimg">
    <li><a target="_blank" href="<?php echo get_option( 'DX-Eblr-flash-1l' ); ?>"><img src="<?php echo get_option( 'DX-Eblr-flash-1' ); ?>" width="952" height="361" /></a></li>
    <li><a target="_blank" href="<?php echo get_option( 'DX-Eblr-flash-2l' ); ?>"><img src="<?php echo get_option( 'DX-Eblr-flash-2' ); ?>" width="952" height="361" /></a></li>
    <li><a target="_blank" href="<?php echo get_option( 'DX-Eblr-flash-3l' ); ?>"><img src="<?php echo get_option( 'DX-Eblr-flash-3' ); ?>" width="952" height="361" /></a></li>
  </ul>
  <ul id="bannum">
    <li  class="hover">1</li>
    <li>2</li>
    <li>3</li>
  </ul>
</div>
</div>
<script type="text/javascript">
var img = document.getElementById("banimg");
var num = document.getElementById("bannum");
var ali = img.getElementsByTagName("li");
var oli = num.getElementsByTagName("li");
var time = null
lanrenzhijiaing = document.getElementById("inban");
img.style.width = ali.length * 952 + "px", inow = 0;
for (var i = 0; i < oli.length; i++) {
	oli[i].index = i
	oli[i].onmouseover = function() {
		inow = this.index;
		tab();
		window.clearInterval(time)
	}
	oli[i].onmouseout = function() {
		time = window.setInterval(autoPlay, 4000)
	}
}

function tab() {
	for (var i = 0; i < oli.length; i++) {
		oli[i].className = ""
	}
	oli[inow].className = "hover"
	startMove(img, {
		left: -inow * 952
	}, 'buffer')
}

function autoPlay() {
	inow++;
	if (inow >= ali.length) {
		inow = 0
	}
	tab();
}
time = window.setInterval(autoPlay, 4000)
</script>