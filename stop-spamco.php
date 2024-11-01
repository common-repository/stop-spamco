<?php
/*
Plugin Name: Stop Spamco
Plugin URI: http://www.guillaumedesbieys.com/
Description: Filtrez les spammeurs en retirant le champ Website après x jours, ou en passant ces liens en NoFollow
Author: Guillaume Desbieys
Version: 0.2
Author URI: http://www.guillaumedesbieys.com/
*/

$stopspamco_options = get_option( 'stopspamco_options' );

/*
* Verifie si le delai maximal est atteint
*/
function delayIsOver()
{
	global $stopspamco_options;

	$current_time = current_time('timestamp');

	$difference_date = $current_time - strtotime(get_the_date("r"));

	$limite_post_link = $stopspamco_options['delai'] * 24 * 3600;

	return $difference_date > $limite_post_link;
}

add_filter( 'comment_form_field_url', 'remove_form_field_url' );
function remove_form_field_url( $url_field )
{
	global $stopspamco_options;

	if (delayIsOver() && $stopspamco_options['choix'] == 'remove') 
		return "";
	else
		return $url_field;
}


add_action( 'preprocess_comment', 'disable_bypass' );
function disable_bypass($commentdata)
{
	if (!empty($_POST['url']) && delayIsOver() && $stopspamco_options['choix'] == 'remove')
		wp_die( __( 'Ce blog utilise le plugin Stop Spamco ! N\'essayez pas de contourner le plugin.' ) );

	return $commentdata;
}

add_action( 'admin_menu', 'stop_spamco_admin' );
function stop_spamco_admin()
{
	include "stop-spamco-admin.php";
}

add_filter( 'get_comment_author_link', 'add_nofollow' );
function add_nofollow($link)
{
	global $stopspamco_options, $comment;

	$url = get_comment_author_url();
	$author = get_comment_author();

	if (delayIsOver() && $stopspamco_options['choix'] == 'nofollow' /*&& strtotime(get_comment_date('r')) > $stopspamco_options['last_update']*/)
	{
		return preg_replace('/href=["|\']?(https?:\/\/([^"\']*))["|\']?/ui', "href=\"$1\" title=\"$1\" rel=\"nofollow\" target=\"_blank\"", $link);
	}
	else
	{
		return $link;
	}
}
?>
