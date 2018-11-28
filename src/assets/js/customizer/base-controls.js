/* eslint-disable */
import WidgetSectionUpdate from './widget/section-update';
import bgtfwWidgetsSection from './controls/bgtfw-widgets-section';
import BlogPagePanelExpand from './design/blog/blog-page/panel-expand.js';
import BlogPostsPanelExpand from './design/blog/posts/panel-expand.js';
import HomepageSectionExpand from './design/homepage/section-expand.js';
import { Control as GenericControls } from './generic/control.js';
import { Required } from './required.js';
import SectionExtendTitle from './menus/extend-title';
import HamburgerControlToggle from './menus/hamburger-control-toggle';
import HoverBackgroundToggle from './menus/hover-background-toggle';
import { Locations as MenuLocations } from './menus/locations';
import { Devices } from './devices';
import bgtfwSortableAccordion from './controls/bgtfw-sortable-accordion.js';
import bgtfwMenuHamburgers from './controls/bgtfw-menu-hamburgers.js';
import bgtfwTypography from './controls/kirki-typography.js';
import bgtfwHeaderTabs from './controls/bgtfw-header-tabs.js';
import bgtfwMenuLocations from './controls/bgtfw-menu-locations.js';
import bgtfwPaneReflow from './controls/bgtfw-pane-reflow';
import bgtfwPanel from './controls/bgtfw-panel';
import bgtfwSection from './controls/bgtfw-section';

let devices = new Devices();
devices.init();

( function( $ ) {
	const api = wp.customize;
	new Required().init();
	new BlogPagePanelExpand();
	new BlogPostsPanelExpand();
	new HomepageSectionExpand();
	new SectionExtendTitle();
	new GenericControls().init();
	new HamburgerControlToggle();
	new HoverBackgroundToggle();
	new MenuLocations();
	bgtfwHeaderTabs.init();

	api( 'bgtfw_header_layout', 'bgtfw_sticky_header_layout' ,'bgtfw_footer_layout', WidgetSectionUpdate );
	wp.customize.bind( 'pane-contents-reflowed', bgtfwPaneReflow );
	wp.customize.Panel = api.Panel.extend( bgtfwPanel );
	wp.customize.controlConstructor['kirki-typography'] = api.controlConstructor['kirki-typography'].extend( bgtfwTypography );
	wp.customize.controlConstructor['bgtfw-menu-hamburgers'] = api.Control.extend( bgtfwMenuHamburgers );
	wp.customize.controlConstructor['bgtfw-sortable-accordion'] = api.Control.extend( bgtfwSortableAccordion );
	wp.customize.controlConstructor.nav_menu_location = api.controlConstructor['nav_menu_location'].extend( bgtfwMenuLocations );
	wp.customize.Section = api.Section.extend( bgtfwSection );
	wp.customize.sectionConstructor['bgtfw-widgets-section'] = api.Section.extend( bgtfwWidgetsSection );
} )( jQuery );
