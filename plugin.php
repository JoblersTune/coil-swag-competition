<?php
/*
Plugin Name: Swag Competition
Description: Plugin to determine if submitted links point to valid web monetized sites, and select winners.
Author: Sarah Jones
Version: 1.0
Author URI: https://github.com/SarahCoilAccount
*/

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/includes/settings/functions.php';
require_once __DIR__ . '/includes/settings/rendering.php';
require_once __DIR__ . '/includes/functions.php';

add_action( 'plugins_loaded', 'CoilSwag\init_plugin' );


