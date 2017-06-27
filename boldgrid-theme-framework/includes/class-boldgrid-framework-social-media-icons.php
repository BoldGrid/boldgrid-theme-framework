<?php
/**
 * Class: Boldgrid_Framework_Social_Media_Icons
 *
 * This is the class responsible for adding the social media icons to nav menus
 * when a user inputs a social url as the nav's URL ie facebook, twitter, etc.
 *
 * @since      1.0.0
 * @package    Boldgrid_Framework
 * @subpackage Boldgrid_Framework_Social_Media_Icons
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Class: Boldgrid_Framework_Social_Media_Icons
 *
 * This is the class responsible for adding the social media icons to nav menus
 * when a user inputs a social url as the nav's URL ie facebook, twitter, etc.
 *
 * @since      1.0.0
 */
class Boldgrid_Framework_Social_Media_Icons {

	/**
	 * Hide menu text or show it (ie the word facebook|facebook icon + facebook)
	 * Override with boldgrid_icon_hide_text filter
	 *
	 * @var     bool
	 * @since   1.0.0
	 */
	var $hide_text = true;

	/**
	 * Contains the supported FontAwesome icons.
	 *
	 * @var     array      links social site URLs with CSS classes for icons
	 * @since   1.0.0
	 */
	var $networks = array(

		'bitbucket.org' => array(
			'name' => 'Bitbucket',
			'class' => 'bitbucket',
			'icon' => 'fa fa-bitbucket',
			'icon-sign' => 'fa fa-bitbucket-square',
			'icon-square-open' => 'fa fa-bitbucket fa-stack-1x',
			'icon-square' => 'fa fa-bitbucket fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-bitbucket fa-stack-1x',
			'icon-circle-open' => 'fa fa-bitbucket fa-stack-1x',
			'icon-circle' => 'fa fa-bitbucket fa-stack-1x',
		),

		'codepen.io' => array(
			'name' => 'Codepen',
			'class' => 'codepen',
			'icon' => 'fa fa-codepen',
			'icon-sign' => 'fa fa-codepen',
			'icon-square-open' => 'fa fa-codepen fa-stack-1x',
			'icon-square' => 'fa fa-codepen fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-codepen fa-stack-1x',
			'icon-circle-open' => 'fa fa-codepen fa-stack-1x',
			'icon-circle' => 'fa fa-codepen fa-stack-1x',
		),

		'dribbble.com' => array(
			'name' => 'Dribbble',
			'class' => 'dribbble',
			'icon' => 'fa fa-dribbble',
			'icon-sign' => 'fa fa-dribbble',
			'icon-square-open' => 'fa fa-dribbble fa-stack-1x',
			'icon-square' => 'fa fa-dribbble fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-dribbble fa-stack-1x',
			'icon-circle-open' => 'fa fa-dribbble fa-stack-1x',
			'icon-circle' => 'fa fa-dribbble fa-stack-1x',
		),

		'dropbox.com' => array(
			'name' => 'Dropbox',
			'class' => 'dropbox',
			'icon' => 'fa fa-dropbox',
			'icon-sign' => 'fa fa-dropbox',
			'icon-square-open' => 'fa fa-dropbox fa-stack-1x',
			'icon-square' => 'fa fa-dropbox fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-dropbox fa-stack-1x',
			'icon-circle-open' => 'fa fa-dropbox fa-stack-1x',
			'icon-circle' => 'fa fa-dropbox fa-stack-1x',
		),

		'facebook.com' => array(
			'name' => 'Facebook',
			'class' => 'facebook',
			'icon' => 'fa fa-facebook',
			'icon-sign' => 'fa fa-facebook-square',
			'icon-square-open' => 'fa fa-facebook fa-stack-1x',
			'icon-square' => 'fa fa-facebook fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-facebook fa-stack-1x',
			'icon-circle-open' => 'fa fa-facebook fa-stack-1x',
			'icon-circle' => 'fa fa-facebook fa-stack-1x',
		),

		'flickr.com' => array(
			'name' => 'Flickr',
			'class' => 'flickr',
			'icon' => 'fa fa-flickr',
			'icon-sign' => 'fa fa-flickr',
			'icon-square-open' => 'fa fa-flickr fa-stack-1x',
			'icon-square' => 'fa fa-flickr fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-flickr fa-stack-1x',
			'icon-circle-open' => 'fa fa-flickr fa-stack-1x',
			'icon-circle' => 'fa fa-flickr fa-stack-1x',
		),

		'foursquare.com' => array(
			'name' => 'Foursquare',
			'class' => 'foursquare',
			'icon' => 'fa fa-foursquare',
			'icon-sign' => 'fa fa-foursquare',
			'icon-square-open' => 'fa fa-foursquare fa-stack-1x',
			'icon-square' => 'fa fa-foursquare fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-foursquare fa-stack-1x',
			'icon-circle-open' => 'fa fa-foursquare fa-stack-1x',
			'icon-circle' => 'fa fa-foursquare fa-stack-1x',
		),

		'github.com' => array(
			'name' => 'Github',
			'class' => 'github',
			'icon' => 'fa fa-github',
			'icon-sign' => 'fa fa-github-square',
			'icon-square-open' => 'fa fa-github fa-stack-1x',
			'icon-square' => 'fa fa-github fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-github fa-stack-1x',
			'icon-circle-open' => 'fa fa-github fa-stack-1x',
			'icon-circle' => 'fa fa-github fa-stack-1x',
		),

		'gratipay.com' => array(
			'name' => 'Gratipay',
			'class' => 'gratipay',
			'icon' => 'fa fa-gratipay',
			'icon-sign' => 'fa fa-gratipay',
			'icon-square-open' => 'fa fa-gratipay fa-stack-1x',
			'icon-square' => 'fa fa-gratipay fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-gratipay fa-stack-1x',
			'icon-circle-open' => 'fa fa-gratipay fa-stack-1x',
			'icon-circle' => 'fa fa-gratipay fa-stack-1x',
		),

		'gittip.com' => array(
			'name' => 'Gittip',
			'class' => 'gratipay',
			'icon' => 'fa fa-gratipay',
			'icon-sign' => 'fa fa-gratipay',
			'icon-square-open' => 'fa fa-gratipay fa-stack-1x',
			'icon-square' => 'fa fa-gratipay fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-gratipay fa-stack-1x',
			'icon-circle-open' => 'fa fa-gratipay fa-stack-1x',
			'icon-circle' => 'fa fa-gratipay fa-stack-1x',
		),

		'instagr.am' => array(
			'name' => 'Instagram',
			'class' => 'instagram',
			'icon' => 'fa fa-instagram',
			'icon-sign' => 'fa fa-instagram',
			'icon-square-open' => 'fa-instagram fa-stack-1x',
			'icon-square' => 'fa-instagram fa-stack-1x',
			'icon-circle-open-thin' => 'fa-instagram fa-stack-1x',
			'icon-circle-open' => 'fa-instagram fa-stack-1x',
			'icon-circle' => 'fa-instagram fa-stack-1x',
		),

		'instagram.com' => array(
			'name' => 'Instagram',
			'class' => 'instagram',
			'icon' => 'fa fa-instagram',
			'icon-sign' => 'fa fa-instagram',
			'icon-square-open' => 'fa-instagram fa-stack-1x',
			'icon-square' => 'fa-instagram fa-stack-1x',
			'icon-circle-open-thin' => 'fa-instagram fa-stack-1x',
			'icon-circle-open' => 'fa-instagram fa-stack-1x',
			'icon-circle' => 'fa-instagram fa-stack-1x',
		),

		'jsfiddle.net' => array(
			'name' => 'JS Fiddle',
			'class' => 'jsfiddle',
			'icon' => 'fa fa-jsfiddle',
			'icon-sign' => 'fa fa-jsfiddle',
			'icon-square-open' => 'fa fa-jsfiddle fa-stack-1x',
			'icon-square' => 'fa fa-jsfiddle fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-jsfiddle fa-stack-1x',
			'icon-circle-open' => 'fa fa-jsfiddle fa-stack-1x',
			'icon-circle' => 'fa fa-jsfiddle fa-stack-1x',
		),

		'linkedin.com' => array(
			'name' => 'LinkedIn',
			'class' => 'linkedin',
			'icon' => 'fa fa-linkedin',
			'icon-sign' => 'fa fa-linkedin-square',
			'icon-square-open' => 'fa fa-linkedin fa-stack-1x',
			'icon-square' => 'fa fa-linkedin fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-linkedin fa-stack-1x',
			'icon-circle-open' => 'fa fa-linkedin fa-stack-1x',
			'icon-circle' => 'fa fa-linkedin fa-stack-1x',
		),

		'mailto:' => array(
			'name' => 'Email',
			'class' => 'envelope',
			'icon' => 'fa fa-envelope',
			'icon-sign' => 'fa fa-envelope-o',
			'icon-square-open' => 'fa fa-envelope fa-stack-1x',
			'icon-square' => 'fa fa-envelope fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-envelope fa-stack-1x',
			'icon-circle-open' => 'fa fa-envelope fa-stack-1x',
			'icon-circle' => 'fa fa-envelope fa-stack-1x',
		),

		'pinterest.com' => array(
			'name' => 'Pinterest',
			'class' => 'pinterest',
			'icon' => 'fa fa-pinterest',
			'icon-sign' => 'fa fa-pinterest-square',
			'icon-square-open' => 'fa fa-pinterest fa-stack-1x',
			'icon-square' => 'fa fa-pinterest fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-pinterest fa-stack-1x',
			'icon-circle-open' => 'fa fa-pinterest fa-stack-1x',
			'icon-circle' => 'fa fa-pinterest fa-stack-1x',
		),

		'plus.google.com' => array(
			'name' => 'Google+',
			'class' => 'google-plus',
			'icon' => 'fa fa-google-plus',
			'icon-sign' => 'fa fa-google-plus-square',
			'icon-square-open' => 'fa fa-google-plus fa-stack-1x',
			'icon-square' => 'fa fa-google-plus fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-google-plus fa-stack-1x',
			'icon-circle-open' => 'fa fa-google-plus fa-stack-1x',
			'icon-circle' => 'fa fa-google-plus fa-stack-1x',
		),

		'renren.com' => array(
			'name' => 'RenRen',
			'class' => 'renren',
			'icon' => 'fa fa-renren',
			'icon-sign' => 'fa fa-renren',
			'icon-square-open' => 'fa fa-renren fa-stack-1x',
			'icon-square' => 'fa fa-renren fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-renren fa-stack-1x',
			'icon-circle-open' => 'fa fa-renren fa-stack-1x',
			'icon-circle' => 'fa fa-renren fa-stack-1x',
		),

		'reddit.com' => array(
			'name' => 'Reddit',
			'class' => 'reddit',
			'icon' => 'fa fa-reddit',
			'icon-sign' => 'fa fa-reddit-square',
			'icon-square-open' => 'fa fa-reddit fa-stack-1x',
			'icon-square' => 'fa fa-reddit fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-reddit fa-stack-1x',
			'icon-circle-open' => 'fa fa-reddit fa-stack-1x',
			'icon-circle' => 'fa fa-reddit fa-stack-1x',
		),

		'snapchat.com' => array(
			'name' => 'Snapchat',
			'class' => 'snapchat',
			'icon' => 'fa fa-snapchat-ghost',
			'icon-sign' => 'fa fa-snapchat-square',
			'icon-square-open' => 'fa fa-snapchat-ghost fa-stack-1x',
			'icon-square' => 'fa fa-snapchat-ghost fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-snapchat-ghost fa-stack-1x',
			'icon-circle-open' => 'fa fa-snapchat-ghost fa-stack-1x',
			'icon-circle' => 'fa fa-snapchat-ghost fa-stack-1x',
		),

		'trello.com' => array(
			'name' => 'Trello',
			'class' => 'trello',
			'icon' => 'fa fa-trello',
			'icon-sign' => 'fa fa-trello',
			'icon-square-open' => 'fa fa-trello fa-stack-1x',
			'icon-square' => 'fa fa-trello fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-trello fa-stack-1x',
			'icon-circle-open' => 'fa fa-trello fa-stack-1x',
			'icon-circle' => 'fa fa-trello fa-stack-1x',
		),

		'tumblr.com' => array(
			'name' => 'Tumblr',
			'class' => 'tumblr',
			'icon' => 'fa fa-tumblr',
			'icon-sign' => 'fa fa-tumblr-square',
			'icon-square-open' => 'fa fa-tumblr fa-stack-1x',
			'icon-square' => 'fa fa-tumblr fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-tumblr fa-stack-1x',
			'icon-circle-open' => 'fa fa-tumblr fa-stack-1x',
			'icon-circle' => 'fa fa-tumblr fa-stack-1x',
		),
		'twitch.tv' => array(
			'name' => 'Twitch',
			'class' => 'twitch',
			'icon' => 'fa fa-twitch',
			'icon-sign' => 'fa fa-twitch',
			'icon-square-open' => 'fa fa-twitch fa-stack-1x',
			'icon-square' => 'fa fa-twitch fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-twitch fa-stack-1x',
			'icon-circle-open' => 'fa fa-twitch fa-stack-1x',
			'icon-circle' => 'fa fa-twitch fa-stack-1x',
		),
		'twitter.com' => array(
			'name' => 'Twitter',
			'class' => 'twitter',
			'icon' => 'fa fa-twitter',
			'icon-sign' => 'fa fa-twitter-square',
			'icon-square-open' => 'fa fa-twitter fa-stack-1x',
			'icon-square' => 'fa fa-twitter fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-twitter fa-stack-1x',
			'icon-circle-open' => 'fa fa-twitter fa-stack-1x',
			'icon-circle' => 'fa fa-twitter fa-stack-1x',
		),

		'weibo.com' => array(
			'name' => 'Weibo',
			'class' => 'weibo',
			'icon' => 'fa fa-weibo',
			'icon-sign' => 'fa fa-weibo',
			'icon-square-open' => 'fa fa-weibo fa-stack-1x',
			'icon-square' => 'fa fa-weibo fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-weibo fa-stack-1x',
			'icon-circle-open' => 'fa fa-weibo fa-stack-1x',
			'icon-circle' => 'fa fa-weibo fa-stack-1x',
		),

		'wordpress.com' => array(
			'name' => 'WordPress.com',
			'class' => 'wpcom',
			'icon' => 'fa fa-wordpress',
			'icon-sign' => 'fa fa-wordpress',
			'icon-square-open' => 'fa fa-wordpress fa-stack-1x',
			'icon-square' => 'fa fa-wordpress fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-wordpress fa-stack-1x',
			'icon-circle-open' => 'fa fa-wordpress fa-stack-1x',
			'icon-circle' => 'fa fa-wordpress fa-stack-1x',
		),

		'wordpress.org' => array(
			'name' => 'WordPress.org',
			'class' => 'wporg',
			'icon' => 'fa fa-wordpress',
			'icon-sign' => 'fa fa-wordpress',
			'icon-square-open' => 'fa fa-wordpress fa-stack-1x',
			'icon-square' => 'fa fa-wordpress fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-wordpress fa-stack-1x',
			'icon-circle-open' => 'fa fa-wordpress fa-stack-1x',
			'icon-circle' => 'fa fa-wordpress fa-stack-1x',
		),

		'xing.com' => array(
			'name' => 'Xing',
			'class' => 'xing',
			'icon' => 'fa fa-xing',
			'icon-sign' => 'fa fa-xing',
			'icon-square-open' => 'fa fa-xing fa-stack-1x',
			'icon-square' => 'fa fa-xing fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-xing fa-stack-1x',
			'icon-circle-open' => 'fa fa-xing fa-stack-1x',
			'icon-circle' => 'fa fa-xing fa-stack-1x',
		),

		'yelp.com' => array(
			'name' => 'Yelp',
			'class' => 'yelp',
			'icon' => 'fa fa-yelp',
			'icon-sign' => 'fa fa-yelp',
			'icon-square-open' => 'fa fa-yelp fa-stack-1x',
			'icon-square' => 'fa fa-yelp fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-yelp fa-stack-1x',
			'icon-circle-open' => 'fa fa-yelp fa-stack-1x',
			'icon-circle' => 'fa fa-yelp fa-stack-1x',
		),

		'youtu.be' => array(
			'name' => 'YouTube',
			'class' => 'youtube',
			'icon' => 'fa fa-youtube',
			'icon-sign' => 'fa fa-youtube-square',
			'icon-square-open' => 'fa fa-youtube fa-stack-1x',
			'icon-square' => 'fa fa-youtube fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-youtube fa-stack-1x',
			'icon-circle-open' => 'fa fa-youtube fa-stack-1x',
			'icon-circle' => 'fa fa-youtube fa-stack-1x',
		),

		'youtube.com' => array(
			'name' => 'YouTube',
			'class' => 'youtube',
			'icon' => 'fa fa-youtube',
			'icon-sign' => 'fa fa-youtube-square',
			'icon-square-open' => 'fa fa-youtube fa-stack-1x',
			'icon-square' => 'fa fa-youtube fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-youtube fa-stack-1x',
			'icon-circle-open' => 'fa fa-youtube fa-stack-1x',
			'icon-circle' => 'fa fa-youtube fa-stack-1x',
		),

		'slideshare.net' => array(
			'name' => 'SlideShare',
			'class' => 'slideshare',
			'icon' => 'fa fa-slideshare',
			'icon-sign' => 'fa fa-slideshare',
			'icon-square-open' => 'fa fa-slideshare fa-stack-1x',
			'icon-square' => 'fa fa-slideshare fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-slideshare fa-stack-1x',
			'icon-circle-open' => 'fa fa-slideshare fa-stack-1x',
			'icon-circle' => 'fa fa-slideshare fa-stack-1x',
		),

		'stackoverflow.com' => array(
			'name' => 'Stack Overflow',
			'class' => 'stack-overflow',
			'icon' => 'fa fa-stack-overflow',
			'icon-sign' => 'fa fa-stack-overflow',
			'icon-square-open' => 'fa fa-stack-overflow fa-stack-1x',
			'icon-square' => 'fa fa-stack-overflow fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-stack-overflow fa-stack-1x',
			'icon-circle-open' => 'fa fa-stack-overflow fa-stack-1x',
			'icon-circle' => 'fa fa-stack-overflow fa-stack-1x',
		),

		'stackexchange.com' => array(
			'name' => 'Stack Exchange',
			'class' => 'stack-exchange',
			'icon' => 'fa fa-stack-exchange',
			'icon-sign' => 'fa fa-stack-exchange',
			'icon-square-open' => 'fa fa-stack-exchange fa-stack-1x',
			'icon-square' => 'fa fa-stack-exchange fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-stack-exchange fa-stack-1x',
			'icon-circle-open' => 'fa fa-stack-exchange fa-stack-1x',
			'icon-circle' => 'fa fa-stack-exchange fa-stack-1x',
		),

		'soundcloud.com' => array(
			'name' => 'SoundCloud',
			'class' => 'soundcloud',
			'icon' => 'fa fa-soundcloud',
			'icon-sign' => 'fa fa-soundcloud',
			'icon-square-open' => 'fa fa-soundcloud fa-stack-1x',
			'icon-square' => 'fa fa-soundcloud fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-soundcloud fa-stack-1x',
			'icon-circle-open' => 'fa fa-soundcloud fa-stack-1x',
			'icon-circle' => 'fa fa-soundcloud fa-stack-1x',
		),

		'steamcommunity.com' => array(
			'name' => 'Steam',
			'class' => 'steam',
			'icon' => 'fa fa-steam',
			'icon-sign' => 'fa fa-steam-square',
			'icon-square-open' => 'fa fa-steam fa-stack-1x',
			'icon-square' => 'fa fa-steam fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-steam fa-stack-1x',
			'icon-circle-open' => 'fa fa-steam fa-stack-1x',
			'icon-circle' => 'fa fa-steam fa-stack-1x',
		),

		'vimeo.com' => array(
			'name' => 'Vimeo',
			'class' => 'vimeo',
			'icon' => 'fa fa-vimeo',
			'icon-sign' => 'fa fa-vimeo-square',
			'icon-square-open' => 'fa fa-vimeo fa-stack-1x',
			'icon-square' => 'fa fa-vimeo fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-vimeo fa-stack-1x',
			'icon-circle-open' => 'fa fa-vimeo fa-stack-1x',
			'icon-circle' => 'fa fa-vimeo fa-stack-1x',
		),

		'vine.co' => array(
			'name' => 'Vine',
			'class' => 'vine',
			'icon' => 'fa fa-vine',
			'icon-sign' => 'fa fa-vine',
			'icon-square-open' => 'fa fa-vine fa-stack-1x',
			'icon-square' => 'fa fa-vine fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-vine fa-stack-1x',
			'icon-circle-open' => 'fa fa-vine fa-stack-1x',
			'icon-circle' => 'fa fa-vine fa-stack-1x',
		),

		'vk.com' => array(
			'name' => 'VK',
			'class' => 'vk',
			'icon' => 'fa fa-vk',
			'icon-sign' => 'fa fa-vk',
			'icon-square-open' => 'fa fa-vk fa-stack-1x',
			'icon-square' => 'fa fa-vk fa-stack-1x',
			'icon-circle-open-thin' => 'fa fa-vk fa-stack-1x',
			'icon-circle-open' => 'fa fa-vk fa-stack-1x',
			'icon-circle' => 'fa fa-vk fa-stack-1x',
		),

	);

