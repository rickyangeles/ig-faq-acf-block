<?php
/**
 * Helper functions used throughout
 *
 * @package mr-blocks
 */

/**
 * Get Icon
 * This function is in charge of displaying SVG icons across the site.
 *
 * Place each <svg> source in the /assets/icons/{group}/ directory, without adding
 * both `width` and `height` attributes, since these are added dynamically,
 * before rendering the SVG code.
 *
 * All icons are assumed to have equal width and height, hence the option
 * to only specify a `$size` parameter in the svg methods.
 *
 * @package      EAGenesisChild
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 * @param array $atts  Array of parameters.
 * @return string
 * Modified by Jerod Hammerstein to add width = 100% and height so can have not square svg icons
 */
function mr_blocks_ea_icon( $atts = array() ) {

	$atts = shortcode_atts(
		array(
			'icon'   => false,
			'group'  => 'utility',
			'size'   => 16,
			'height' => false,
			'class'  => false,
		),
		$atts
	);

	if ( empty( $atts['icon'] ) ) {
		return;
	}

	$icon_path = get_stylesheet_directory() . '/svg/' . $atts['group'] . '/' . $atts['icon'] . '.svg';
	if ( ! file_exists( $icon_path ) ) {
		return 'file does not exist: ' . $icon_path;
	}

	$icon = file_get_contents( $icon_path );

	$class = 'svg-icon';
	if ( ! empty( $atts['class'] ) ) {
		$class .= ' ' . esc_attr( $atts['class'] );
	}

	$height = false;

	if ( '' !== $atts['size'] && '100%' !== $atts['size'] ) {
		$width = $atts['size'];
		if ( false !== $atts['height'] ) {
			$height = $atts['height'];
		} else {
			$height = $atts['size'];
		}
	} else {
		$width = '100%';
	}

	if ( false !== $height ) {
		$repl = sprintf( '<svg class="' . $class . '" width="%d" height="%d" aria-hidden="true" role="img" focusable="false" ', $width, $height );
	} else {
		$repl = '<svg class="' . $class . '" width="' . $width . '" aria-hidden="true" role="img" focusable="false" ';
	}
	$svg = preg_replace( '/^<svg /', $repl, trim( $icon ) ); // Add extra attributes to SVG code.
	$svg = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
	$svg = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.

	return $svg;
}

/**
 * Read in ACF field and create class list of boostrap margin utility classes.
 *
 * @return string|bool
 */
function mr_blocks_section_margin() {
	$return = '';

	$mp = get_field( 'mp' );

	if ( $mp ) {
		$margin_top    = isset( $mp['mt'] ) ? $mp['mt'] : false;
		$margin_bottom = isset( $mp['mb'] ) ? $mp['mb'] : false;
	}

	if ( $margin_top && $margin_top > 0 ) {
		$return .= ' mt-' . intval( $margin_top );
	}

	if ( $margin_bottom && $margin_bottom > 0 ) {
		$return .= ' mb-' . intval( $margin_bottom );
	}

	return ( '' === $return ) ? false : $return;
}

/**
 * Read in ACF field and create class list of boostrap padding utility classes.
 *
 * @return string|bool
 */
function mr_blocks_section_padding() {
	$return = '';

	$mp = get_field( 'mp' );

	if ( $mp ) {
		$padding_top    = isset( $mp['pt'] ) ? $mp['pt'] : false;
		$padding_bottom = isset( $mp['pb'] ) ? $mp['pb'] : false;
	}
	if ( $padding_top && $padding_top > 0 ) {
		$return .= ' pt-' . intval( $padding_top );
	}

	if ( $padding_bottom && $padding_bottom > 0 ) {
		$return .= ' pb-' . intval( $padding_bottom );
	}

	return ( '' === $return ) ? false : $return;
}
