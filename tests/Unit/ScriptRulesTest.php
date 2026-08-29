<?php
/**
 * Tests for ScriptRules configuration normalisation.
 *
 * @package Soderlind\KjeksScripting
 */

declare(strict_types=1);

use Soderlind\KjeksScripting\ScriptRules;

it( 'defaults to no gated handles', function (): void {
	expect( ScriptRules::defaults() )->toBe( array( 'handles' => array() ) );
} );

it( 'zips parallel form arrays into a handle map', function (): void {
	$config = ScriptRules::normalize(
		array(
			'handle'   => array( 'analytics-js', 'ads-js' ),
			'category' => array( 'analytics', 'marketing' ),
		)
	);

	expect( $config['handles'] )->toBe(
		array(
			'ads-js'       => 'marketing',
			'analytics-js' => 'analytics',
		)
	);
} );

it( 'reads a stored handle map back', function (): void {
	$config = ScriptRules::normalize(
		array( 'handles' => array( ' hotjar ' => 'analytics' ) )
	);

	expect( $config['handles'] )->toBe( array( 'hotjar' => 'analytics' ) );
} );

it( 'drops rows with an empty handle', function (): void {
	$config = ScriptRules::normalize(
		array(
			'handle'   => array( 'keep', '', '   ' ),
			'category' => array( 'marketing', 'marketing', 'analytics' ),
		)
	);

	expect( $config['handles'] )->toBe( array( 'keep' => 'marketing' ) );
} );

it( 'falls back to marketing for an invalid category', function (): void {
	$config = ScriptRules::normalize(
		array(
			'handle'   => array( 'widget' ),
			'category' => array( 'bogus' ),
		)
	);

	expect( $config['handles'] )->toBe( array( 'widget' => 'marketing' ) );
} );

it( 'strips illegal characters from handles', function (): void {
	expect( ScriptRules::clean_handle( 'my handle!@#' ) )->toBe( 'myhandle' )
		->and( ScriptRules::clean_handle( 'gtag.js-v2_1' ) )->toBe( 'gtag.js-v2_1' );
} );

it( 'exposes the handle map from a resolved config', function (): void {
	expect( ScriptRules::handles( array( 'handles' => array( 'a' => 'analytics' ) ) ) )
		->toBe( array( 'a' => 'analytics' ) )
		->and( ScriptRules::handles( array() ) )->toBe( array() );
} );

it( 'returns an empty config when nothing is submitted', function (): void {
	expect( ScriptRules::normalize( array() ) )->toBe( array( 'handles' => array() ) );
} );