	/**
	 * Class to apply to the <li> of all social menu items
	 *
	 * @since 1.0.0
	 * @var string $li_class default class to apply.
	 */
	var $li_class = 'menu-social';

	/**
	 * FontAwesome Icon Size options available for icon output
	 * These are sizes that render as "pixel perfect" according to FontAwesome.
	 *
	 * @since 1.0.0
	 * @var   array $icon_sizes Available icon sizes in font-awesome.
	 */
	var $icon_sizes = array(

		'normal' => '',
		'large'  => 'fa-lg',
		'2x'     => 'fa-2x',
		'3x'     => 'fa-3x',
		'4x'     => 'fa-4x',
		'5x'     => 'fa-5x',

	);

	/**
	 * Size of the icons to display.
	 *
	 * Override with boldgrid_social_icon_size filter.
	 *
	 * @var     string $size normal|large|2x|3x|4x
	 * @since   1.0.0
	 */
	var $size = '2x';

	/**
	 * Display normal icons, or icons cut out of a box (sign) shape?
	 *
	 * Override with boldgrid_social_icon_type filter
	 *
	 * @var     string $type icon|icon-sign
	 * @since   1.0.0
	 */
	var $type = 'icon';

	/**
	 * The BoldGrid Theme Framework configurations.
	 *
	 * Array of configuration options for the BoldGrid Theme Framework.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var  array  $configs  The BoldGrid Theme Framework configurations.
	 */
	protected $configs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 *
	 * @param array $configs The BoldGrid Theme Framework configuration options.
	 */
	public function __construct( $configs ) {
		$this->configs = $configs;
		// Allow the size of icons to be set (using FA sizing options).
		$this->size         = apply_filters( 'boldgrid_social_icon_size',         $this->configs['social-icons']['size'] );
		// Provide access to change if icons are -square or the standard FA open icons.
		$this->type         = apply_filters( 'boldgrid_social_icon_type',         $this->configs['social-icons']['type'] );
		// Set the option to display or hide the text next to icons.
		$this->hide_text    = apply_filters( 'boldgrid_social_icon_hide_text',    $this->configs['social-icons']['hide-text'] );
		// Allow adding new networks or icons via filter.
		$this->networks     = apply_filters( 'boldgrid_social_icon_networks',     $this->networks );
	}

