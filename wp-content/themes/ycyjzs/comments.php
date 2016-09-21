<?php 
	$com_close = get_option( 'DX-Eblr-other-1' );
	if( $com_close[0] != 'close' ):
?>

<div id="comments">

	<?php if( have_comments() ): ?><div id="comments-list"><h3>留言列表</h3><ul><?php wp_list_comments(); ?></ul></div><?php endif; ?>
	
	<div id="comments-form"><?php comment_form( array( 'title_reply'=>'发表留言', 'label_submit'=>'提交' ) ); ?></div>

</div>

<?php endif; ?>