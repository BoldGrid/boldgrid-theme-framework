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
	),
);

// URL to our TMG Recommended Plugins page, used several times on this page.
$tgm_url = admin_url( 'admin.php?page=bgtfw-install-plugins' );

$customizer_url = admin_url( 'customize.php' );
?>

<div class="wrap about-wrap bgtfw-about-wrap">

	<h1><?php esc_html_e( 'Welcome to Crio!', 'bgtfw' ); ?></h1>

	<div class="wp-badge"><?php esc_html_e( 'Version', 'bgtfw' ); ?> 2.0.0</div>

	<p>
		<?php esc_html_e( 'Congratulations! You\'ve successfully installed BoldGrid Crio. BoldGrid Crio is a powerful tool that enables you to build beautiful websites without boundaries or limitations. Before you begin, please scroll down and read over the following steps to maximize the creative potential of BoldGrid Crio.', 'bgtfw' ); ?>
	</p>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container two-col">
				<div class="welcome-panel-column">
					<h3><?php esc_html_e( 'Register Your Account With BoldGrid Central', 'bgtfw' ); ?></h3>
					<p><?php esc_html_e( 'BoldGrid Central will allow you to generate your connect key, which will unlock all the features of your BoldGrid Crio purchase. Central also allows you to submit personalized support requests.', 'bgtfw' ); ?></p>
					<p>
						<a href="https://www.boldgrid.com/central/code/envato" class="button button-primary button-hero"><?php esc_html_e( 'Register', 'bgtfw' ); ?></a>
						<?php esc_html_e( 'or', 'bgtfw' ); ?>
						<a href="https://www.boldgrid.com/central"><?php esc_html_e( 'Login to BoldGrid Central', 'bgtfw' ); ?></a>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="http://via.placeholder.com/350x150" />
				</div>
			</div>
		</div>
	</div>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container two-col">
				<div class="welcome-panel-column">
					<h3><?php esc_html_e( 'Activate the Included Post and Page Builder Plugin', 'bgtfw' ); ?></h3>
					<p><?php
						printf(
							wp_kses(
								__( 'The <a href="%1$s">BoldGrid Page and Post Builder</a> provides a true WYSIWYG experience while allowing full control over your content. Easily try out new icons, section backgrounds, column and row designs, text settings and more. You can even customize our preset icons, images, and backgrounds within the editor to make them your own.', 'bgtfw' ),
								$allowed_html
							),
							esc_url( 'https://www.boldgrid.com/wordpress-page-builder-by-boldgrid/' )
						);
					?></p>
					<p>
						<a href="<?php echo $tgm_url; ?>" class="button button-primary button-hero"><?php esc_html_e( 'Install', 'bgtfw' ); ?></a>
						<?php esc_html_e( 'or', 'bgtfw' ); ?>
						<a href="https://www.boldgrid.com/support/post-page-builder-plugin"><?php esc_html_e( 'See Support Documents...', 'bgtfw' ); ?></a>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="http://via.placeholder.com/350x150" />
				</div>
			</div>
		</div>
	</div>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container four-col">
				<div class="welcome-panel-column">
					<img src="http://via.placeholder.com/45x45" />
					<p><?php esc_html_e( 'The Post and Page Builder plugin also gives you access to blocks. Blocks are pre-built professionally designed sections of content.', 'bgtfw' ); ?></p>
				</div>
				<div class="welcome-panel-column">
					<img src="http://via.placeholder.com/45x45" />
					<p><?php esc_html_e( 'Block layouts consist of rows and columns that are pre-populated with content relevant to your industry.', 'bgtfw' ); ?></p>
				</div>
				<div class="welcome-panel-column">
					<img src="http://via.placeholder.com/45x45" />
					<p><?php esc_html_e( 'Blocks can be managed visually in the Editor using drag and drop functionality, or using text view to access the HTML and CSS.', 'bgtfw' ); ?></p>
				</div>
				<div class="welcome-panel-column">
					<img src="http://via.placeholder.com/45x45" />
					<p><?php esc_html_e( 'Our advanced image controls help you change images while keeping the layout intact.', 'bgtfw' ); ?></p>
				</div>
			</div>
		</div>
	</div>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container two-col">
				<div class="welcome-panel-column">
					<h3><?php esc_html_e( 'You\'re Ready to Start Building', 'bgtfw' ); ?></h3>
					<p><?php esc_html_e( 'BoldGrid Crio\'s advanced customization options are completely integrated with the WordPress Customizer API. Our integration gives you granular control over many elements straight from the customizer.', 'bgtfw' ); ?></p>
					<p><?php esc_html_e( 'Use the "Suggest Palette" feature to have BoldGrid Crio automatically recommend beautiful color schemes that you can apply to your entire website with a few clicks.', 'bgtfw' ); ?></p>
					<p><?php esc_html_e( 'Adjust fonts, headers and sizes across your entire website with just a few clicks. See your changes live in the customizer preview area.', 'bgtfw' ); ?></p>
					<p>
						<a href="<?php echo $customizer_url; ?>" class="button button-primary button-hero"><?php esc_html_e( 'Get Started', 'bgtfw' ); ?></a>
						<?php esc_html_e( 'or', 'bgtfw' ); ?>
						<a href="https://www.boldgrid.com/support/"><?php esc_html_e( 'See Support Documents...', 'bgtfw' ); ?></a>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="http://via.placeholder.com/350x150" />
				</div>
			</div>
		</div>
	</div>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container two-col">
				<div class="welcome-panel-column">
					<h3><?php esc_html_e( 'Activate the Included BoldGrid Backup Premium Plugin', 'bgtfw' ); ?></h3>
					<p><?php
						printf(
							wp_kses(
								__( '<a href="%1$s">BoldGrid Backup</a> will backup your entire WordPress site with just a couple of clicks right in your WordPress dashboard. Just select a time and day for backups to run automatically. Or manually create a backup at any time with a single click. You can also create offsite backups at Amazon S3 or a web server of your choosing.', 'bgtfw' ),
								$allowed_html ),
							esc_url( 'https://www.boldgrid.com/wordpress-backup-plugin/' )
						);
					?></p>
					<p>
						<a href="<?php echo $tgm_url; ?>" class="button button-primary button-hero"><?php esc_html_e( 'Install', 'bgtfw' ); ?></a>
						<?php esc_html_e( 'or', 'bgtfw' ); ?>
						<a href="https://www.boldgrid.com/support/backup-plugin"><?php esc_html_e( 'See Support Documents...', 'bgtfw' ); ?></a>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="http://via.placeholder.com/350x150" />
				</div>
			</div>
		</div>
	</div>

	<div class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container two-col">
				<div class="welcome-panel-column">
					<h3><?php esc_html_e( 'Activate the Included BoldGrid SEO Plugin', 'bgtfw' ); ?></h3>
					<p><?php
						printf(
							wp_kses(
								__( '<a href="%1$s">BoldGrid SEO</a> analyzes your page content in real-time and makes recommendations to help you maintain best SEO practices while writing content. Just set your target keyword or phrase and the BoldGrid SEO dashboard will update your stats on all important on-page SEO factors.', 'bgtfw' ),
								$allowed_html ),
							esc_url( 'https://www.boldgrid.com/wordpress-seo-plugin/' )
						);
					?></p>
					<p>
						<a href="<?php echo $tgm_url; ?>" class="button button-primary button-hero"><?php esc_html_e( 'Install', 'bgtfw' ); ?></a>
						<?php esc_html_e( 'or', 'bgtfw' ); ?>
						<a href="https://www.boldgrid.com/support/seo-plugin"><?php esc_html_e( 'See Support Documents...', 'bgtfw' ); ?></a>
					</p>
				</div>
				<div class="welcome-panel-column">
					<img style="width:100%;" src="http://via.placeholder.com/350x150" />
				</div>
			</div>
		</div>
	</div>

	<div class="two-col">
		<div class="welcome-panel col">
			<h3><?php esc_html_e( 'Submit Feature Requests and Feedback', 'bgtfw' ); ?></h3>
			<p><?php esc_html_e( 'Help us make BoldGrid Crio better. To submit your product ideas or feedback about current features, please let us know by visiting our:', 'bgtfw' ); ?></p>
			<p><a href="https://www.boldgrid.com/feedback/communities/8-feature-request" class="button button-primary button-hero"><?php esc_html_e( 'Feature Request', 'bgtfw' ); ?></a></p>
		</div>
		<div class="welcome-panel col">
			<h3><?php esc_html_e( 'Submit Bugs and Issues', 'bgtfw' ); ?></h3>
			<p><?php esc_html_e( 'Our team is quick at confirming bugs and communicating updates. To submit your bug report or present issues, please visit our:', 'bgtfw' ); ?></p>
			<p><a href="https://www.boldgrid.com/feedback/communities/10-bug-reports" class="button button-primary button-hero"><?php esc_html_e( 'Bug Report', 'bgtfw' ); ?></a></p>
		</div>
	</div>

	<p style="text-align:center;"><?php esc_html_e( 'Check out the great BoldGrid Starter Content included with your theme purchase. If you\'re looking for a great starting point for your site - or you just need some inspirations - install the pre-built website to jumpstart the process.', 'bgtfw' ); ?></p>
</div>
