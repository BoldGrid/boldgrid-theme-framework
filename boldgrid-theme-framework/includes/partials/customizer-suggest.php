<?php
/**
 * The template used for the jQuery UI Dialog Boxes.
 *
 * @package Boldgrid_Theme_Framework
 */

?>
<div id='dialog-starter-content-suggest' title='<?php echo esc_attr__( 'Welcome to the Customizer!', 'bgtfw' ); ?>' class='dialog-hidden'>
	<p><?php echo esc_html__( 'To get a head start customizing your website, we recommend that you install starter content. Starter content is a pre built demo website. Many users find it easier editing an existing site and making it their own, vs starting off with a blank site.', 'bgtfw' ); ?></p>
	<p class="bgtfw-dialog-question"><?php echo esc_html__( 'Would you like to exit the Customizer and access the Starter Content in your Dashboard?', 'bgtfw' ); ?></p>
	<?php wp_nonce_field( 'starter_content_suggested', 'suggest_nonce', false ); ?>
</div>

