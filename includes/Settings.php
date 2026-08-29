<?php
/**
 * Settings screen for gated script handles.
 *
 * @package Soderlind\KjeksScripting
 */

declare(strict_types=1);

namespace Soderlind\KjeksScripting;

use Soderlind\Kjeks\AddonKit\Categories;
use Soderlind\Kjeks\AddonKit\SettingsPage;

/**
 * Network Admin (Multisite) or Settings (single site) screen where an
 * administrator maps registered script handles to a consent category.
 */
final class Settings extends SettingsPage {

	private const BLANK_ROWS = 3;

	protected function option_key(): string {
		return 'kjeks_scripting';
	}

	protected function menu_slug(): string {
		return 'kjeks-scripting';
	}

	protected function page_title(): string {
		return __( 'Kjeks Scripting', 'kjeks-scripting' );
	}

	protected function menu_title(): string {
		return __( 'Scripting', 'kjeks-scripting' );
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
		<p class="description">
			<?php esc_html_e( 'Assign an enqueued script handle to a consent category. The script stays inert until the visitor consents to that category. Leave a row blank to ignore it.', 'kjeks-scripting' ); ?>
		</p>
		<?php $this->render_handle_datalist(); ?>
		<table class="form-table" role="presentation">
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
			</td>
		</tr>
		<?php
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
