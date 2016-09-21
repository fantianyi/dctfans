<?php 
	query_posts( array( 'cat'=>$cid, 'posts_per_page'=>18, 'ignore_sticky_posts'=>true ) );
	while( have_posts() ): the_post(); 
?>	
			<td height="203" align="center" valign="top">
                 <table border="0" cellspacing="0" cellpadding="0" width="140" align="center" style="margin-right:10px;">
                    <tbody>
                      <tr>
                        <td width="140" height="210" align="center" valign="top">
						<a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>' target="_blank">
						<?php wpjam_post_thumbnail($size=array(140,210), $crop=1,$class="wp-post-image"); ?></a></td>
                      </tr>
                      <tr>
                        <td height="5"></td>
                      </tr>
                      <tr>
                        <td height="26" align="center" valign="top" style="background:url(<?php bloginfo('template_directory'); ?>/images/team_bg.jpg) no-repeat center top;">
						 <table width="140" border="0" cellspacing="0" cellpadding="0">
                          <tbody><tr>
                            <td width="55" height="26" align="center" valign="middle" style="color:#4F2A00;"><?php echo get_post_meta($post->ID,"sjsmz_value",true);?></td>
                            <td width="85" align="center" valign="middle" style="color:#343233;line-height:26px;"><?php echo get_post_meta($post->ID,"sjszw_value",true);?></td>
                            </tr>
                          </tbody></table>  
                        </td>
                      </tr>
                      </tbody>
                </table>
			</td>
<?php endwhile; wp_reset_query(); ?>