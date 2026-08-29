<?php
/**
 * Uninstall cleanup.
 *
 * @package Soderlind\KjeksScripting
 */

declare(strict_types=1);

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'kjeks_scripting' );
delete_site_option( 'kjeks_scripting' );
