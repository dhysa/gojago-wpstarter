<?php
/**
 * Accessible search form fallback.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="search-form__label" for="search-field"><?php esc_html_e( 'Search', 'gojago-starter' ); ?></label>
	<input id="search-field" class="search-form__input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search the site', 'gojago-starter' ); ?>">
	<button class="search-form__submit" type="submit"><?php esc_html_e( 'Search', 'gojago-starter' ); ?></button>
</form>
