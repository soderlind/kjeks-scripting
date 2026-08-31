<?php
/**
 * Settings screen for gated script handles.
 *
 * @package Soderlind\KjeksScripting
 */

declare(strict_types=1);

namespace Soderlind\KjeksScripting;

use Soderlind\Kjeks\AddonKit\Categories;
use Soderlind\Kjeks\AddonKit\AbstractFormTab;

/**
 * A "Scripting" tab on the core Cookie Consent screen where an administrator
 * maps registered script handles to a consent category.
 */
final class Settings extends AbstractFormTab {

	private const BLANK_ROWS = 3;

	protected function option_key(): string {
		return 'kjeks_scripting';
	}

	protected function get_tab_slug(): string {
		return 'scripting';
	}

	protected function get_tab_label(): string {
		return __( 'Scripting', 'kjeks-scripting' );
	}

	protected function get_tab_intro(): string {
		return __( 'Assign an enqueued script handle to a consent category. The script stays inert until the visitor consents to that category. Leave a row blank to ignore it.', 'kjeks-scripting' );
	}

	/**
	 * @return array{handles: array<string, string>}
	 */
	protected function defaults(): array {
		return ScriptRules::defaults();
	}

	/**
	 * @param array<string, mixed> $raw Raw values.
	 * @return array{handles: array<string, string>}
	 */
	protected function normalize( array $raw ): array {
		return ScriptRules::normalize( $raw );
	}

	/**
	 * @param string               $prefix Field-name prefix.
	 * @param array<string, mixed> $config Effective config.
	 */
	protected function render_fields( string $prefix, array $config ): void {
		$handles = ScriptRules::handles( $config );
		?>
		<?php $this->render_handle_datalist(); ?>
		<table class="form-table" role="presentation" id="kjeks-scripting-rows">
			<tbody>
				<?php
				$row = 0;
				foreach ( $handles as $handle => $category ) {
					$this->render_row( $prefix, $row++, (string) $handle, (string) $category );
				}
				for ( $blank = 0; $blank < self::BLANK_ROWS; $blank++ ) {
					$this->render_row( $prefix, $row++, '', 'marketing' );
				}
				?>
			</tbody>
		</table>
		<?php $this->render_clear_script(); ?>
		<?php
	}

	private function render_row( string $prefix, int $index, string $handle, string $category ): void {
		$handle_id = 'kjeks-scripting-handle-' . $index;
		$cat_id    = 'kjeks-scripting-category-' . $index;
		?>
		<tr>
			<th scope="row">
				<label for="<?php echo esc_attr( $handle_id ); ?>"><?php esc_html_e( 'Script handle', 'kjeks-scripting' ); ?></label>
			</th>
			<td>
				<span class="kjeks-scripting-row" style="display:inline-flex;align-items:center;gap:8px;flex-wrap:wrap;">
					<input
						type="text"
						class="regular-text"
						list="kjeks-scripting-handles"
						id="<?php echo esc_attr( $handle_id ); ?>"
						name="<?php echo esc_attr( $this->field_name( $prefix, 'handle' ) . '[]' ); ?>"
						value="<?php echo esc_attr( $handle ); ?>"
					/>
					<?php
					Categories::render_select(
						$this->field_name( $prefix, 'category' ) . '[]',
						$cat_id,
						$category
					);
					?>
					<button
						type="button"
						class="button button-secondary kjeks-scripting-clear"
						data-handle="<?php echo esc_attr( $handle_id ); ?>"
						data-category="<?php echo esc_attr( $cat_id ); ?>"
					><?php esc_html_e( 'Clear', 'kjeks-scripting' ); ?></button>
				</span>
			</td>
		</tr>
		<?php
	}

	/**
	 * Prints the delegated click handler that clears a single row's inputs.
	 */
	private function render_clear_script(): void {
		$script = <<<'JS'
		( function () {
			var table = document.getElementById( 'kjeks-scripting-rows' );
			if ( ! table ) {
				return;
			}
			table.addEventListener( 'click', function ( event ) {
				var button = event.target.closest( '.kjeks-scripting-clear' );
				if ( ! button ) {
					return;
				}
				var handle = document.getElementById( button.dataset.handle );
				var category = document.getElementById( button.dataset.category );
				if ( handle ) {
					handle.value = '';
				}
				if ( category ) {
					category.value = 'marketing';
				}
				if ( handle ) {
					handle.focus();
				}
			} );
		}() );
		JS;

		wp_print_inline_script_tag( $script );
	}

	private function render_handle_datalist(): void {
		if ( ! function_exists( 'wp_scripts' ) ) {
			return;
		}

		$registered = array_keys( wp_scripts()->registered );
		sort( $registered );
		?>
		<datalist id="kjeks-scripting-handles">
			<?php foreach ( $registered as $handle ) : ?>
				<option value="<?php echo esc_attr( (string) $handle ); ?>"></option>
			<?php endforeach; ?>
		</datalist>
		<?php
	}
}
