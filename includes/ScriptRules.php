<?php
/**
 * Pure configuration rules for gated script handles.
 *
 * @package Soderlind\KjeksScripting
 */

declare(strict_types=1);

namespace Soderlind\KjeksScripting;

use Soderlind\Kjeks\AddonKit\Categories;

/**
 * Normalises the stored/submitted configuration into a handle => category map.
 *
 * Kept free of WordPress state so it can be unit-tested directly.
 */
final class ScriptRules {

	/**
	 * Default configuration: no handles gated.
	 *
	 * @return array{handles: array<string, string>}
	 */
	public static function defaults(): array {
		return array( 'handles' => array() );
	}

	/**
	 * Normalises raw values into the config shape.
	 *
	 * Accepts either the stored shape (`['handles' => [handle => category]]`)
	 * or the submitted form shape (parallel `handle[]` / `category[]` arrays).
	 *
	 * @param array<string, mixed> $raw Raw values.
	 * @return array{handles: array<string, string>}
	 */
	public static function normalize( array $raw ): array {
		$map = array();

		if ( isset( $raw['handle'] ) && is_array( $raw['handle'] ) ) {
			$handles    = array_values( $raw['handle'] );
			$categories = ( isset( $raw['category'] ) && is_array( $raw['category'] ) )
				? array_values( $raw['category'] )
				: array();

			foreach ( $handles as $index => $handle ) {
				$clean = self::clean_handle( (string) $handle );
				if ( '' === $clean ) {
					continue;
				}
				$map[ $clean ] = Categories::coerce( (string) ( $categories[ $index ] ?? '' ) );
			}
		} elseif ( isset( $raw['handles'] ) && is_array( $raw['handles'] ) ) {
			foreach ( $raw['handles'] as $handle => $category ) {
				$clean = self::clean_handle( (string) $handle );
				if ( '' === $clean ) {
					continue;
				}
				$map[ $clean ] = Categories::coerce( (string) $category );
			}
		}

		ksort( $map );

		return array( 'handles' => $map );
	}

	/**
	 * The handle => category map from a resolved config.
	 *
	 * @param array<string, mixed> $config Resolved config.
	 * @return array<string, string>
	 */
	public static function handles( array $config ): array {
		$handles = $config['handles'] ?? array();

		return is_array( $handles ) ? $handles : array();
	}

	/**
	 * Strips a handle to the characters WordPress permits in a script handle.
	 */
	public static function clean_handle( string $handle ): string {
		return (string) preg_replace( '/[^A-Za-z0-9_.\-]/', '', trim( $handle ) );
	}
}
