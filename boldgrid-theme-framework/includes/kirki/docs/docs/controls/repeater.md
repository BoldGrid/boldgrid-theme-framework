---
layout: default
title: The "repeater" control
slug: repeater
subtitle: Learn how to create controls using Kirki
mainMaxWidth: 50rem;
bodyClasses: control page
returns: array
heroButtons:
  - url: ../controls
    class: white button round border-only
    icon: fa fa-arrow-circle-o-left
    label: Back to Controls
---

Repeater controls allow you to build repeatable blocks of fields.
You can create for example a set of fields that will contain a checkbox and a textfield. The user will then be able to add "rows", and each row will contain a checkbox and a textfield.

### Example


Creating a repeater control where each row contains 2 textfields:

```php
Kirki::add_field( 'theme_config_id', array(
	'type'        => 'repeater',
	'label'       => esc_attr__( 'Repeater Control', 'textdomain' ),
	'section'     => 'section_id',
	'priority'    => 10,
	'row_label' => array(
		'type' => 'text',
		'value' => esc_attr__('your custom value', 'textdomain' ),
	),
	'button_label' => esc_attr__('"Add new" button label (optional) ', 'textdomain' ),
	'settings'     => 'my_setting',
	'default'      => array(
		array(
			'link_text' => esc_attr__( 'Kirki Site', 'textdomain' ),
			'link_url'  => 'https://aristath.github.io/kirki/',
		),
		array(
			'link_text' => esc_attr__( 'Kirki Repository', 'textdomain' ),
			'link_url'  => 'https://github.com/aristath/kirki',
		),
	),
	'fields' => array(
		'link_text' => array(
			'type'        => 'text',
			'label'       => esc_attr__( 'Link Text', 'textdomain' ),
			'description' => esc_attr__( 'This will be the label for your link', 'textdomain' ),
			'default'     => '',
		),
		'link_url' => array(
			'type'        => 'text',
			'label'       => esc_attr__( 'Link URL', 'textdomain' ),
			'description' => esc_attr__( 'This will be the link URL', 'textdomain' ),
			'default'     => '',
		),
	)
) );
```

Creating a repeater control where the label has a dynamic name based on a field's input.  This will use `['row_label']['value']` if nothing is returned from the specified field:

```php
Kirki::add_field( 'theme_config_id', array(
	'type'        => 'repeater',
	'label'       => esc_attr__( 'Repeater Control', 'textdomain' ),
	'section'     => 'section_id',
	'priority'    => 10,
	'row_label' => array(
		'type'  => 'field',
		'value' => esc_attr__('your custom value', 'textdomain' ),
		'field' => 'link_text',
	),
	'button_label' => esc_attr__('"Add new" button label (optional) ', 'textdomain' ),
	'settings'     => 'my_setting',
	'default'      => array(
		array(
			'link_text' => esc_attr__( 'Kirki Site', 'textdomain' ),
			'link_url'  => 'https://aristath.github.io/kirki/',
		),
		array(
			'link_text' => esc_attr__( 'Kirki Repository', 'textdomain' ),
			'link_url'  => 'https://github.com/aristath/kirki',
		),
	),
	'fields' => array(
		'link_text' => array(
			'type'        => 'text',
			'label'       => esc_attr__( 'Link Text', 'textdomain' ),
			'description' => esc_attr__( 'This will be the label for your link', 'textdomain' ),
			'default'     => '',
		),
		'link_url' => array(
			'type'        => 'text',
			'label'       => esc_attr__( 'Link URL', 'textdomain' ),
			'description' => esc_attr__( 'This will be the link URL', 'textdomain' ),
			'default'     => '',
		),
	)
) );
```

### Usage

```php
<?php
// Default values for 'my_setting' theme mod.
$defaults = array(
    array(
        'link_text' => esc_attr__( 'Kirki Site', 'textdomain' ),
		'link_url'  => 'https://aristath.github.io/kirki/',
	),
	array(
		'link_text' => esc_attr__( 'Kirki Repository', 'textdomain' ),
		'link_url'  => 'https://github.com/aristath/kirki',
	),
);

// Theme_mod settings to check.
$settings = get_theme_mod( 'my_setting', $defaults ); ?>

<div class="kirki-links">
    <?php foreach( $settings as $setting ) : ?>
        <a href="<?php $setting['link_url']; ?>">
            <?php $setting['link_text']; ?>
        </a>
    <?php endforeach; ?>
</div>
```
