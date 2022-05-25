<?php
declare(strict_types=1);
/**
 * Coil Swag loader file.
 */

namespace CoilSwag;

/**
 * @var string Plugin root folder.
 */
const COILSWAG__FILE__ = __DIR__;

/**
 * Initialise and set up the plugin.
 *
 * @return void
 */
function init_plugin() : void {

	// Admin screens and settings.
	add_action( 'admin_menu', __NAMESPACE__ . '\Settings\register_admin_menu' );
	add_action( 'admin_init', __NAMESPACE__ . '\Settings\register_admin_content_settings' );
}

/**
 * Deactivate the plugin.
 */
function coil_deactive_self() {

	deactivate_plugins( plugin_basename( __FILE__ ) );
}
