<?php
/**
 * Class: BoldGrid_Framework_Pro_Feature_Cards
 *
 * This Class is used for generating Pro Feature Cards for use on the Pro Features page.
 *
 * @since      SINCEVERSION
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid_Framework_Pro_Feature_Cards
 *
 * This Class is used for generating Pro Feature Cards for use on the Pro Features page.
 *
 * @since      SINCEVERSION
 */
class BoldGrid_Framework_Pro_Feature_Cards {

	/**
	 * Configs
	 *
	 * @since SINCEVERSION
	 * @var array
	 */
	public $configs = array();

	/**
	 * Constructor
	 *
	 * @since SINCEVERSION
	 * @param array $configs Theme Configs.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
	}

	/**
	 * Print Cards
	 *
	 * This function prints cards as used by the 'bgtfw_pro_feature_cards'
	 * action hook.
	 *
	 * @since SINCEVERSION
	 */
	public function print_cards() {
		if ( ! isset( $this->configs['pro-feature-cards'] ) ) {
			return;
		}

		?>
		<div class="pro-cards-container">
		<?php
		foreach ( $this->configs['pro-feature-cards'] as $card_slug => $card ) {
			$title      = empty( $card['title'] ) ? '' : $card['title'];
			$subtitle   = empty( $card['subtitle'] ) ? '' : $card['subtitle'];
			$learn_more = empty( $card['learn-more'] ) ? '' : $card['learn-more'];
			$video      = empty( $card['video'] ) ? '' : $card['video'];

			$image = $this->get_image_url( $card_slug );
			?>
			<div class="card <?php echo esc_attr( $card_slug ); ?>-card">
				<div class="title"><h3><?php echo esc_html( $card['title'] ); ?></h3></div>
				<div class="subtitle"><h4><?php echo esc_html( $card['subtitle'] ); ?></h4></div>
				<div class="image" style="background-image: url(<?php echo esc_url( $image ); ?>)"></div>
				<div class="action-links">
					<?php if ( $learn_more ) : ?>
						<a class="button" href="<?php echo esc_url( $learn_more ); ?>"><?php echo esc_html__('Learn More', 'bgtfw' ); ?></a>
					<?php endif; ?>
					<?php if ( $video ) : ?>
						<a href="<?php echo esc_url( $video ); ?>"><span class="dashicons dashicons-video-alt3"></span></a>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Get Image Url
	 *
	 * Gets the URL of the image for a card
	 *
	 * @since SINCEVERSION
	 * @param string $card_slug Slug of the card.
	 */
	public function get_image_url( $card_slug ) {
		$image_dir = $this->configs['framework']['admin_asset_dir'] . 'img/';
		$image_url = $image_dir . $card_slug . '.png';

		return $image_url;
	}
}
