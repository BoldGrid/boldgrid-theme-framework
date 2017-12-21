---
layout: default
title: The "select" control
slug: select
subtitle: Learn how to create controls using Kirki
mainMaxWidth: 50rem;
bodyClasses: control page
returns: string|int
heroButtons:
  - url: ../controls
    class: white button round border-only
    icon: fa fa-arrow-circle-o-left
    label: Back to Controls
---

### Example

```php
Kirki::add_field( 'theme_config_id', array(
	'type'        => 'select',
	'settings'    => 'my_setting',
	'label'       => __( 'This is the label', 'textdomain' ),
	'section'     => 'section_id',
	'default'     => 'option-1',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'option-1' => esc_attr__( 'Option 1', 'textdomain' ),
		'option-2' => esc_attr__( 'Option 2', 'textdomain' ),
		'option-3' => esc_attr__( 'Option 3', 'textdomain' ),
		'option-4' => esc_attr__( 'Option 4', 'textdomain' ),
	),
) );
```
