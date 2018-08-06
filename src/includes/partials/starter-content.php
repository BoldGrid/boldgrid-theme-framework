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

<div class="wrap">

	<h1>Starter Content</h1>

	<?php
	$query['starter_content'] = 'default';
	$customizer_link = add_query_arg( $query, admin_url( 'customize.php' ) );

	printf( '<a href="%1$s" class="button button-primary">%2$s</a>', $customizer_link, __( 'Install', 'bgtfw' ) );
	?>

</div>
