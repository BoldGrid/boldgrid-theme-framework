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

// Allowed html for wp_kses usage on this page.
$allowed_html = array(
	'a' => array(
		'href' => array(),
		'target' => array(
			'blank',
		),
	),
);

// URL to our TMG Recommended Plugins page, used several times on this page.
$tgm_url = admin_url( 'admin.php?page=bgtfw-install-plugins' );

$customizer_url = admin_url( 'customize.php' );

$registraton_url = admin_url( 'admin.php?page=boldgrid-connect.php' );

global $boldgrid_theme_framework;
$configs = $boldgrid_theme_framework->get_configs();
?>

<div class="wrap about-wrap bgtfw-about-wrap">

	<div>

		<h1><?php esc_html_e( 'Welcome to Crio!', 'bgtfw' ); ?></h1>

		<div class="wp-badge"><?php esc_html_e( 'Version', 'bgtfw' ); ?> <?php echo esc_html( $theme->version ); ?></div>

		<p>
			<?php esc_html_e( 'Congratulations! You\'ve successfully installed BoldGrid Crio. BoldGrid Crio is a powerful tool that enables you to build beautiful websites without boundaries or limitations.', 'bgtfw' ); ?>
		</p>

	</div>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container two-col">
				<div class="welcome-panel-column">
					<h2><?php esc_html_e( 'Register and Verify Your Purchase', 'bgtfw' ); ?></h2>
					<p><?php esc_html_e( 'To claim your Premium Connect Key we need to verify your Envato purchase and setup your BoldGrid Central account.  To do this automatically we need access to read the purchase record in your Envato Account.  You can also do this manually by signing up for BoldGrid Central and providing your Envato purchase code to our Support Team in a ticket.', 'bgtfw' ); ?></p>
					<p>
						<a href="https://www.boldgrid.com/central/code/envato" class="button button-primary button-hero" target="_blank"><?php esc_html_e( 'Envato Auto Verify', 'bgtfw' ); ?></a>
						<span class="nowrap">
							<?php esc_html_e( 'or', 'bgtfw' ); ?>
							<a href="https://www.boldgrid.com/central" target="_blank"><?php esc_html_e( 'Manually Setup and Verify', 'bgtfw' ); ?></a>
						</span>
					</p>
					<p><?php
						printf(
							wp_kses(
								/* translators: The Url to the Post and Page Builder on boldgrid.com */
								__( 'Have your Connect Key? Go to <a href="%1$s">Registration</a>.', 'bgtfw' ),
								$allowed_html
							),
							esc_url( $registraton_url )
						);
					?></p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/bg-central.png' ); ?>" />
				</div>
			</div>
		</div>
	</div>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container two-col">
				<div class="welcome-panel-column">
					<h2><?php esc_html_e( 'Activate the Included Post and Page Builder Plugin', 'bgtfw' ); ?></h2>
					<p>
					<?php
						printf(
							wp_kses(
								/* translators: The Url to the Post and Page Builder on boldgrid.com */
								__( 'The <a href="%1$s" target="_blank">BoldGrid Page and Post Builder</a> provides a true WYSIWYG experience while allowing full control over your content. Easily try out new icons, section backgrounds, column and row designs, text settings and more. You can even customize our preset icons, images, and backgrounds within the editor to make them your own.', 'bgtfw' ),
								$allowed_html
							),
							esc_url( 'https://www.boldgrid.com/wordpress-page-builder-by-boldgrid/' )
						);
						?>
					</p>
					<p>
						<a href="<?php echo esc_url( $tgm_url ); ?>" class="button button-primary button-hero"><?php esc_html_e( 'Activate', 'bgtfw' ); ?></a>
						<span class="nowrap">
							<?php esc_html_e( 'or', 'bgtfw' ); ?>
							<a href="https://www.boldgrid.com/support/post-page-builder-plugin" target="_blank"><?php esc_html_e( 'See Support Documents...', 'bgtfw' ); ?></a>
						</span>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/bg-pape.png' ); ?>" />
				</div>
			</div>
		</div>
	</div>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<h2><?php esc_html_e( 'Here\'s a few things to know', 'bgtfw' ); ?></h2>
			<div class="welcome-panel-column-container four-col">
				<div class="welcome-panel-column">
					<img src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/bg-lightbulb-icon.png' ); ?>" />
					<p><?php esc_html_e( 'The Post and Page Builder plugin also gives you access to blocks. Blocks are pre-built professionally designed sections of content.', 'bgtfw' ); ?></p>
				</div>
				<div class="welcome-panel-column">
					<img src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/bg-lightbulb-icon.png' ); ?>" />
					<p><?php esc_html_e( 'Block layouts consist of rows and columns that are pre-populated with content relevant to your industry.', 'bgtfw' ); ?></p>
				</div>
				<div class="welcome-panel-column">
					<img src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/bg-lightbulb-icon.png' ); ?>" />
					<p><?php esc_html_e( 'Blocks can be managed visually in the Editor using drag and drop functionality, or using text view to access the HTML and CSS.', 'bgtfw' ); ?></p>
				</div>
				<div class="welcome-panel-column">
					<img src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/bg-lightbulb-icon.png' ); ?>" />
					<p><?php esc_html_e( 'Our advanced image controls help you change images while keeping the layout intact.', 'bgtfw' ); ?></p>
				</div>
			</div>
		</div>
	</div>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container two-col">
				<div class="welcome-panel-column">
					<h2><?php esc_html_e( 'You\'re Ready to Start Building', 'bgtfw' ); ?></h2>
					<p><?php esc_html_e( 'BoldGrid Crio\'s advanced customization options are completely integrated with the WordPress Customizer API. Our integration gives you granular control over many elements straight from the customizer.', 'bgtfw' ); ?></p>
					<p><?php esc_html_e( 'Use the "Suggest Palette" feature to have BoldGrid Crio automatically recommend beautiful color schemes that you can apply to your entire website with a few clicks.', 'bgtfw' ); ?></p>
					<p><?php esc_html_e( 'Adjust fonts, headers and sizes across your entire website with just a few clicks. See your changes live in the customizer preview area.', 'bgtfw' ); ?></p>
					<p>
						<a href="<?php echo esc_url( $customizer_url ); ?>" class="button button-primary button-hero"><?php esc_html_e( 'Get Started', 'bgtfw' ); ?></a>
						<span class="nowrap">
							<?php esc_html_e( 'or', 'bgtfw' ); ?>
							<a href="https://www.boldgrid.com/support/" target="_blank"><?php esc_html_e( 'See Support Documents...', 'bgtfw' ); ?></a>
						</span>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/bg-customizer.png' ); ?>" />
				</div>
			</div>
		</div>
	</div>

	<div class="two-col">
		<div class="welcome-panel col">
			<h2><?php esc_html_e( 'Feature Requests and Bug Reports', 'bgtfw' ); ?></h2>
			<p><?php esc_html_e( 'Help us make BoldGrid Crio better. To submit your product ideas or feedback about current features, or to report bugs and issues, please visit:', 'bgtfw' ); ?></p>
			<p><a href="https://boldgrid.com/feedback/" target="_blank" class="button button-primary button-hero"><?php esc_html_e( 'BoldGrid Community Portal', 'bgtfw' ); ?></a></p>
		</div>
		<div class="welcome-panel col">
			<h2><?php esc_html_e( '1 on 1 Support', 'bgtfw' ); ?></h2>
			<p><?php esc_html_e( 'Need help with BoldGrid Crio? Contact a member of our knowledgable support team:', 'bgtfw' ); ?></p>
			<p><a href="https://www.boldgrid.com/central" target="_blank" class="button button-primary button-hero"><?php esc_html_e( 'BoldGrid Central', 'bgtfw' ); ?></a></p>
		</div>
	</div>

	<h2>
		<?php esc_html_e( 'Follow these steps to maximize your BoldGrid License!', 'bgtfw' ); ?>
	</h2>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container two-col">
				<div class="welcome-panel-column">
					<h2><?php esc_html_e( 'Activate the Included BoldGrid Backup Premium Plugin', 'bgtfw' ); ?></h2>
					<p>
					<?php
						printf(
							wp_kses(
								/* translators: URL to boldgrid backup on boldgrid.com. */
								__( '<a href="%1$s" target="blank">BoldGrid Backup</a> will backup your entire WordPress site with just a couple of clicks right in your WordPress dashboard. Just select a time and day for backups to run automatically. Or, manually create a backup at any time with a single click. You can also create offsite backups at Amazon S3 or a web server of your choosing.', 'bgtfw' ),
								$allowed_html
							),
							esc_url( 'https://www.boldgrid.com/wordpress-backup-plugin/' )
						);
						?>
					</p>
					<p>
						<a href="<?php echo esc_url( $tgm_url ); ?>" class="button button-primary button-hero"><?php esc_html_e( 'Install', 'bgtfw' ); ?></a>
						<span class="nowrap">
							<?php esc_html_e( 'or', 'bgtfw' ); ?>
							<a href="https://www.boldgrid.com/support/backup-plugin" target="_blank"><?php esc_html_e( 'See Support Documents...', 'bgtfw' ); ?></a>
						</span>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/bg-backup.png' ); ?>" />
				</div>
			</div>
		</div>
	</div>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container two-col">
				<div class="welcome-panel-column">
					<h2><?php esc_html_e( 'Activate the Included BoldGrid SEO Plugin', 'bgtfw' ); ?></h2>
					<p>
					<?php
						printf(
							wp_kses(
								/* translators: url to Easy SEO plugin on boldgrid.com. */
								__( '<a href="%1$s" target="_blank">BoldGrid SEO</a> analyzes your page content in real-time and makes recommendations to help you maintain best SEO practices while writing content. Just set your target keyword or phrase and the BoldGrid SEO dashboard will update your stats on all important on-page SEO factors.', 'bgtfw' ),
								$allowed_html
							),
							esc_url( 'https://www.boldgrid.com/wordpress-seo-plugin/' )
						);
						?>
					</p>
					<p>
						<a href="<?php echo esc_url( $tgm_url ); ?>" class="button button-primary button-hero"><?php esc_html_e( 'Install', 'bgtfw' ); ?></a>
						<span class="nowrap">
							<?php esc_html_e( 'or', 'bgtfw' ); ?>
							<a href="https://www.boldgrid.com/support/seo-plugin" target="_blank"><?php esc_html_e( 'See Support Documents...', 'bgtfw' ); ?></a>
						</span>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/seo-plugin.png' ); ?>" />
				</div>
			</div>
		</div>
	</div>

	<p style="text-align:center;"><?php esc_html_e( 'Check out the great BoldGrid Starter Content included with your theme purchase. If you\'re looking for a great starting point for your site - or you just need some inspirations - install the pre-built website to jumpstart the process.', 'bgtfw' ); ?></p>
</div>