	/**
	 * Get icon HTML with appropriate classes and html markup depending on size and icon type
	 *
	 * @since 1.0.0
	 *
	 * @param string $network type of icon to retrieve and use.
	 */
	public function get_icon( $network ) {

		$icon_sizes = $this->icon_sizes;

		$size = $icon_sizes[ $this->size ];

		$icon = $network[ $this->type ];

		$show_text = $this->hide_text ? '' : 'visible-text';

		if ( 'icon-circle' === $this->configs['social-icons']['type'] ) {

			$html = "<span class='fa-stack $size'>
						<i class='fa fa-circle fa-stack-2x'></i>
  						<i class='fa $icon fa-stack-1x fa-inverse $show_text'></i>
					</span>";

		} elseif ( 'icon-circle-open' === $this->configs['social-icons']['type'] ) {

			$html = "<span class='fa-stack $size'>
						<i class='fa fa-circle-o fa-stack-2x'></i>
							<i class='fa $icon fa-stack-1x $show_text'></i>
					</span>";

		} elseif ( 'icon-circle-open-thin' === $this->configs['social-icons']['type'] ) {

			$html = "<span class='fa-stack $size'>
						<i class='fa fa-circle-thin fa-stack-2x'></i>
							<i class='fa $icon fa-stack-1x $show_text'></i>
					</span>";

		} elseif ( 'icon-square' === $this->configs['social-icons']['type'] ) {

			$html = "<span class='fa-stack $size'>
						<i class='fa fa-square fa-stack-2x'></i>
							<i class='fa $icon fa-stack-1x fa-inverse $show_text'></i>
					</span>";

		} elseif ( 'icon-square-open' === $this->configs['social-icons']['type'] ) {

			$html = "<span class='fa-stack $size'>
						<i class='fa fa-square-o fa-stack-2x'></i>
							<i class='fa $icon fa-stack-1x $show_text'></i>
					</span>";

		} else {

			$html = "<i class='$size $icon $show_text'></i>";

		}

		return apply_filters( 'boldgrid_icon_html', $html, $size, $icon, $show_text );
	}

	/**
	 * Find social links in top-level menu items, then add icon HTML.  Skip over submenu items.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $sorted_menu_items The menu items, sorted by each menu item's menu order.
	 * @param object $args An object containing wp_nav_menu() arguments.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_nav_menu_objects/
	 */
	public function wp_nav_menu_objects( $sorted_menu_items, $args ) {

		foreach ( $sorted_menu_items as &$item ) {

			// Skip submenu items.
			if ( 0 != $item->menu_item_parent ) {
				continue;
			}

			foreach ( $this->networks as $url => $network ) {

				if ( false !== strpos( $item->url, $url ) ) {
					$item->classes[] = $this->li_class;
					$item->classes[] = $network['class'];

					if ( $this->hide_text ) {
						$html = "<span class='sr-only'>{$item->title}</span>";
						$item->title = apply_filters( 'boldgrid_icon_title_html', $html, $item->title );
					}

					$item->title = $this->get_icon( $network ) . $item->title ;
				}
			}
		}

		return $sorted_menu_items;
	}
}
