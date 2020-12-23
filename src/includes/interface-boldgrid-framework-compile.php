<?php
/**
 * Interface: Boldgrid_Framework_Compile
 *
 * Functions for interfacing with ScssPhp\ScssPhp\Compiler
 *
 * @since      1.2.3
 * @package    Boldgrid_Framework
 * @author     BoldGrid <support@boldgrid.com>
 * @link       https://boldgrid.com
 */

/**
 * Interface: Boldgrid_Framework_Compile
 *
 * @since      1.2.3
 */
interface Boldgrid_Framework_Compile {
	/**
	 * Calls compile and saves output.
	 */
	public function build();
	/**
	 * Compiles the uncompiled code.
	 */
	public function compile( $path, $content, $variables );
}
