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

$starter_content_url = admin_url( 'admin.php?page=crio-starter-content' );

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
					<?php
					/*
					 * Adjust the "Envato Auto Verify" button dynamically.
					 *
					 * If the user has premium envato-prime, then update the button to instead say
					 * "Registered!".
					 */
					if ( ! $is_premium ) {
					?>
						<a href="https://www.boldgrid.com/central/code/envato" class="button button-primary button-hero" target="_blank"><?php esc_html_e( 'Envato Auto Verify', 'bgtfw' ); ?></a>
					<?php } else { ?>
						<a class="button button-primary button-hero" disabled="disabled"><?php esc_html_e( 'Registered!', 'bgtfw' ); ?></a>
					<?php } // End $is_premium check. ?>
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
					<h2><?php esc_html_e( 'Let\'s Start Building your New Site!', 'bgtfw' ); ?></h2>
					<p>
					<?php
						printf(
							wp_kses(
								/* translators: The Url to the Post and Page Builder on boldgrid.com */
								__( 'Crio gives you control over layouts and macro "Style Guide" design elements straight from the Customizer. The <a href="%1$s" target="_blank">Post and Page Builder Premium</a>, included with your Crio purchase, natively inherits this Style Guide. For example, using Crio\'s Color Palette System you can create beautiful color schemes that apply to your entire website. This same Palette is available on the Page and Post level to inherit or override as you see fit. This saves time and helps keep your site within your Style Guide.', 'bgtfw' ),
								$allowed_html
							),
							esc_url( 'https://www.boldgrid.com/support/boldgrid-post-and-page-builder/post-and-page-builder/' )
						);
					?>
					</p>
					<p>
					<?php
						esc_html_e( 'Your Crio purchase also comes with a set of Starter Content including a Form Builder Plugin. The Post and Page Builder Premium gives you access to pre-built professionally designed Premium Blocks. With your Crio license, these Blocks and Starter Content are included for you to adapt and publish as your own.', 'bgtfw' );
					?>
					</p>
					<form method="post" action="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="starter-content-install">
						<input type="hidden" name="starter_content" value="default" />
						<p>
						<?php
						/*
						 * Adjust starter content button based on whether or not it has been previewed.
						 *
						 * The $starter_content_previewed value is true when the user has accessed
						 * the Customizer and the starter content has been loaded. It doesn't mean
						 * the user has published, but it does mean the starter content plugins
						 * have been installed and the user has seen the starter content.
						 */
						if ( ! $starter_content_previewed ) { ?>
							<input type="submit" class="button button-primary button-hero" value="<?php esc_attr_e( 'Auto Configure and Start Designing', 'bgtfw' ); ?>" />
						<?php } else { ?>
							<input type="submit" class="button button-primary button-hero" value="<?php esc_attr_e( 'Configured!', 'bgtfw' ); ?>" disabled="disabled" />
						<?php } // End $starter_content_previewed check. ?>
							<span class="spinner"></span>
							<span class="nowrap">
								<?php esc_html_e( 'or', 'bgtfw' ); ?>
								<a href="https://www.boldgrid.com/support/boldgrid-crio/getting-started-with-boldgrid-crio/" target="_blank"><?php esc_html_e( 'Learn More', 'bgtfw' ); ?></a>
							</span>
						</p>

						<?php require_once $this->configs['framework']['includes_dir'] . '/partials/starter-content-messages.php'; ?>

					</form>
					<?php
					/*
					 * Give a plug to BoldGrid Central's Cloud WordPress.
					 *
					 * Only give the plug however after the user has "previewed" the Starter Content.
					 */
					if ( BoldGrid_Framework_Customizer_Starter_Content::has_been_previewed() ) {
					?>
					<p>
						<img src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/outline-cloud-24px.svg' ); ?>" style="margin:0px;margin-bottom:-4px;width:40px;vertical-align:text-bottom;" />
						<?php
						printf(
							wp_kses(
								/* translators: The Url to the BoldGrid Central. */
								__( 'Are you looking to create a new Crio based website or need to test some changes? You can use your complimentary <a href="%1$s" target="_blank">Cloud WordPress</a> to test, develop, and share new designs before publishing to permanent hosting.', 'bgtfw' ),
								$allowed_html
							),
							esc_url( 'https://www.boldgrid.com/central' )
						);
						?>
					</p>
					<?php } // End Cloud WordPress plug. ?>
					<p>
					<?php
						esc_html_e( 'You can also manually install the components under Manual Install and Optional Plugins at the end of this page.', 'bgtfw' );
					?>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/bg-customizer.png' ); ?>" />
				</div>
			</div>
		</div>
	</div>

	<div class="two-col">
		<div class="welcome-panel col welcome-panel-column">
			<div class="welcome-panel-content">
				<h2><?php esc_html_e( 'Feature Requests and Bug Reports', 'bgtfw' ); ?></h2>
				<p>
				<?php
					printf(
						wp_kses(
							/* translators: The Url to submit feature requests and bug reports. */
							__( 'Help us make BoldGrid Crio better. To submit your product ideas or feedback about current features, or to report bugs and issues, please visit our <a href="%1$s" target="_blank">BoldGrid Community Portal</a>.', 'bgtfw' ),
							$allowed_html
						),
						esc_url( 'https://boldgrid.com/feedback/' )
					);
				?>
				</p>
			</div>
		</div>
		<div class="welcome-panel col welcome-panel-column">
			<div class="welcome-panel-content">
				<h2><?php esc_html_e( '1 on 1 Support', 'bgtfw' ); ?></h2>
				<p>
				<?php
					printf(
						wp_kses(
							/* translators: 1 is the Url for Crio support, 2 is the Url to BoldGrid Central. */
							__( 'Need help with Crio that you didn\'t see in our <a href="%1$s" target="_blank">Crio Support Documentation</a>? Contact our knowledgeable Support Team from within <a href="%2$s" target="_blank">BoldGrid Central</a>.', 'bgtfw' ),
							$allowed_html
						),
						esc_url( 'https://www.boldgrid.com/support/boldgrid-crio' ),
						esc_url( 'https://www.boldgrid.com/central' )
					);
				?>
				</p>
			</div>
		</div>
	</div>

	<h2>
		<?php esc_html_e( 'Manual Install and Optional Plugins', 'bgtfw' ); ?>
	</h2>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container two-col">
				<div class="welcome-panel-column">
					<h2><?php esc_html_e( 'Required for Best Performance: Post and Page Builder Plugin', 'bgtfw' ); ?></h2>
					<p>
					<?php
						printf(
							wp_kses(
								/* translators: The Url to the Post and Page Builder on boldgrid.com */
								__( 'The <a href="%1$s" target="_blank">BoldGrid Post and Page Builder</a> provides a true WYSIWYG experience while allowing full control over your content. Easily try out new icons, section backgrounds, column and row designs, text settings and more. You can even customize our preset icons, images, and backgrounds within the editor to make them your own.', 'bgtfw' ),
								$allowed_html
							),
							esc_url( 'https://www.boldgrid.com/wordpress-page-builder-by-boldgrid/' )
						);
						?>
					</p>
					<p>
					<?php
					/*
					 * "Plugin Installer" button for BoldGrid Post & Page Builder plugin.
					 *
					 * If we don't need to install or activate the plugin, don't link to the recommended
					 * plugins page.
					 */
					if ( ! class_exists( 'Boldgrid_Editor' ) || ! defined( 'BGPPB_PREMIUM_VERSION' ) ) {
					?>
						<a href="<?php echo esc_url( $tgm_url ); ?>" class="button button-secondary button-hero"><?php esc_html_e( 'Plugin Installer', 'bgtfw' ); ?></a>
					<?php } else { ?>
						<a class="button button-secondary button-hero" disabled="disabled"><?php esc_html_e( 'Installed!', 'bgtfw' ); ?></a>
					<?php } ?>
						<span class="nowrap">
							<?php esc_html_e( 'or', 'bgtfw' ); ?>
							<a href="https://www.boldgrid.com/support/boldgrid-post-and-page-builder/post-and-page-builder/" target="_blank"><?php esc_html_e( 'See Support Documents...', 'bgtfw' ); ?></a>
						</span>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/bg-pape.png' ); ?>" />
				</div>
			</div>
		</div>
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
					<h2><?php esc_html_e( 'Highly Recommended: BoldGrid Backup Premium Plugin', 'bgtfw' ); ?></h2>
					<p>
					<?php
						esc_html_e( 'BoldGrid Backup Premium simplifies maintenance and saves you time by handling backups, upgrades, site transfers, and rollbacks. Automated scheduled backups or manual backups are simple to configure. You can also store offsite backups automatically on Amazon S3, BoldGrid Central (coming soon), Google Drive (coming soon), or a FTP server of your choosing. Rollbacks can be done in bulk or at the file level. Our industry first, Auto rollback (on manual install currently, on scheduled coming soon) protects you from the dreaded "White Screen of Death". Site transfers are easy as well including server to server moves to speed up your work.', 'bgtfw' );
					?>
					</p>
					<p>
					<?php
					/*
					 * "Plugin Installer" button for BoldGrid Backup plugin.
					 *
					 * If we don't need to install or activate the plugin, don't link to the recommended
					 * plugins page.
					 */
					if ( ! class_exists( 'Boldgrid_Backup' ) || ! class_exists( 'Boldgrid_Backup_Premium' ) ) {
					?>
						<a href="<?php echo esc_url( $tgm_url ); ?>" class="button button-secondary button-hero"><?php esc_html_e( 'Plugin Installer', 'bgtfw' ); ?></a>
					<?php } else { ?>
						<a class="button button-secondary button-hero" disabled="disabled"><?php esc_html_e( 'Installed!', 'bgtfw' ); ?></a>
					<?php } ?>
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
					<h2><?php esc_html_e( 'Recommended: BoldGrid Easy SEO', 'bgtfw' ); ?></h2>
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
					<?php
					/*
					 * "Plugin Installer" button for BoldGrid Easy SEO plugin.
					 *
					 * If we don't need to install or activate the plugin, don't link to the recommended
					 * plugins page.
					 */
					if ( ! class_exists( 'Boldgrid_Seo' ) ) {
					?>
						<a href="<?php echo esc_url( $tgm_url ); ?>" class="button button-secondary button-hero"><?php esc_html_e( 'Plugin Installer', 'bgtfw' ); ?></a>
					<?php } else { ?>
						<a class="button button-secondary button-hero" disabled="disabled"><?php esc_html_e( 'Installed!', 'bgtfw' ); ?></a>
					<?php } ?>
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
					<p>
					<?php
						printf(
							wp_kses(
								/* translators: url to Starter Content. */
								__( 'You can still import the <a href="%1$s">Starter Content</a> but please note it may install other plugins based on the requirements of the Content itself.', 'bgtfw' ),
								$allowed_html
							),
							esc_url( $starter_content_url )
						);
					?>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="<?php echo esc_url( $configs['framework']['admin_asset_dir'] . 'img/welcome/bg-customizer.png' ); ?>" />
				</div>
			</div>
		</div>
	</div>
</div>
