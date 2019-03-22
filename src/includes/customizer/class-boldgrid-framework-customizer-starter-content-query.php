<?php
/**
 * Class: BoldGrid_Framework_Customizer_Starter_Content
 *
 * This is used for the starter content import functionality in the WordPress customizer.
 *
 * @since      2.0.0
 * @category   Customizer
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Customizer
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/**
 * BoldGrid_Framework_Customizer_Starter_Content_Query
 *
 * Responsible for the starter content import functionality in the WordPress customizer.
 *
 * @since 2.0.0
 */
class BoldGrid_Framework_Customizer_Starter_Content_Query {

	/**
	 * Auto draft post IDs in the current changeset.
	 *
	 * @var array
	 */
	protected $changeset_auto_draft_post_ids = array();

	/**
	 * Add hooks to ensure auto-draft posts from the current changeset are included in WP_Query results.
	 *
	 * @param WP_Customize_Manager $wp_customize Manager.
	 */
	public function make_auto_drafts_queryable( WP_Customize_Manager $wp_customize ) {
		global $wp_post_statuses;

		// WordPress 4.7 required.
		if ( ! method_exists( $wp_customize, 'changeset_data' ) ) {
			return;
		}

		/*
		* Note that we cannot use $wp_customize->get_setting( 'nav_menus_created_posts' )->value()
		* for the sake of unauthenticated users who may be previewing the changeset on the
		* frontend. The WP_Customize_Nav_Menus::sanitize_nav_menus_created_posts() function
		* removes IDs from the list for which the current user cannot edit.
		*/
		$changeset_data = $wp_customize->changeset_data();
		if ( isset( $changeset_data['nav_menus_created_posts']['value'] ) && is_array( $changeset_data['nav_menus_created_posts']['value'] ) ) {
			$this->changeset_auto_draft_post_ids = array_merge(
				$this->changeset_auto_draft_post_ids,
				$changeset_data['nav_menus_created_posts']['value']
			);
		}

		/*
		* Now merge the starter content post IDs  from the changeset with any additional
		* IDs for page/post stubs created for nav menus which may not have been written
		* into the changeset yet (which are still being sent in POST data).
		*/
		$nav_menus_created_posts_setting = $wp_customize->get_setting( 'nav_menus_created_posts' );
		if ( $nav_menus_created_posts_setting ) {
			$this->changeset_auto_draft_post_ids = array_merge(
				$this->changeset_auto_draft_post_ids,
				$nav_menus_created_posts_setting->value()
			);
		}

		// If there are no auto-draft posts in the current changeset, then there is nothing to do here.
		if ( empty( $this->changeset_auto_draft_post_ids ) ) {
			return;
		}

		/*
		* Ensure that starter content posts (with auto-draft status) will be considered
		* to have a public post status in in WP_Query::get_posts(), at:
		* https://github.com/WordPress/wordpress-develop/blob/4.7.3/src/wp-includes/class-wp-query.php#L2351-L2357
		* Compare this with WP_Customize_Nav_Menus::make_auto_draft_status_previewable(),
		* which apparently did not go far enough:
		*
		* $wp_post_statuses['auto-draft']->public = true;
		*/
		add_action( 'pre_get_posts', array( $this, 'prevent_filter_suppression' ), 100, 2 );
		add_filter( 'posts_where', array( $this, 'posts_where' ) );
	}

	/**
	 * Make sure that the posts_where filter will be applied.
	 *
	 * @param WP_Query $query The WP_Query instance.
	 */
	public function prevent_filter_suppression( WP_Query $query ) {
		$query->set( 'suppress_filters', false );
	}

	/**
	 * Filter WHERE clause for posts queries to exclude auto-draft posts that aren't part of the current changeset.
	 *
	 * When $wp_post_statuses['auto-draft']->public = true, then query contains post-status = 'auto-draft', but
	 * we are going to force the query to always add the changeset auto draft post ids when imported regardless of
	 * auto-draft post status being public.
	 *
	 * @link   https://developer.wordpress.org/reference/hooks/posts_where/
	 *
	 * @param  string $where The WHERE clause of the query.
	 * @return string $where Amended SQL WHERE.
	 */
	public function posts_where( $where ) {
		global $wpdb;
		$type = "'auto-draft'";
		$posts = $this->changeset_auto_draft_post_ids;
		if ( ( ! empty( $_REQUEST['customize_changeset_uuid'] ) ) ) {
			$type = "'draft'";
		}
		$autodrafts = "{$wpdb->posts}.post_status = {$type}";
		$posts_in = sprintf(
			" {$wpdb->posts}.ID IN ( %s )",
			join( ', ', array_map( 'intval', $posts ) )
		);
		if ( strpos( $where, "{$autodrafts}" ) !== false ) {
			$old_condition = "{$autodrafts}";
			$new_condition = "({$old_condition} AND {$posts_in})";
		} else {
			$old_condition = "{$wpdb->posts}.post_status = 'publish'";
			$new_condition = "({$old_condition} OR {$posts_in})";
		}
		$where = str_replace( $old_condition, $new_condition, $where );
		return $where;
	}

	/**
	 * Set the order for blog posts and archive pages by default.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_Query $query The WP_Query instance.
	 *
	 * @return WP_Query $query The WP_Query instance.
	 */
	public function set_main_query( WP_Query $query ) {
		if ( ( $query->is_archive() || $query->is_home() ) && $query->is_main_query() ) {
			$query->set( 'orderby', 'date modified title' );
			$query->set( 'order', 'desc' );
		}
		return $query;
	}

	/**
	 * Make sure that the posts_where filter will be applied.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args WP_Query args.
	 *
	 * @return array $args WP_Query args.
	 */
	public function set_recent_posts_query( $args ) {
		$args['orderby'] = 'date modified title';
		$args['order'] = 'desc';
		return $args;
	}
}
