<?php
/**
 * This file contains the "Welcome" markup displayed after Crio is activated.
 *
 * @package Boldgrid_Theme_Framework
 * @since 2.0.0
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * Generate "install starter content" button.
 *
 * As we get more starter content sets in the future, this system will become much more structured.
 * For example, we'll have a config file, maybe some fancy loops, etc. For now, we have 1 set of starter
 * content, so markup is basic.
 */

?>

<div class="wrap about-wrap bgtfw-about-wrap">

	<h1>Starter Content</h1>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<form method="post" action="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">
				<input type="hidden" name="starter_content" value="default" />

				<div class="welcome-panel-column-container two-col">
					<div class="welcome-panel-column">
						<h2><strong>Bold</strong>Fresh</h2>
						<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
						<p>
							<input class="button button-primary button-hero" type="submit" value="<?php esc_attr_e( 'Install', 'bgtfw' ); ?>" />
						</p>
					</div>
					<div class="welcome-panel-column">
						<img style="width:100%;" src="https://via.placeholder.com/646x395">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
