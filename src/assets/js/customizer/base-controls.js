/* eslint-disable */
import WidgetSectionUpdate from './widget/section-update';
import bgtfwWidgetsSection from './controls/bgtfw-widgets-section';
import HomepageSectionExpand from './design/homepage/section-expand.js';
import WoocommerceSectionExpand from './design/woocommerce/section-expand.js'
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
import bgtfwNotifications from './controls/bgtfw-notifications';
import bgtfwBackgroundControls from './controls/bgtfw-background-control';
import bgtfwEditPane from './controls/edit/pane';
import { HeaderBackground as BgtfwHeaderBackground } from './controls/bgtfw-header-background';
import bgtfwDropdownMenu from './controls/bgtfw-dropdown-menu';
import bgtfwResponsiveFontSize from './controls/bgtfw-responsive-font-size';

let devices = new Devices();
devices.init();
bgtfwNotifications();
bgtfwBackgroundControls();
bgtfwEditPane();
WidgetSectionUpdate();
bgtfwDropdownMenu();
bgtfwResponsiveFontSize();

( function( $ ) {
	const api = wp.customize;
	new Required().init();
	new HomepageSectionExpand();
	new WoocommerceSectionExpand();
	new SectionExtendTitle();
	new GenericControls().init();
	new HamburgerControlToggle();
	new HoverBackgroundToggle();
	new MenuLocations();
	new BgtfwHeaderBackground();
	bgtfwHeaderTabs.init();

	wp.customize.bind( 'pane-contents-reflowed', bgtfwPaneReflow );
	wp.customize.Panel = api.Panel.extend( bgtfwPanel );
	wp.customize.controlConstructor['kirki-typography'] = api.controlConstructor['kirki-typography'].extend( bgtfwTypography );
	wp.customize.controlConstructor['bgtfw-menu-hamburgers'] = api.Control.extend( bgtfwMenuHamburgers );
	wp.customize.controlConstructor['bgtfw-sortable-accordion'] = api.Control.extend( bgtfwSortableAccordion );
	wp.customize.controlConstructor.nav_menu_location = api.controlConstructor['nav_menu_location'].extend( bgtfwMenuLocations );
	wp.customize.Section = api.Section.extend( bgtfwSection );
	wp.customize.sectionConstructor['bgtfw-widgets-section'] = api.Section.extend( bgtfwWidgetsSection );
	wp.customize.sectionConstructor['bgtfw-upsell'] = api.Section.extend( bgtfwWidgetsSection );
} )( jQuery );
