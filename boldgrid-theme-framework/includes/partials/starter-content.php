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
							esc_html_e( 'This Starter Content is included to help you get moving quickly while showing off the power and flexibility of Crio and its integrated plugins.  It includes headers, footers, menus, several pages, contact forms, and a blog roll with several posts.  You can also quickly create new pages and posts by using the Add Block functionality to import Premium Blocks from within the Editor.', 'bgtfw' );
						?>
						</p>
						<p>
						<?php
							esc_html_e( 'This Install requires the Post and Page Builder Premium and WP Forms plugins.  These plugins will be installed automatically if you do not already have them.', 'bgtfw' );
						?>
						</p>
						<p>
							<input class="button button-primary button-hero" type="submit" value="<?php esc_attr_e( 'Auto Configure and Start Designing', 'bgtfw' ); ?>" />
							<span class="spinner"></span>
						</p>
					</div>
					<div class="welcome-panel-column">
						<img style="width:100%;" src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/screenshot.jpg">
					</div>
				</div>

				<?php require_once $this->configs['framework']['includes_dir'] . '/partials/starter-content-messages.php'; ?>

			</form>
		</div>
	</div>
</div>
