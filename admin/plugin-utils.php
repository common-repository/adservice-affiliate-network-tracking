<?php

class Utils_Plugins {

	/**
	 * Check if a plugin is active
	 *
	 * @param string $plugin_main_file main file of the plugin, eg. woocommerce.php
	 * @return bool True if plugin is active, false otherwise.
	 */
	public static function is_active( $plugin_main_file ) {
		// get the list of plugins.
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		// escape characters that have special meaning in regex.
		$plugin_main_file    = preg_quote( $plugin_main_file, '/' );
		$is_plugin_installed = false;

		// Loop through the active plugins.
		foreach ( $active_plugins as $plugin ) {
			if ( preg_match( '/.+\/' . $plugin_main_file . '/', $plugin ) ) {
				$is_plugin_installed = true;
				break;
			}
		}

		return $is_plugin_installed;
	}

	/**
	 * Check if a plugin is network active
	 *
	 * @param string $plugin_main_file main file of the plugin, eg. woocommerce.php
	 * @return bool True if plugin is active, false otherwise.
	 */
	public static function is_network_active( $plugin_main_file ) {

		// if not a multisite, don't check.
		if ( ! is_multisite() ) {
			return false;
		}

		// get the list of plugins.
		$active_plugins = get_site_option( 'active_sitewide_plugins' );

		// escape characters that have special meaning in regex.
		$plugin_main_file = preg_quote( $plugin_main_file, '/' );
		$is_plugin_active = false;

		// Loop through the active plugins.
		foreach ( $active_plugins as $plugin_name => $plugin_activation ) {
			if ( preg_match( '/.+\/' . $plugin_main_file . '/', $plugin_name ) ) {
				$is_plugin_active = true;
				break;
			}
		}

		return $is_plugin_active;
	}

	/**
	 * Check if a must use (mu) plugin exists.
	 *
	 * mu plugins are always active. So there's no need to check if they are
	 * active or not. We just need to check that they are in the list.
	 *
	 * @param string $plugin_main_file main file of the plugin, eg. woocommerce.php
	 * @return bool True if plugin matches, false otherwise.
	 */
	public static function is_mu_active( $plugin_main_file ) {
		$_mu_plugins = get_mu_plugins();

		if ( isset( $_mu_plugins[ $plugin_main_file ] ) ) {
			return true;
		}

		return false;
	}

}