<?php

if ( function_exists( 'add_options_page' ) ) {
	add_options_page( 'Stop Spamco setup', 'Stop SpamCo', 'update_plugins', basename(__FILE__), 'stop_spamco_admin_page' );
}



function stop_spamco_admin_page() {

	global $stopspamco_options;

	if ( isset( $_POST[ 'stopspamco_options_submit' ] ) && check_admin_referer( 'stopspamco_admin_page_submit' ) ) {
		
		$stopspamco_options['choix'] = esc_html($_POST['stopspamco_choix']);
		$stopspamco_options['delai'] = intval(esc_html($_POST['stopspamco_delai']));
		$stopspamco_options['last_update'] = current_time('timestamp');

		update_option( 'stopspamco_options', $stopspamco_options );
		echo '<div id="message" class="updated"><p><strong>';
		_e('Réglages enregistrés.');
		echo '</strong></p></div>';
	} 

?>

	<div class="wrap"> 
		<h2>Stop SpamCo paramètres</h2>			
		<div id="poststuff">
			<form name="stopspamco_options_form" action="" method="post">
			<?php if (function_exists(wp_nonce_field)) {wp_nonce_field('stopspamco_admin_page_submit'); }?>

			<div class="stuffbox">
				<h3>Réglages</h3>
				<div class="inside">

					<label class="stopspamco_options" for="stopspamco_delai">Délai à partir duquel appliquer la règle (en jours)</label>
					<input type="text" name="stopspamco_delai" id="stopspamco_delai" value="<?php echo $stopspamco_options[ 'delai' ]; ?>" />


					<label class="stopspamco_options" for="stopspamco_remove">Supprimer le champs 'Website'</label>
					<input type="radio" name="stopspamco_choix" id="stopspamco_remove" value="remove" <?php if ( $stopspamco_options[ 'choix' ] == 'remove' ) echo 'checked="checked"'; ?> /> Empêche les visiteurs de laisser l'URL d'un site.

					<label class="stopspamco_options" for="stopspamco_nofollow">Passer les liens en nofollow</label>
					<input type="radio" groupe="" name="stopspamco_choix" id="stopspamco_nofollow" value="nofollow" <?php if ( $stopspamco_options[ 'choix' ] == 'nofollow' ) echo 'checked="checked"'; ?> /> Autorise les visiteurs à laisser l'URL d'un site mais passe le lien en nofollow.

					<!-- Show Update Button -->
					<div class="submit">
					<input type="submit" name="stopspamco_options_submit" value="<?php _e('Mettre à jour &raquo;') ?>"/>
					</div>

				</div> <!-- end class="inside" -->
			</div> <!-- end class="stuffbox" -->

			<!-- End Form -->
			</form>

		</div> <!-- end id="poststuff" -->
	</div> <!-- end class="wrap" -->

<?php
}

add_action( 'admin_head', 'stopspamco_admin_head' );
function stopspamco_admin_head() {
?>
	<style type="text/css" media>
		label.stopspamco_options {
			display:block;
			margin: 16px 0 4px;
			font-weight:bold;
		}
		#submit {
			margin: 0 0 20px;
		}
	</style>
<?php
}

?>