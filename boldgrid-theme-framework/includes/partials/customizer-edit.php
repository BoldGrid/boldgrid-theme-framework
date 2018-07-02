<?php
/**
 * The template used for the jQuery UI Dialog Boxes.
 *
 * @package Boldgrid_Theme_Framework
 */

// Translatable strings for the jQuery UI dialog boxes.
$edit_page_header   = __( 'Edit this page', 'bgtfw' );
$edit_page_content  = __( "This section is part of an individual page of your site. The Customizer (where you are now) is for editing the header and footer or an overall setting of your site like colors and fonts. To edit this section, you'll need to leave the Customizer and go to the Page Editor.", 'bgtfw' );
?>
<div id='entry-content' title='<?php echo esc_attr( $edit_page_header ) ?>' class='dialog-hidden'>
	<?php echo esc_html( $edit_page_content ); ?>
</div>
<div id='target-highlight'></div>
