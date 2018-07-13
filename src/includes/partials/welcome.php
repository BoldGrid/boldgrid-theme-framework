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
?>

<div class="wrap about-wrap">

	<h1>Welcome to BoldGrid Crio!</h1>

	<p class="about-text">You have successfully installed BoldGrid Crio, and are now ready to start building an amazing site.
	Head to <a href="http://www.boldgrid.com/support/boldgrid-crio" target="_blank">BoldGrid.com</a> to learn more about what Crio can do in our support center.</p>

	<h2 class="nav-tab-wrapper wp-clearfix">
		<a href="<?php echo admin_url( '?page=' . $this->menu_slug ); ?>" class="nav-tab nav-tab-active">Getting Started</a>
	</h2>

	<p>We recommend following these steps to get the most out of BoldGrid Crio:</p>

	<ol>
		<li>
			Register your account.  You will be taken to BoldGrid Central to link your theme purchase and generate a connect key.  This key will unlock all of the features of your theme, and allows you to submit personalized support requests.
		</li>
		<li>
			Add your connect key to your BoldGrid Crio install.  You will be prompted to enter the connect key you just generated when you login to WordPress admin.
		</li>
		<li>
			Install and activate your included copy of the BoldGrid Post and Page Builder premium plugin.  This adds premium Block layouts and advanced page design controls.
		</li>
		<li>
			(Recommended) Check out the great BoldGrid starter content included with your theme purchase.  If you are looking for a great starting point for your site - or are just looking for some inspiration - install the BoldFresh prebuilt website to jumpstart your site building process.
		</li>
	</ol>

</div>
