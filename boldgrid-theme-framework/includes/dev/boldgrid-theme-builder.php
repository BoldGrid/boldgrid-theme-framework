<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Boldgrid_Seo
 * @subpackage Boldgrid_Seo/includes
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

// If this file is called directly, abort.
defined( 'WPINC' ) ? : die;

/** Theme Builder
 --------------------------------------------------------*/
/**
 * Register the form setting for the build.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, boldgrid_builder_validate(),
 * which is used when the option is saved, to ensure that our option values are properly
 * formatted, and safe.
 */
function boldgrid_builder_init(  ) {
    register_setting(
        'boldgrid_builder',         // Options group, see settings_fields() call in boldgrid_builder_render_page()
        'boldgrid_builder_options', // Database option, see boldgrid_builder_get_options()
        'boldgrid_builder_validate' // The sanitization callback, see boldgrid_builder_validate()
    );
    $options = boldgrid_builder_get_options(  );

    // Register our settings field group
    add_settings_section(
        'default', // Unique identifier for the settings section
        __('Name your new BoldGrid theme', BOLDGRID_THEME_NAME), // Section title
        '__return_false', // Section callback (we don't want anything)
        'boldgrid_builder' // Menu slug, used to uniquely identify the page; see boldgrid_builder_add_page()
    );
    add_settings_field( 'boldgrid_builder_name', __( 'Theme name', BOLDGRID_THEME_NAME ), 'boldgrid_builder_field_name', 'boldgrid_builder', 'default');
    add_settings_field( 'boldgrid_builder_slug', __( 'Theme slug', BOLDGRID_THEME_NAME ), 'boldgrid_builder_field_slug', 'boldgrid_builder', 'default' );
}
add_action( 'admin_init', 'boldgrid_builder_init' );

function boldgrid_builder_field_name() {
    $options = boldgrid_builder_get_options();
    $selected = array_key_exists('name', $options) ? $options['name'] : '';
    ?><input  class="all-options" type="text" name="boldgrid_builder_options[name]" id="boldgrid_builder_options_name" value="<?php echo esc_attr( $selected ); ?>" />
    <span class="description"><?php _e( 'Something like "My Awesome BoldGrid Theme" ', BOLDGRID_THEME_NAME ); ?></span>
<?php
}

function boldgrid_builder_field_slug(  ) {

    $options = boldgrid_builder_get_options(  );
    $selected = array_key_exists( 'slug', $options ) ? $options['slug'] : '';
    ?><input class="all-options" type="text" name="boldgrid_builder_options[slug]" id="boldgrid_builder_options_slug" value="<?php echo esc_attr( $selected ); ?>" />
    <span class="description"><?php _e( 'Something like "my-awesome-boldgrid-theme"', BOLDGRID_THEME_NAME ); ?></span>
<?php

}

/**
 * Returns the options array
 */
function boldgrid_builder_get_options(  ) {
    $saved = (array) get_option( 'boldgrid_builder_options' );
    $defaults = boldgrid_builder_default_options(  );
    $defaults = apply_filters( 'boldgrid_builder_default_options', $defaults );

    $options = wp_parse_args( $saved, $defaults );
    $options = array_intersect_key( $options, $defaults );

    return $options;
}

function boldgrid_builder_default_options(  ) {

    $defaults = array(
        'name'  => '',
        'slug'   => '',
    );

    return $defaults;

}


/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see boldgrid_builder_init()
 *
 * @param array $input Unknown values.
 * @return array Sanitized theme options ready to be stored in the database.
 */
