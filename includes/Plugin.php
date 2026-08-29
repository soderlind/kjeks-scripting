<?php
/**
 * Plugin bootstrap.
 *
 * @package Soderlind\KjeksScripting
 */

declare(strict_types=1);

namespace Soderlind\KjeksScripting;

use Soderlind\Kjeks\AddonKit\Plugin as AddonPlugin;

/**
 * Wires the settings screen and the front-end gate.
 */
final class Plugin extends AddonPlugin {

	protected function register(): void {
		$settings = new Settings();
		$settings->hooks();

		( new Gate( $settings ) )->hooks();
	}
}
