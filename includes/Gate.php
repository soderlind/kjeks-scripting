<?php
/**
 * Front-end gating of configured script handles.
 *
 * @package Soderlind\KjeksScripting
 */

declare(strict_types=1);

namespace Soderlind\KjeksScripting;

/**
 * Registers each configured handle with the core Kjeks blocking registry so
 * the shared script gate rewrites it to an inert, consent-aware tag.
 */
final class Gate {

	public function __construct( private readonly Settings $settings ) {}

	public function hooks(): void {
		// Late, so third-party scripts are already registered/enqueued.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_integrations' ), 1000 );
	}

	public function register_integrations(): void {
		if ( is_admin() || ! function_exists( 'kjeks_register_integration' ) ) {
			return;
		}

		$config = $this->settings->resolve();

		foreach ( ScriptRules::handles( $config ) as $handle => $category ) {
			kjeks_register_integration(
				'scripting-' . $handle,
				array(
					'category' => $category,
					'label'    => $handle,
					'handles'  => array( $handle ),
				)
			);
		}
	}
}