function boldgrid_builder_validate( $input ) {

    $output = array(  );
    $error = 0;
    $default_keys = array_keys( boldgrid_builder_default_options(  ) );

    foreach( $default_keys as $key ) {

        $output[$key] = ( isset( $input[$key] )

            && get_post( $input[$key] ) !== FALSE )

        ? trim( $input[$key] ) 

        : '';

    }

    // name
    if ( empty( $output['name'] ) ) {

        add_settings_error(
            'boldgrid_builder_name',
            'boldgrid_builder_name_req',
            __('Name is required', BOLDGRID_THEME_NAME ),
            'error' 
        );

        $error++;

    }

    // slug
    if ( empty( $output['slug'] ) ) {

        $sane_slug = boldgrid_builder_sanitize( $output['name'] );

        $err_msg = sprintf( __('You must specify a valid Theme slug - try: %s', BOLDGRID_THEME_NAME ), $sane_slug );

        add_settings_error(
            'boldgrid_builder_slug',
            'boldgrid_builder_slug_req',
            $err_msg,
            'error'
        );

        $error = true;

    } else {

        $sane_slug = boldgrid_builder_sanitize( $output['slug'] );
        if ( $sane_slug != $output['slug'] ) {

            $err_msg = sprintf( __( '%s is not a valid Theme slug - try: %s instead', BOLDGRID_THEME_NAME ), $output['slug'], $sane_slug );
    
            add_settings_error(
                'boldgrid_builder_slug',
                'boldgrid_builder_slug_regexp',
                $err_msg,
                'error'
            );

            $output['slug'] = '';
            $error++;

        }

    }

    if ( $error > 0 ) {

        return apply_filters( 'boldgrid_builder_validate', $output, $input );

    } else {

        $nodes = boldgrid_builder_theme( $output['name'], $output['slug'] );//        echo '<pre>'; print_r( array_keys($nodes) ); exit;
        $redirect_url = admin_url( '/themes.php?activated=true' );
        switch_theme( $output['slug'] );
        wp_safe_redirect( $redirect_url );
        exit;

    }

}

/**
 * Add our build theme page to the admin menu.
 * This function is attached to the admin_menu action hook.
 */
function boldgrid_builder_add_page(  ) {

    $theme_page = add_theme_page(
        __( 'Build theme', BOLDGRID_THEME_NAME ), // Name of page
        __( 'Build theme', BOLDGRID_THEME_NAME ), // Label in menu
        'manage_options',                         // Capability required
        'boldgrid_builder',                       // Menu slug, used to uniquely identify the page
        'boldgrid_builder_render_page'            // Function that renders the options page
    );

}
add_action( 'admin_menu', 'boldgrid_builder_add_page' );

/**
 * Renders the build theme page screen.
 *
 */
function boldgrid_builder_render_page(  ) {

    ?>
<div class="wrap">
    <h2><?php _e( 'Build theme', BOLDGRID_THEME_NAME ); ?></h2>
    <?php settings_errors(  ); ?>

    <form method="post" action="options.php">
    <?php
        settings_fields( 'boldgrid_builder' );
        do_settings_sections( 'boldgrid_builder' );
        submit_button( __( 'Create New BoldGrid Theme!', BOLDGRID_THEME_NAME ) );
    ?>
    </form>
</div>
<?php

}

/**
 * Builds your awesome _strap-powered theme
 *
 * @param string $name Theme name.
 * @param string $slug Theme slug.
 * @return: array
 */
function boldgrid_builder_theme( $name, $slug ) {

	$root = get_theme_root(  );

	$src = get_stylesheet_directory(  );

	$dst = $root . DIRECTORY_SEPARATOR . $slug;

	$nodes = boldgrid_builder_scan( $name, $slug, $src );

	if ( is_dir( $dst ) || is_file( $dst ) ) {

		// handle error, don't overwrite

	}
	elseif ( ! mkdir( $dst ) ) {

		// handle error, maybe set flag for dynamic zip instead of copying files

	} else {

		$ignored = boldgrid_gitignore( $src . DIRECTORY_SEPARATOR . '.gitignore' );

		foreach( $nodes as $path => $content ) {

			$from = realpath( get_stylesheet_directory(  ) . DIRECTORY_SEPARATOR . $path );

			if ( in_array( $from, $ignored ) ) continue;

			$target = $dst . DIRECTORY_SEPARATOR . $path;

			if ( empty( $content ) ) { // it's a dir

				mkdir( $target ); // don't check, after all you just created the parent dir

			} else {

				file_put_contents( $target, $content ); // don't check, after all you just created the parent dir

			}

		}

	}

	return $nodes;

}

/**
 * Scans all files in theme
 *
 * @param string $name Theme name.
 * @param string $slug Theme slug.
 * @return: array $nodes all files in theme
 */
