<?php
	// Translatable strings for the jQuery UI dialog boxes.
	$edit_page_header   = __( 'Edit this page', 'bgtfw' );
	$edit_page_content  = __( "This section is part of an individual page of your site. The Customizer (where you are now) is for editing the header and footer or an overall setting of your site like colors and fonts. To edit this section, you'll need to leave the Customizer and go to the Page Editor.", 'bgtfw' );
	$edit_title_header  = __( 'Hide page title', 'bgtfw' );
	$edit_title_content = __( "To hide the title on this page, you'll need to leave the Customizer and go to the Page Editor.", 'bgtfw' );
?>
<div id='entry-content' title='<?php echo $edit_page_header ?>' class='dialog-hidden'>
	<?php echo $edit_page_content; ?>
</div>
<div id='entry-title' title='<?php echo $edit_title_header; ?>' class='dialog-hidden'>
	<?php echo $edit_title_content; ?><br />
	<img src='<?php echo $this->configs['framework']['admin_asset_dir']; ?>img/hide-page-title.png' />
</div>
<div id='target-highlight'></div>