<?php
/**
 * Interface: Boldgrid_Framework_Compile
 *
 * Functions for interfacing with Leafo\ScssPhp\Compiler
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
	 * Calls compile and save processes.
	 */
	public function build();
	/**
	 * Compiles the SCSS.
	 */
	public function compile();
}
