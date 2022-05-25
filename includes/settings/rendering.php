<?php
declare(strict_types=1);
/**
 * Coil Swag settings rendering helper functions.
 * Creates the basic components that can be used to render elements and tabs in the Coil Swag settings panel
 */

namespace CoilSwag\Rendering;

/**
 * Renders the heading for each section in the settings panel.
 * @return void
 * @param string $heading
 * @param string $description
*/
function render_settings_section_heading( $heading, $description = '' ) {
	printf(
		'<h3>%1$s</h3>',
		esc_html( $heading )
	);

	if ( $description !== '' ) {
		echo '<p>' . esc_html( $description ) . '</p>';
	}
}

/**
 * Renders the heading for input fields in the settings panel.
 * @return void
 * @param string $heading
 * @param string $id Provided if the heading needs an ID
*/
function render_input_field_heading( $heading, $id = '' ) {
	if ( $id !== '' ) {
		printf(
			'<h4 id="%s"><strong>%s</strong></h4>',
			esc_html( $id ),
			esc_html( $heading )
		);
	} else {
		printf(
			'<h4><strong>%s</strong></h4>',
			esc_html( $heading )
		);
	}
}

/**
 * Creates a text input element.
 * @return void
 * @param string $id
 * @param string $name
 * @param string $value
 * @param string $placeholder
 * @param string $heading
 * @param string $description
*/
function render_text_input_field( $id, $name, $value, $placeholder, $heading = '', $description = '' ) {
	if ( $heading !== '' ) {
		render_input_field_heading( $heading );
	}

	printf(
		'<input class="%s" type="%s" name="%s" id="%s" value="%s" placeholder="%s" />',
		esc_attr( 'wide-input' ),
		esc_attr( 'text' ),
		esc_attr( $name ),
		esc_attr( $id ),
		esc_attr( $value ),
		esc_attr( $placeholder )
	);

	if ( $description !== '' ) {
		printf(
			'<p class="description">%s</p>',
			esc_html( $description )
		);
	}
}
