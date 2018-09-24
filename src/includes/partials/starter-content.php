<?php
/**
 * This file contains the "Welcome" markup displayed after Crio is activated.
 *
 * As we get more starter content sets in the future, this system will become much more structured.
 * For example, we'll have a config file, maybe some fancy loops, etc. For now, we have 1 set of
 * starter content, so markup is basic.
 *
 * @package Boldgrid_Theme_Framework
 * @since 2.0.0
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="wrap about-wrap bgtfw-about-wrap">

	<h1>Starter Content</h1>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<form method="post" action="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="starter-content-install">
				<input type="hidden" name="starter_content" value="default" />

				<div class="welcome-panel-column-container two-col">
					<div class="welcome-panel-column">
						<h2><strong>Bold</strong>Business</h2>
						<p>
						<?php
							esc_html_e( 'We created this default starter content set to show off the power and flexibility of BoldGrid Crio. This set is a design for a business site. All standard and Premium functionality is compatible with this starter content.', 'bgtfw' );
						?>
						</p>
						<p>
							<input class="button button-primary button-hero" type="submit" value="<?php esc_attr_e( 'Install', 'bgtfw' ); ?>" />
							<span class="spinner"></span>
						</p>
					</div>
					<div class="welcome-panel-column">
						<img style="width:100%;" src="<?php echo bloginfo( 'template_directory' ); ?>/starter-content/corporate/screenshot.jpg">
					</div>
				</div>

				<?php require_once $this->configs['framework']['includes_dir'] . '/partials/starter-content-messages.php'; ?>

			</form>
		</div>
	</div>
</div>
