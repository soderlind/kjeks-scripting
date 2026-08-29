<?php
/**
 * Plugin Name:       Kjeks Scripting
 * Plugin URI:        https://github.com/soderlind/kjeks-scripting
 * Description:       Consent-gate any enqueued script by handle for Kjeks — assign registered script handles to a consent category and they stay inert until the visitor consents.
 * Version:           0.1.1
 * Requires at least: 6.8
 * Requires PHP:      8.3
 * Requires Plugins:  kjeks
 * Author:            Per Søderlind
 * Author URI:        https://soderlind.no
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       kjeks-scripting
 * Domain Path:       /languages
 * Network:           true
 *
 * @package Soderlind\KjeksScripting
 */

declare(strict_types=1);

namespace Soderlind\KjeksScripting;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KJEKS_SCRIPTING_VERSION', '0.1.1' );
define( 'KJEKS_SCRIPTING_FILE', __FILE__ );
define( 'KJEKS_SCRIPTING_DIR', plugin_dir_path( __FILE__ ) );
define( 'KJEKS_SCRIPTING_URL', plugin_dir_url( __FILE__ ) );

$kjeks_scripting_autoload = KJEKS_SCRIPTING_DIR . 'vendor/autoload.php';
if ( is_readable( $kjeks_scripting_autoload ) ) {
	require $kjeks_scripting_autoload;
}

// Self-updates from GitHub releases. Private repos need a KJEKS_GITHUB_TOKEN constant.
if ( class_exists( \Soderlind\WordPress\GitHubUpdater::class ) ) {
	\Soderlind\WordPress\GitHubUpdater::init(
		github_url:   'https://github.com/soderlind/kjeks-scripting',
		plugin_file:  __FILE__,
		plugin_slug:  'kjeks-scripting',
		name_regex:   '/kjeks-scripting\.zip/',
		branch:       'main',
		check_period: 6,
		auth_token:   defined( 'KJEKS_GITHUB_TOKEN' ) ? KJEKS_GITHUB_TOKEN : '',
	);
}

add_action(
	'plugins_loaded',
	static function (): void {
		// The core Kjeks plugin (a declared dependency) provides the AddonKit base classes.
		if ( ! class_exists( \Soderlind\Kjeks\AddonKit\Plugin::class ) ) {
			return;
		}

		require_once KJEKS_SCRIPTING_DIR . 'includes/ScriptRules.php';
		require_once KJEKS_SCRIPTING_DIR . 'includes/Settings.php';
		require_once KJEKS_SCRIPTING_DIR . 'includes/Gate.php';
		require_once KJEKS_SCRIPTING_DIR . 'includes/Plugin.php';

		Plugin::instance()->boot();
	}
);
