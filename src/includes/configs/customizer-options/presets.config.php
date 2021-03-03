<?php
/**
 * Customizer Presets Configs.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs
 *
 * @since 2.0.0
 *
 * @return array Presets to use in the WordPress Customizer.
 */

return array(
	'header'        => array(
		'lbrm'   => array(
			'label'  => __( 'Branding + Menu', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'w',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
						array(
							'type'  => 'boldgrid_menu_main',
							'key'   => 'menu',
							'align' => 'e',
							'uid'   => 'h48',
						),
					),
				),
			),
		),
		'lbcmrs' => array(
			'label'  => __( 'Branding + Menu + Social Icons', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'w',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
						array(
							'type'  => 'boldgrid_menu_main',
							'key'   => 'menu',
							'align' => 'c',
							'uid'   => 'h48',
						),
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_social',
							'align' => 'e',
							'uid'   => 'h110',
						),
					),
				),
			),
		),
		'lmrb'   => array(
			'label'  => __( 'Menu + Branding', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'  => 'boldgrid_menu_main',
							'key'   => 'menu',
							'align' => 'w',
							'uid'   => 'h48',
						),
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'e',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
					),
				),
			),
		),
		'lbrslm' => array(
			'label'  => __( 'Branding and Menu + Social', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'w',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_social',
							'align' => 'e',
							'uid'   => 'h110',
						),
					),
				),
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_main',
							'align' => 'w',
							'uid'   => 'h105',
						),
					),
				),
			),
		),
		'lbrscm' => array(
			'label'  => __( 'Branding + Social Icons w/ Center Menu', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'w',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_social',
							'align' => 'e',
							'uid'   => 'h110',
						),
					),
				),
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_main',
							'align' => 'c',
							'uid'   => 'h105',
						),
					),
				),
			),
		),
		'lbrsrm' => array(
			'label'  => __( 'Branding + Social Icons and Menu', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'w',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_social',
							'align' => 'e',
							'uid'   => 'h110',
						),
					),
				),
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_main',
							'align' => 'e',
							'uid'   => 'h105',
						),
					),
				),
			),
		),
		'cbcm'   => array(
			'label'  => __( 'Centered Branding above Menu', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'c',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
					),
				),
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_main',
							'align' => 'c',
							'uid'   => 'h105',
						),
					),
				),
			),
		),
		'cmcb'   => array(
			'label'  => __( 'Centered Menu above Branding', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_main',
							'align' => 'c',
							'uid'   => 'h105',
						),
					),
				),
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'c',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
					),
				),
			),
		),
	),
	'sticky_header' => array(
		'lbrm'   => array(
			'label'  => __( 'Branding + Menu', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'w',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
						array(
							'type'  => 'boldgrid_menu_sticky-main',
							'key'   => 'menu',
							'align' => 'e',
							'uid'   => 'h48',
						),
					),
				),
			),
		),
		'lbcmrs' => array(
			'label'  => __( 'Branding + Menu + Social Icons', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'w',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
						array(
							'type'  => 'boldgrid_menu_sticky-main',
							'key'   => 'menu',
							'align' => 'c',
							'uid'   => 'h48',
						),
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_sticky-social',
							'align' => 'e',
							'uid'   => 'h110',
						),
					),
				),
			),
		),
		'lmrb'   => array(
			'label'  => __( 'Menu + Branding', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'  => 'boldgrid_menu_sticky-main',
							'key'   => 'menu',
							'align' => 'w',
							'uid'   => 'h48',
						),
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'e',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
					),
				),
			),
		),
		'lbrslm' => array(
			'label'  => __( 'Branding and Menu + Social', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'w',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_sticky-social',
							'align' => 'e',
							'uid'   => 'h110',
						),
					),
				),
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_sticky-main',
							'align' => 'w',
							'uid'   => 'h105',
						),
					),
				),
			),
		),
		'lbrscm' => array(
			'label'  => __( 'Branding + Social Icons w/ Center Menu', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'w',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_sticky-social',
							'align' => 'e',
							'uid'   => 'h110',
						),
					),
				),
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_sticky-main',
							'align' => 'c',
							'uid'   => 'h105',
						),
					),
				),
			),
		),
		'lbrsrm' => array(
			'label'  => __( 'Branding + Social Icons and Menu', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'w',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_sticky-social',
							'align' => 'e',
							'uid'   => 'h110',
						),
					),
				),
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_sticky-main',
							'align' => 'e',
							'uid'   => 'h105',
						),
					),
				),
			),
		),
		'cbcm'   => array(
			'label'  => __( 'Centered Branding above Menu', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'c',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
					),
				),
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_sticky-main',
							'align' => 'c',
							'uid'   => 'h105',
						),
					),
				),
			),
		),
		'cmcb'   => array(
			'label'  => __( 'Centered Menu above Branding', 'bgtfw' ),
			'config' => array(
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'key'   => 'menu',
							'type'  => 'boldgrid_menu_sticky-main',
							'align' => 'c',
							'uid'   => 'h105',
						),
					),
				),
				array(
					'container' => 'container',
					'items'     => array(
						array(
							'type'    => 'boldgrid_site_identity',
							'key'     => 'branding',
							'align'   => 'c',
							'display' => array(
								array(
									'selector' => '.custom-logo',
									'display'  => 'show',
									'title'    => 'Logo',
								),
								array(
									'selector' => '.site-title',
									'display'  => 'hide',
									'title'    => 'Title',
								),
								array(
									'selector' => '.site-description',
									'display'  => 'hide',
									'title'    => 'Tagline',
								),
							),
							'uid'     => 'h47',
						),
					),
				),
			),
		),
	),
);
