<?php
/**
 * Customizer Controls Configs.
 *
 * @package Boldgrid_Theme_Framework
 * @subpackage Boldgrid_Theme_Framework\Configs\Customizer\Controls
 *
 * @since 2.0.0
 *
 * @return array Controls to create in the WordPress Customizer.
 */

return array(

	'bgtfw_logo_dropdown' => [
		'type'                => 'bgtfw-dropdown-menu',
		'transport'           => 'postMessage',
		'label'               => __( 'Branding', 'bgtfw' ),
		'active_label'        => __( 'Site Logo', 'bgtfw' ),
		'default'             => false,
		'settings'            => 'bgtfw_logo_dropdown',
		'section'             => 'title_tagline',
		'priority'            => -1,
		'help_label'          => __( 'Tip - Show / Hide Branding Elements', 'bgtfw' ),
		'help_text'           => sprintf(
			__(
				'Depending on the active header layout, the Logo may not be displayed in your header. You can enable the Logo, %3$sSite Title%1$s, and %4$sTagline%1$s in the %2$sHeader Layout%1$s Section.',
				'bgtfw'
			),
			'</a>',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Header Layouts Section" data-focusType="section" data-focusId="bgtfw_header_presets">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Site Title Section" data-focusType="section" data-focusId="bgtfw_site_title">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Tagline Section" data-focusType="section" data-focusId="bgtfw_tagline">'
		),
		'additional_controls' => array(
			array(
				'label'      => __( 'Header Layout', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_header_presets',
			),
			array(
				'label'      => __( 'Site Tagline', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_tagline',
			),
			array(
				'label'      => __( 'Site Title ', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_site_title',
			),
		),
		'faq_links'           => array(
			array(
				'label' => __( 'Working with the Logo', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/working-with-your-site-title-logo-and-tagline-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Working with Fonts', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/working-with-fonts-in-boldgrid-crio/' ),
			),
		),
	],
	'bgtfw_dropdown_title' => [
		'type'                => 'bgtfw-dropdown-menu',
		'transport'           => 'postMessage',
		'label'               => __( 'Branding', 'bgtfw' ),
		'active_label'        => __( 'Site Title', 'bgtfw' ),
		'default'             => false,
		'settings'            => 'bgtfw_dropdown_title',
		'section'             => 'bgtfw_site_title',
		'priority'            => -1,
		'help_label'          => __( 'Tip - Show / Hide Branding Elements', 'bgtfw' ),
		'help_text'           => sprintf(
			__(
				'Depending on the active header layout, the Site Title may not be displayed in your header. You can enable the Site Title, %3$sLogo%1$s, and %4$sTagline%1$s in the %2$sHeader Layout%1$s Section.',
				'bgtfw'
			),
			'</a>',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Header Layout Section" data-focusType="section" data-focusId="bgtfw_header_presets">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Logo Section" data-focusType="section" data-focusId="title_tagline">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Tagline Section" data-focusType="section" data-focusId="bgtfw_tagline">'
		),
		'additional_controls' => array(
			array(
				'label'      => __( 'Header Layout', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_header_presets',
			),
			array(
				'label'      => __( 'Site Logo', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'title_tagline',
			),
			array(
				'label'      => __( 'Site Tagline', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_tagline',
			),
			array(
				'label'      => __( 'Fonts', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'boldgrid_typography',
			),
		),
		'faq_links'           => array(
			array(
				'label' => __( 'Working with the Site Title', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/working-with-your-site-title-logo-and-tagline-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Working with Fonts', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/working-with-fonts-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Choose the Color Palette', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/customizing-the-color-palette/' ),
			),
		),
	],
	'bgtfw_dropdown_tagline' => [
		'type'                => 'bgtfw-dropdown-menu',
		'transport'           => 'postMessage',
		'label'               => __( 'Branding', 'bgtfw' ),
		'active_label'        => __( 'Site Tagline', 'bgtfw' ),
		'default'             => false,
		'settings'            => 'bgtfw_dropdown_tagline',
		'section'             => 'bgtfw_tagline',
		'priority'            => -1,
		'help_label'          => __( 'Tip - Show / Hide Branding Elements', 'bgtfw' ),
		'help_text'           => sprintf(
			__(
				'Depending on the active header layout, the Tagline may not be displayed in your header. You can enable the Tagline, %3$sSite Title%1$s, and %4$sLogo%1$s in the %2$sHeader Layout%1$s Section.',
				'bgtfw'
			),
			'</a>',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Header Layout Section" data-focusType="section" data-focusId="bgtfw_header_presets">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Site Title Section" data-focusType="section" data-focusId="bgtfw_site_title">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Logo Section" data-focusType="section" data-focusId="title_tagline">'
		),
		'additional_controls' => array(
			array(
				'label'      => __( 'Header Layout', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_header_presets',
			),
			array(
				'label'      => __( 'Site Logo', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'title_tagline',
			),
			array(
				'label'      => __( 'Site Title', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_site_title',
			),
			array(
				'label'      => __( 'Fonts', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'boldgrid_typography',
			),
		),
		'faq_links'           => array(
			array(
				'label' => __( 'Working with the Tagline', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/working-with-your-site-title-logo-and-tagline-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Working with Fonts', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/working-with-fonts-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Choose the Color Palette', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/customizing-the-color-palette/' ),
			),
		),
	],
	'bgtfw_dropdown_typography' => [
		'type'                => 'bgtfw-dropdown-menu',
		'transport'           => 'postMessage',
		'label'               => __( 'Font', 'bgtfw' ),
		'active_label'        => __( 'Fonts', 'bgtfw' ),
		'default'             => false,
		'settings'            => 'bgtfw_dropdown_typography',
		'section'             => 'boldgrid_typography',
		'priority'            => -1,
		'help_label'          => __( 'Tip - Changing and Styling Fonts', 'bgtfw' ),
		'help_text'           => sprintf(
			__(
				'Choose your Heading color from the active Color Palette. The Main Text color will be black or white depending on the site background. Site elements like the %2$sSite Title%1$s and %2$sTagline%1$s can use different fonts and colors than what\'s shown here, please explore all areas to style your typography.',
				'bgtfw'
			),
			'</a>',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Site Title Section" data-focusType="section" data-focusId="bgtfw_site_title">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Tagline Section" data-focusType="section" data-focusId="bgtfw_tagline">'
		),
		'additional_controls' => array(
			array(
				'label'      => __( 'Site Title', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_site_title',
			),
			array(
				'label'      => __( 'Tagline', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_tagline',
			),
		),
		'faq_links'           => array(
			array(
				'label' => __( 'Working with Fonts', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/working-with-fonts-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Working with the Site Title and Tagline', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/working-with-your-site-title-logo-and-tagline-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Choose the Color Palette', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/customizing-the-color-palette/' ),
			),
			array(
				'label' => __( 'Style the Site Links', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/site-content-design-tools-in-boldgrid-crio/' ),
			),
		),
	],
	'bgtfw_dropdown_header_presets' => [
		'type'                => 'bgtfw-dropdown-menu',
		'transport'           => 'postMessage',
		'label'               => __( 'Branding', 'bgtfw' ),
		'active_label'        => __( 'Header Layout', 'bgtfw' ),
		'default'             => false,
		'settings'            => 'bgtfw_dropdown_header_presets',
		'section'             => 'bgtfw_header_presets',
		'priority'            => -1,
		'help_label'          => __( 'Tip - Choosing a Header Layout', 'bgtfw' ),
		'help_text'           => sprintf(
			__(
				'After choosing a new Header Layout, you can select if the Site Title, Tagline, and/or Logo display. You can also create a %5$sCustom Header%1$s if desired. You can also %2$sstyle your Site Title%1$s, %3$supload your Logo%1$s, and %4$sstyle your Tagline%1$s if needed.',
				'bgtfw'
			),
			'</a>',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Site Title Section" data-focusType="section" data-focusId="bgtfw_site_title">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Logo Section" data-focusType="section" data-focusId="title_tagline">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Tagline Section" data-focusType="section" data-focusId="bgtfw_tagline">',
			'<a class="bgtfw-inline-af-link custom-preset" href="#" title="Go To Custom Header Section" data-focusType="section" data-focusId="bgtfw_header_layout_advanced">'
		),
		'additional_controls' => array(
			array(
				'label'      => __( 'Logo', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'title_tagline',
			),
			array(
				'label'      => __( 'Tagline', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_tagline',
			),
			array(
				'label'      => __( 'Site Title', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_site_title',
			),
			array(
				'label'      => __( 'Header Background', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'header_image',
			),
		),
		'faq_links'           => array(
			array(
				'label' => __( 'Choose a Header Layout', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/customizing-the-header-design-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Change the Header Background', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/how-to-change-the-header-background-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Working with Widgets', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/working-with-header-and-footer-widgets-in-boldgrid-crio/' ),
			),
		),
	],
	'bgtfw_dropdown_header_background' => [
		'type'                => 'bgtfw-dropdown-menu',
		'transport'           => 'postMessage',
		'label'               => __( 'Background', 'bgtfw' ),
		'active_label'        => __( 'Header Background', 'bgtfw' ),
		'default'             => false,
		'settings'            => 'bgtfw_dropdown_header_background',
		'section'             => 'header_image',
		'priority'            => -1,
		'help_label'          => __( 'Tip - Changing Backgrounds', 'bgtfw' ),
		'help_text'           => sprintf(
			__(
				'Your Header Background can be a color, image, or video. A video background can even show on internal pages. If you don’t see the color you want to use, please update your %2$sColor Palette%1$s. Your %3$sSite Background%1$s and %4$sFooter Background%1$s can be changed as well.',
				'bgtfw'
			),
			'</a>',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Color Palette Section" data-focusType="section" data-focusId="colors">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Site Background Section" data-focusType="section" data-focusId="background_image">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Footer Background Section" data-focusType="section" data-focusId="bgtfw_footer_colors">'
		),
		'additional_controls' => array(
			array(
				'label'      => __( 'Color Palette', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'colors',
			),
			array(
				'label'      => __( 'Header Layout', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_header_presets',
			),
			array(
				'label'      => __( 'Site Background', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'background_image',
			),
			array(
				'label'      => __( 'Footer Background', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_footer_colors',
			),
		),
		'faq_links'           => array(
			array(
				'label' => __( 'Choose a Header Layout', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/customizing-the-header-design-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Change the Header Background', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/how-to-change-the-header-background-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Choose Your Color Palette', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/customizing-the-color-palette/' ),
			),
		),
	],
	'bgtfw_dropdown_site_background' => [
		'type'                => 'bgtfw-dropdown-menu',
		'transport'           => 'postMessage',
		'label'               => __( 'Background', 'bgtfw' ),
		'active_label'        => __( 'Site Background', 'bgtfw' ),
		'default'             => false,
		'settings'            => 'bgtfw_dropdown_site_background',
		'section'             => 'background_image',
		'priority'            => -1,
		'help_label'          => __( 'Tip - Changing Backgrounds', 'bgtfw' ),
		'help_text'           => sprintf(
			__(
				'Your Site Background is usually the last color in your %2$sColor Palette%1$s, but you can also upload an image or select a background pattern here. If you don’t see the color you want to use, please update your %2$sColor Palette%1$s.',
				'bgtfw'
			),
			'</a>',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Color Palette Section" data-focusType="section" data-focusId="colors">'
		),
		'additional_controls' => array(
			array(
				'label'      => __( 'Color Palette', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'colors',
			),
			array(
				'label'      => __( 'Header Background', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'header_image',
			),
			array(
				'label'      => __( 'Footer Background', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_footer_colors',
			),
		),
		'faq_links'           => array(
			array(
				'label' => __( 'Change the Site Background', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/changing-your-site-background-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Choose the Color Palette', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/customizing-the-color-palette/' ),
			),
		),
	],
	'bgtfw_dropdown_footer_colors' => [
		'type'                => 'bgtfw-dropdown-menu',
		'transport'           => 'postMessage',
		'label'               => __( 'Background', 'bgtfw' ),
		'active_label'        => __( 'Footer Background', 'bgtfw' ),
		'default'             => false,
		'settings'            => 'bgtfw_dropdown_footer_colors',
		'section'             => 'bgtfw_footer_colors',
		'priority'            => -1,
		'help_label'          => __( 'Tip - Changing Backgrounds', 'bgtfw' ),
		'help_text'           => sprintf(
			__(
				'After changing your Footer Colors, you may need to adjust your %2$sWidget%1$s colors. If you don’t see the color you want to use, please update your %3$sColor Palette%1$s. You may also want to change the %4$sFooter Design%1$s or %5$sSite Background%1$s.',
				'bgtfw'
			),
			'</a>',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Widgets Panel" data-focusType="panel" data-focusId="widgets">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Color Palette Section" data-focusType="section" data-focusId="colors">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Footer Layout Section" data-focusType="section" data-focusId="boldgrid_footer_panel">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Site Background Section" data-focusType="section" data-focusId="background_image">'
		),
		'additional_controls' => array(
			array(
				'label'      => __( 'Widgets', 'bgtfw' ),
				'focus_type' => 'panel',
				'focus_id'   => 'widgets',
			),
			array(
				'label'      => __( 'Color Palette', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'colors',
			),
			array(
				'label'      => __( 'Header Background', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'header_image',
			),
			array(
				'label'      => __( 'Site Background', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'background_image',
			),
		),
		'faq_links'           => array(
			array(
				'label' => __( 'Change the Site Background', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/changing-your-site-background-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Choose the Color Palette', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/customizing-the-color-palette/' ),
			),
		),
	],
	'bgtfw_dropdown_color_palette' => [
		'type'                => 'bgtfw-dropdown-menu',
		'transport'           => 'postMessage',
		'label'               => __( 'Color', 'bgtfw' ),
		'active_label'        => __( 'Color Palette', 'bgtfw' ),
		'default'             => false,
		'settings'            => 'bgtfw_dropdown_color_palette',
		'section'             => 'colors',
		'priority'            => -1,
		'help_label'          => __( 'Tip - Changing Colors', 'bgtfw' ),
		'help_text'           => sprintf(
			__(
				'The colors chosen here are used across your entire website, changing one will update that color everywhere it’s used. The last position is the %2$sSite Background%1$s, but you can change it to an image or video. These colors are also used for the %3$sHeader Background%1$s and %4$sFooter Background%1$s.',
				'bgtfw'
			),
			'</a>',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Site Background Section" data-focusType="section" data-focusId="background_image">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Header Background Section" data-focusType="section" data-focusId="header_image">',
			'<a class="bgtfw-inline-af-link" href="#" title="Go To Footer Background Section" data-focusType="section" data-focusId="bgtfw_footer_colors">'
		),
		'additional_controls' => array(
			array(
				'label'      => __( 'Site Background', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'background_image',
			),
			array(
				'label'      => __( 'Header Background', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'header_image',
			),
			array(
				'label'      => __( 'Footer Background', 'bgtfw' ),
				'focus_type' => 'section',
				'focus_id'   => 'bgtfw_footer_colors',
			),
			array(
				'label'      => __( 'Menu Design', 'bgtfw' ),
				'focus_type' => 'panel',
				'focus_id'   => 'bgtfw_menus_panel',
			),
		),
		'faq_links'           => array(
			array(
				'label' => __( 'Change the Header Background', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/how-to-change-the-header-background-in-boldgrid-crio/' ),
			),
			array(
				'label' => __( 'Choose the Footer Background', 'bgtfw' ),
				'url'   => esc_url( 'https://www.boldgrid.com/support/boldgrid-crio-supertheme-product-guide/how-to-change-the-footer-colors-in-boldgrid-crio/' ),
			),
		),
	],
);