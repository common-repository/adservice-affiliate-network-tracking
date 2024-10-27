<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.adservice.com
 * @since             1.0.0
 * @package           Astrk
 *
 * @wordpress-plugin
 * Plugin Name:       Adservice Affiliate Network Tracking
 * Description:       Adservice is the leading nordic affiliate network. The Adservice tracking plugin makes it easy to setup our tracking API on your Wordpress site. It also works great with Woocommerce for easy integration into your shop.
 * Version:           2.0.1
 * Author:            Adservice
 * Author URI:        https://www.adservice.com/en/advertiser/#reach-new-customers?utm_source=Wordpress&utm_medium=Partnership
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       astrk
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ASTRK_VERSION', '2.0.1' );
define( 'ASTRK_DIRECTORY', 'adservice-affiliate-network-tracking/');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-astrk-activator.php
 */
function activate_astrk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-astrk-activator.php';
	Astrk_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-astrk-deactivator.php
 */
function deactivate_astrk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-astrk-deactivator.php';
	Astrk_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_astrk' );
register_deactivation_hook( __FILE__, 'deactivate_astrk' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-astrk.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_astrk() {

	$plugin = new Astrk();
	$plugin->run();

}
run_astrk();


if ( ! function_exists('astrk_options_menu') ) {
    add_action( 'admin_menu', 'astrk_options_menu' );
    function astrk_options_menu() {

        $icon_base64 = 'PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMTYiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDQyMS42NSAzMDAiPjxkZWZzPjxzdHlsZT4uY2xzLTF7ZmlsbDojZmZmO308L3N0eWxlPjwvZGVmcz48dGl0bGU+QXNzZXQgNzwvdGl0bGU+PGcgaWQ9IkxheWVyXzIiIGRhdGEtbmFtZT0iTGF5ZXIgMiI+PGcgaWQ9IkxheWVyXzEtMiIgZGF0YS1uYW1lPSJMYXllciAxIj48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0yOTEsMTI5LjcyYTM4LjM3LDM4LjM3LDAsMCwwLTI4LjUyLDEyLjE0LDQwLjA3LDQwLjA3LDAsMCwwLTExLjgzLDI5cTAsMTcuNTgsMTEuODksMjkuMjh0MzAsMTEuN2EzNS42OSwzNS42OSwwLDAsMCwyNy0xMS43NnExMS4wNi0xMS43NywxMS4wNi0yOC43MSwwLTE3LjgzLTExLjMyLTI5LjcyVDI5MSwxMjkuNzJaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNDIxLjY1LDE0OS45NWMwLS4wOCwwLS40NywwLTEuMTQsMC0xLDAtMi41MywwLTQuNTZxMC0xMC41OS0uNTMtMjEuMzNjMC0xLS4wNy0xLjkyLS4xMS0yLjktLjkyLTIzLTMuMDgtNTQtOC4xMi03My4xN0E1My4yNSw1My4yNSwwLDAsMCwzNzUuNTYsOWExMDEuNDcsMTAxLjQ3LDAsMCwwLTEwLjg4LTIuMTdDMzYyLjMyLDYuNCwzNTIuNzMsNS4yMSwzNTAuMjksNWMtNDkuOS01LTEzOS40Ny01LTEzOS40Ny01aDBjLS41NiwwLTg5LjU5LDAtMTM5LjM2LDVDNjksNS4yLDU5LjI0LDYuNCw1Ni44OCw2LjhBMTAxLDEwMSwwLDAsMCw0Ni4wOSw5LDUzLjI1LDUzLjI1LDAsMCwwLDguODEsNDYuODVjLTUsMTktNy4xNiw0OS42My04LjEsNzIuNjEsMCwxLjA1LS4wOCwyLjA4LS4xMiwzLjFRLjA5LDEzMy4zNiwwLDE0NGMwLDIuMTIsMCwzLjc1LDAsNC43NSwwLC43NywwLDEuMjIsMCwxLjIySDBzMCwuNDQsMCwxLjE2YzAsMSwwLDIuNjUsMCw0LjhxMCwxMC42OS41NSwyMS41MmMwLDEsLjA4LDIuMTEuMTIsMy4xOS45NCwyMywzLjEsNTMuNTMsOC4wOSw3Mi40NkE1My4yNSw1My4yNSwwLDAsMCw0Ni4wOSwyOTFhOTguMzIsOTguMzIsMCwwLDAsMTAuMjIsMi4wNmMyLjU2LjQ0LDE0LjE5LDEuODYsMTcuNDQsMi4xMUMxMjMuOSwzMDAsMjEwLjI2LDMwMCwyMTAuODEsMzAwaDBzODYuOTEsMCwxMzcuMjEtNC43OWMzLjE5LS4yNCwxNC43LTEuNjUsMTcuMjEtMi4wOEE5OC43Nyw5OC43NywwLDAsMCwzNzUuNTYsMjkxYTUzLjI1LDUzLjI1LDAsMCwwLDM3LjI4LTM3Ljg5YzUtMTkuMDksNy4xOS01MCw4LjExLTczLDAtMSwuMDgtMiwuMTEtMi45M3EuNS0xMC43OC41NC0yMS40MmMwLTIuMDcsMC0zLjY2LDAtNC42MiwwLS42LDAtMSwwLTEuMDZzMCwwLDAtLjA3UzQyMS42NSwxNTAsNDIxLjY1LDE0OS45NVpNMTc5LjQzLDI0MmwtMTUuNjUtNDAuMzRIOTUuNDhMODAuNTUsMjQySDQzLjI0bDcyLjEtMTg1LjkxaDMxLjEyTDIxOC4zMSwyNDJabTE1My4zMSwwTDMzMiwyMjguODhhNzAuMDksNzAuMDksMCwwLDEtMjAuMjQsMTEuMzIsNjksNjksMCwwLDEtMjMsMy43Myw3Mi45MSw3Mi45MSwwLDAsMS0yNC43OS00LjE3LDY1LjQ3LDY1LjQ3LDAsMCwxLTIwLjg2LTEyLjE0LDc0LjA5LDc0LjA5LDAsMCwxLTE5LjQyLTI1Ljc0LDc1LjI4LDc1LjI4LDAsMCwxLTYuNzctMzEuNTYsNzMuNCw3My40LDAsMCwxLDIzLjkxLTU0LjEzLDY1LDY1LDAsMCwxLDIxLjc1LTEzLjY2LDcyLjg2LDcyLjg2LDAsMCwxLDI2LjE4LTQuNjgsNzQuNzIsNzQuNzIsMCwwLDEsMjMuNzgsMy40OEE2MS41Myw2MS41MywwLDAsMSwzMzIsMTEyLjI3VjU2LjEyaDMxLjg3TDM2NC42MSwyNDJaIi8+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjEwNy44OCAxNjcuNTQgMTUxLjg5IDE2Ny41NCAxMzAuMTQgMTA4LjIyIDEwNy44OCAxNjcuNTQiLz48L2c+PC9nPjwvc3ZnPg==';
        //The icon in the data URI scheme
        $icon_data_uri = 'data:image/svg+xml;base64,' . $icon_base64;


        add_menu_page( 'Adservice', 'Adservice', 'manage_options', ASTRK_DIRECTORY.'/index.php', '', $icon_data_uri);
    }
}

$options = get_option("astrk_options");
if (!isset($options)) {
    $options['uid'] = 0;
    $options['token'] = 0;
    
    add_option("astrk_options", $astrk_options);
}
