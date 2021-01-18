<?php
/**
 * Class: BoldGrid_Framework_Pro_Feature_Cards
 *
 * This Class is used for generating Pro Feature Cards for use on the Pro Features page.
 *
 * @since      2.5.0
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: BoldGrid_Framework_Pro_Feature_Cards
 *
 * This Class is used for generating Pro Feature Cards for use on the Pro Features page.
 *
 * @since      2.5.0
 */
class BoldGrid_Framework_Pro_Feature_Cards {

	/**
	 * Configs
	 *
	 * @since 2.5.0
	 * @var array
	 */
	public $configs = array();

	/**
	 * Upgrade Url
	 *
	 * Upgrade URL used on pro feature cards and CTA. This does not contain the
	 * utm_content argument, that way it can be added individual for different locations
	 * on the page.
	 *
	 * @since 2.5.0
	 * @var string
	 */
	public $upgrade_url = 'https://boldgrid.com/wordpress-themes/crio/?utm_source=Crio_-_Pro_Features&utm_medium=Button&utm_campaign=Crio_Pro_Features';

	/**
	 * Constructor
	 *
	 * @since 2.5.0
	 *
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
	 * @since 2.5.0
	 */
	public function print_cards() {
		$current_user = wp_get_current_user();

		if ( ! isset( $this->configs['pro-feature-cards'] ) ) {
			return;
		}

		$notices           = array();
		$dismissed_notices = get_user_meta( $current_user->ID, 'crio_dismissed_feature_notices', true );
		$dismissed_notices = $dismissed_notices ? $dismissed_notices : array();
		?>
		<div class="pro-cards-container">
		<?php
		foreach ( $this->configs['pro-feature-cards'] as $card_slug => $card ) {
			$title      = empty( $card['title'] ) ? '' : $card['title'];
			$subtitle   = empty( $card['subtitle'] ) ? '' : $card['subtitle'];
			$learn_more = empty( $card['learn-more'] ) ? $this->upgrade_url . '&utm_content=Learn_More' : $card['learn-more'];
			$video      = empty( $card['video'] ) ? '' : $card['video'];
			$icon       = empty( $card['icon'] ) ? '' : $card['icon'];
			$color      = empty( $card['color'] ) ? '' : $card['color'];

			if ( isset( $card['show_notice'] ) ) {
				$notices[] = $card['show_notice'];
				$new_card  = in_array( $card['show_notice'], $dismissed_notices ) ? '' : ' new-card';
			} else {
				$new_card = '';
			}

			?>
			<div class="card <?php echo esc_attr( $card_slug ); ?>-card<?php echo esc_attr( $new_card ); ?>">
				<?php if ( $new_card ) : ?>
					<div class="new-ribbon-wrapper"><div class="new-ribbon">NEW</div></div>
				<?php endif; ?>
				<div class="title"><h3><?php echo esc_html( $card['title'] ); ?></h3></div>
				<div class="icon" style="border-top:3px solid #<?php echo esc_attr( $color ); ?>;border-bottom:1px solid #ddd">
					<span class="dashicons <?php echo esc_attr( $icon ); ?>" style="color: #<?php echo esc_attr( $color ); ?>"></span>
				</div>
				<div class="subtitle"><p><?php echo esc_html( $card['subtitle'] ); ?></p></div>
				<div class="action-links">
					<?php if ( $learn_more ) : ?>
						<a class="button" href="<?php echo esc_url( $learn_more ); ?>" target="_blank"><?php echo esc_html__( 'Learn More', 'bgtfw' ); ?></a>
					<?php endif; ?>
					<?php if ( $video ) : ?>
						<a href="<?php echo esc_url( $video ); ?>"><span class="dashicons dashicons-video-alt3"></span></a>
					<?php endif; ?>
				</div>
			</div>
			<?php
			if ( $notices ) {
				update_user_meta(
					$current_user->ID,
					'crio_dismissed_feature_notices',
					$notices
				);
			}
		}
	}

	/**
	 * Get Notice Counts
	 *
	 * This will get a list of notices and list of notices that have
	 * been dismissed by the current user, then return the count of that list
	 * in order to be displayed in the admin menu.
	 *
	 * @since 2.5.0
	 *
	 * @return int The number of notices.
	 */
	public function get_notice_counts() {
		$current_user = wp_get_current_user();

		if ( ! isset( $this->configs['pro-feature-cards'] ) ) {
			return 0;
		}

		$notices = array();

		foreach ( $this->configs['pro-feature-cards'] as $key => $value ) {
			if ( isset( $value['show_notice'] ) ) {
				$notices[] = $value['show_notice'];
			}
		}

		$dismissed_notices = get_user_meta( $current_user->ID, 'crio_dismissed_feature_notices', true );
		$dismissed_notices = $dismissed_notices ? $dismissed_notices : array();

		return count( array_diff( $notices, $dismissed_notices ) );
	}

	/**
	 * Show Notice Counts
	 *
	 * This is used to modify the menu and submenu in the
	 * WordPress Dashboard, by adding notice counts.
	 * This is fired in the 'admin_menu' action hook.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/admin_menu/
	 * @since 2.5.0
	 */
	public function show_notice_counts() {
		global $menu;
		global $submenu;

		$counts = $this->get_notice_counts();
		if ( empty( $counts ) ) {
			return;
		}

		foreach ( $menu as $menu_position => $menu_data ) {
			if ( 'crio' === $menu_data[2] ) {
				$menu[ $menu_position ][0] .= ' <span class="update-plugins count-1"><span class="plugin-count">' . $counts . '</span></span>';
				break;
			}
		}

		if ( isset( $submenu['crio'] ) && isset( $submenu['crio'][1] ) ) {
			$submenu['crio'][1][0] .= ' <span class="update-plugins count-1"><span class="plugin-count">' . $counts . '</span></span>';
		}
	}
}