function boldgrid_builder_scan( $name, $slug, $dir = false ) {

    $base = get_stylesheet_directory(  );
    $dir = $dir ? $dir : $base;
    $nodes = array(  );

    foreach( scandir( $dir ) as $item ) {

        if ( substr( $item, 0, 1 ) == '.' ) continue;

        $content = null;

        $path = $dir . DIRECTORY_SEPARATOR . $item;

        $node = substr( $path, strlen( $base ) + 1 );

        if ( is_file( $path ) ) $content = boldgrid_builder_replace( $name, $slug, $path );

        $nodes[$node] = boldgrid_builder_replace( $name, $slug, $path );

        if ( is_dir( $path ) ) $nodes = array_merge( $nodes, boldgrid_builder_scan( $name, $slug, $path ) );
    }

    return $nodes;
}

/**
 * Performs replacements on theme files
 *
 * @param string $name Theme name.
 * @param string $slug Theme slug.
 * @return: array
 */
function boldgrid_builder_replace( $name, $slug, $path ) {

    if ( is_dir( $path ) ) return null;

    $search = array( "'_s'", '_s_', ' _s' );

    $replace = array( "'$slug'", $slug . '_', " $name" );

    $content = file_get_contents( $path ); // binary-safe

    $pathinfo = pathinfo( $path );

    if ( $pathinfo['extension'] == 'php' ) {

        if ( $pathinfo['filename'] == 'boldgrid-theme-builder' ) {

            $end = strpos( $content, '/** Theme Builder' );

            $content = substr( $content, 0, $end );

        }

        $content = str_replace( $search, $replace, $content );

    }

    elseif ( $pathinfo['filename'] == 'style' ) {

        $start = strpos( $content, '*/' );

        $content = boldgrid_builder_header( $name ) . substr( $content, $start + 2 );

    }
    
    return $content;

}

/**
 * Performs replacements on theme files
 *
 * @param string $name Theme name.
 * @param string $slug Theme slug.
 * @return: array
 */
function boldgrid_builder_sanitize( $name ) {
    $sane_slug = sanitize_title( $name );
    $sane_slug = str_replace( '-', '_', $sane_slug );
    return $sane_slug;
}

/**
 * Default theme header (for style.css)
 */
function boldgrid_builder_header( $name ) {
    return <<<END
/*
Theme Name: $name
Theme URI: https://boldgrid.com
Author: 
Author URI: https://boldgrid.com
Description: This is a theme built with BoldGrid.
Version: 0.1
License: GNU General Public License
License URI: license.txt
Tags: BoldGrid, bootstrap, _s

Based on BoldGrid [http://wwww.boldgrid.com], _s [https://github.com/Automattic/_s], and Bootstrap [https://github.com/twitter/bootstrap]
*/
END;
}

/**
 * Recursively delete directory
 */
function boldgrid_rmdir_tree( $dir ) {

	$files = array_diff( scandir( $dir ), array( '.','..' ) );

	foreach ( $files as $file ) {

		( is_dir( "$dir/$file" ) 
            && ! is_link( $dir ) )

        ? boldgrid_rmdir_tree( "$dir/$file" )

        : unlink( "$dir/$file" );

	}

	return rmdir( $dir );

}

/**
 * Find .gitignored files
 */
function boldgrid_gitignore( $file ) { # $file = '/absolute/path/to/.gitignore'
	$matches = array(  );

	if( is_file( $file ) ) {

		$dir = dirname( $file );

		$lines = file( $file );

		foreach ( $lines as $line ) {

			$line = trim( $line );

			if ( $line === '' ) continue;                 # empty line

			if ( substr( $line, 0, 1 ) == '#' ) continue;   # a comment

			if ( substr( $line, 0, 1 ) == '!' ) {           # negated glob

				$line = substr( $line, 1 );
				$files = array_diff( glob( $dir . DIRECTORY_SEPARATOR .  '*' ), glob( $dir . DIRECTORY_SEPARATOR .  $line ) );

			} else {                                       # normal glob

				$files = glob( $dir . DIRECTORY_SEPARATOR .  $line );

			}

			$matches = array_merge( $matches, $files );

		}

		foreach( $matches as $i => $match ) $matches[$i] = realpath( $match );

	}

	return $matches;

}
