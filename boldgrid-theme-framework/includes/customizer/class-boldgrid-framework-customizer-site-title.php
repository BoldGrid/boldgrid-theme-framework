<?php
/**
 * Class: Boldgrid_Framework_Customizer_Site_Title
 *
 * This contains the $controls to pass to Kirki to modify the site title
 * and logo controls that are used in the WordPress customizer for BoldGrid
 * themes.
 *
 * @since      1.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer_Site_Title
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */
class Boldgrid_Framework_Customizer_Site_Title {

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       string     $configs       The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 * @param     string $configs       The BoldGrid Theme Framework configurations.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 * @return    array      $controls      array of controls to pass to Kirki.
	 */
	public function site_identity_controls( $controls ) {

		$site_title_customizer = $this->configs['customizer-options']['site-title']['site-title'];

		if ( $site_title_customizer == true ) {

			/**
			* Font Toggle
			*/
			$controls['boldgrid_font_toggle'] = array(
			    'type'        => 'toggle',
			    'setting'     => 'boldgrid_font_toggle',
			    'label'       => __( 'Custom Font', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'     => false,
			);

			/**
			* Logo Fonts
			*/
			$controls['logo_font_family'] = array(
			    'type'     => 'select',
			    'setting'  => 'logo_font_family',
			    'label'    => __( 'Font Family', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 'Oswald',
			    'choices'  => kirki_Fonts::get_font_choices(),
			    'output'   => array(
			    	array(
				        'element'  => '.site-title',
				        'property' => 'font-family',
		    		),
			    ),
			);

			$controls['logo_font_size'] = array(
			    'type'     => 'slider',
			    'setting'  => 'logo_font_size',
			    'label'    => __( 'Font Size', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 55,
			    'choices'  => array(
			        'min'  => 1,
			        'max'  => 250,
			        'step' => 1,
			    ),
			    'output' => array(
			    	array(
				        'element'  => '.site-title',
				        'property' => 'font-size',
				        'units'    => 'px',
		    		),
			    ),
			);

			/**
			* Logo Styling
			*/
			$controls['logo_text_transform'] = array(
			    'type'     => 'select',
			    'setting'  => 'logo_text_transform',
			    'label'    => __( 'Capitalization', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 'uppercase',
			    'choices'  => array(
			        'capitalize' => 'Capitalize',
			        'uppercase' => 'All Uppercase',
			        'lowercase' => 'All Lowercase',
			        'none' => 'Unmodified',
			    ),
			    'output'   => array(
			    	array(
			        	'element'  => '.site-title',
			        	'property' => 'text-transform',
	    			),
			    ),
			);

			$controls['logo_text_decoration'] = array(
			    'type'     => 'select',
			    'setting'  => 'logo_text_decoration',
			    'label'    => __( 'Decoration', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 'none',
			    'choices'  => array(
			        'none' => 'Normal',
			        'overline' => 'Overline',
			        'underline' => 'Underline',
			        'line-through' => 'Strikethrough',
			    ),
			    'output' => array(
			        array(
			            'element'  => '.site-title a',
			            'property' => 'text-decoration',
			        ),
			    ),
			);

			$controls['logo_text_decoration_hover'] = array(
			    'type'     => 'select',
			    'setting'  => 'logo_text_decoration_hover',
			    'label'    => __( 'Decoration Hover', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 'underline',
			    'choices'  => array(
			        'none' => 'Normal',
			        'overline' => 'Overline',
			        'underline' => 'Underline',
			        'line-through' => 'Strikethrough',
			    ),
			    'output' => array(
			        array(
			            'element'  => '.site-title a:hover',
			            'property' => 'text-decoration',
			        ),
			        array(
			            'element'  => '.site-title a:focus',
			            'property' => 'text-decoration',
			        ),
			    ),
			);

			/**
			* Font Toggle
			*/
			$controls['boldgrid_position_toggle'] = array(
			    'type'        => 'toggle',
			    'setting'     => 'boldgrid_position_toggle',
			    'label'       => __( 'Position', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'     => false,
			);

			/**
			* Logo Spacing
			*/
			$controls['logo_margin_top'] = array(
			    'type'     => 'slider',
			    'setting'  => 'logo_margin_top',
			    'label'    => __( 'Top Margin', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 10,
			    'choices'  => array(
			        'min'  => -20,
			        'max'  => 100,
			        'step' => 1,
			    ),
			    'output' => array(
			    	array(
			        	'element'  => '.site-title',
			        	'property' => 'margin-top',
			        	'units'    => 'px',
		    		),
			    ),
			);

			$controls['logo_margin_bottom'] = array(
			    'type'     => 'slider',
			    'setting'  => 'logo_margin_bottom',
			    'label'    => __( 'Bottom Margin', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 20,
			    'choices'  => array(
			        'min'  => -20,
			        'max'  => 100,
			        'step' => 1,
			    ),
			    'output' => array(
			    	array(
			        	'element'  => '.site-title',
			        	'property' => 'margin-bottom',
			        	'units'    => 'px',
		    		),
			    ),
			);

			$controls['logo_margin_left'] = array(
			    'type'     => 'slider',
			    'setting'  => 'logo_margin_left',
			    'label'    => __( 'Horizontal Margin', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 0,
			    'choices'  => array(
			        'min'  => -50,
			        'max'  => 50,
			        'step' => 1,
			    ),
			    'output' => array(
			    	array(
				        'element'  => '.site-title',
				        'property' => 'margin-left',
				        'units'    => 'px',
		    		),
			    ),
			);

			$controls['logo_line_height'] = array(
			    'type'     => 'slider',
			    'setting'  => 'logo_line_height',
			    'label'    => __( 'Line Height', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 150,
			    'choices'  => array(
			        'min'  => 50,
			        'max'  => 150,
			        'step' => 1,
			    ),
			    'output' => array(
			    	array(
			        	'element'  => '.site-title',
			        	'property' => 'line-height',
			        	'units'    => '%',
		    		),
			    ),
			);

			$controls['logo_letter_spacing'] = array(
			    'type'     => 'slider',
			    'setting'  => 'logo_letter_spacing',
			    'label'    => __( 'Letter Spacing', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 1,
			    'choices'  => array(
			        'min'  => 1,
			        'max'  => 50,
			        'step' => 1,
			    ),
			    'output' => array(
			    	array(
			        	'element'  => '.site-title',
			        	'property' => 'letter-spacing',
			        	'units'    => 'px',
		    		),
			    ),
			);

			/**
			* Logo Shadow
			*/
			$controls['logo_shadow_switch'] = array(
			    'type'        => 'toggle',
			    'setting'     => 'logo_shadow_switch',
			    'label'       => __( 'Custom Shadow', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'     => 0,
			);

			$controls['logo_shadow_horizontal'] = array(
			    'type'     => 'slider',
			    'setting'  => 'logo_shadow_horizontal',
			    'label'    => __( 'Horizontal Shadow', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 5,
			    'choices'  => array(
			        'min'  => -25,
			        'max'  => 25,
			        'step' => 1,
			    ),
			);

			$controls['logo_shadow_vertical'] = array(
			    'type'     => 'slider',
			    'setting'  => 'logo_shadow_vertical',
			    'label'    => __( 'Vertical Shadow', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 5,
			    'choices'  => array(
			        'min'  => -25,
			        'max'  => 25,
			        'step' => 1,
			    ),
			);

			$controls['boldgrid_logo_size'] = array(
			    'type'     => 'slider',
			    'setting'  => 'boldgrid_logo_size',
			    'label'    => __( 'Logo Size', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 260,
			    'choices'  => array(
			        'min'  => 120,
			        'max'  => 555,
			        'step' => 1,
			    ),
				'output' => array(
					array(
						'element'  => '.logo-site-title img',
						'property' => 'width',
						'units'    => 'px',
					),
				),
			);

			$controls['logo_shadow_blur'] = array(
			    'type'     => 'slider',
			    'setting'  => 'logo_shadow_blur',
			    'label'    => __( 'Shadow Blur', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => 5,
			    'choices'  => array(
			        'min'  => 1,
			        'max'  => 25,
			        'step' => 1,
			    ),
			);

			$controls['logo_shadow_color'] = array(
			    'type'     => 'color-alpha',
			    'setting'  => 'logo_shadow_color',
			    'label'    => __( 'Shadow Color', 'bgtfw' ),
			    'section'  => 'title_tagline',
			    'default'  => '#000',
			);

			/**
			* Return
			*/
			return $controls;
		}

	}

	/**
	 * Adds text shadow based on logo shadow selection
	 *
	 * @since     1.0.0
	 */
	public function title_text_shadow() {

	    if ( get_theme_mod( 'logo_shadow_switch' ) == '1' ) : ?>

	        <style type="text/css">
	            .site-title { text-shadow:<?php echo get_theme_mod( 'logo_shadow_horizontal', '5' ); ?>px <?php echo get_theme_mod( 'logo_shadow_vertical', '5' ); ?>px <?php echo get_theme_mod( 'logo_shadow_blur', '5' ); ?>px <?php echo get_theme_mod( 'logo_shadow_color', '#000000' ); ?>; }
	        </style>

	    <?php endif;

	}
}
