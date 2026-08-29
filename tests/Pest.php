<?php
/**
 * Pest bootstrap: Brain Monkey lifecycle and shared WordPress stubs.
 *
 * The AddonKit base classes live in the core Kjeks plugin; register its
 * autoloader (a sibling checkout) so `Soderlind\Kjeks\*` resolves in tests.
 *
 * @package Soderlind\KjeksScripting
 */

declare(strict_types=1);

use Brain\Monkey;
use Brain\Monkey\Functions;

// Map `Soderlind\Kjeks\*` to the sibling core plugin's src/ directory. We register
// a targeted PSR-4 loader rather than requiring core's Composer autoloader, whose
// `files` bootstrap (functions.php) calls `exit` when ABSPATH is undefined (CLI).
spl_autoload_register(
	static function ( string $class ): void {
		$prefix = 'Soderlind\\Kjeks\\';
		if ( ! str_starts_with( $class, $prefix ) ) {
			return;
		}
		$relative = str_replace( '\\', '/', substr( $class, strlen( $prefix ) ) );
		$file     = dirname( __DIR__, 2 ) . '/kjeks/src/' . $relative . '.php';
		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
);

uses()
	->beforeEach(
		function (): void {
			Monkey\setUp();
			Functions\when( '__' )->returnArg( 1 );
			Functions\when( 'apply_filters' )->returnArg( 2 );
		}
	)
	->afterEach(
		function (): void {
			Monkey\tearDown();
		}
	)
	->in( 'Unit' );
