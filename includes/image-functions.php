<?php
/**
 * Image Functions
 *
 * @package mr-blocks
 */

// add_filter( 'wp_get_attachment_image_attributes', 'mr_blocks_image_markup_responsive_background',10,2 );

function mr_blocks_image_markup_responsive_background( $attributes, $image = null ) {

	if ( isset( $attributes['src'] ) ) {
		$attributes['data-src'] = $attributes['src'];
		$attributes['src']      = ''; // could add default small image or a base64 encoded small image here
	}
	if ( isset( $attributes['srcset'] ) ) {
		$attributes['data-srcset'] = $attributes['srcset'];
		$attributes['srcset']      = '';
	}
	if ( isset( $attributes['class'] ) ) {
		$attributes['class'] .= ' responsive-background-image';
	} else {
		$attributes['class'] = 'responsive-background-image';
	}

	if ( $image ) {
		$attributes['data-fallback'] = wp_get_attachment_image_url( $image->ID, 'large' );
	}

	return $attributes;

}
