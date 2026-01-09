<?php
/**
 * Very Simple Portfolio - Minimalio Theme
 * Inline styles
 *
 * @package very-simple-portfolio
 */

defined( 'ABSPATH' ) || exit();

function very_simple_portfolio_child_dynamic_styles() {

	$styles = [
		'header_width'         => absint( get_theme_mod(
			'very_simple_portfolio_settings_header_width'
		) ),
		'header_border_width'         => absint( get_theme_mod(
			'very_simple_portfolio_settings_header_border_width'
		) ),
		'header_border_color'     => 
			sanitize_hex_color( get_theme_mod( 'very_simple_portfolio_settings_header_border_color' )
		),
		'menu_margin'         => floatval( get_theme_mod(
			'very_simple_portfolio_settings_menu_margin'
		) ),
		'category_size'         => absint( get_theme_mod(
			'very_simple_portfolio_settings_category_font_size'
		) ),
		'category_margin_top'         => absint( get_theme_mod(
			'very_simple_portfolio_settings_category_margin_top'
		) ),
		'category_align'         => sanitize_text_field( get_theme_mod(
			'very_simple_portfolio_settings_category_align'
		) ),
		'post_card_border_width'         => absint( get_theme_mod(
			'very_simple_portfolio_settings_post_card_border_width'
		) ),
		'post_card_border_color'     => 
			sanitize_hex_color( get_theme_mod( 'very_simple_portfolio_settings_post_card_border_color' )
		),
		'portfolio_card_title_size'         => absint( get_theme_mod(
			'very_simple_portfolio_settings_portfolio_card_title_size'
		) ),
	];


		function very_simple_portfolio_mapped_implode( $glue, $array, $symbol = '=' ) {
		return implode(
			$glue,
			array_map(
				function ( $k, $v ) use ( $symbol ) {
					return $k . $symbol . $v;
				},
				array_keys( $array ),
				array_values( $array )
			)
		);
	}


	global $very_simple_portfolio_parameters;
	$very_simple_portfolio_parameters = $styles;
	$css = require_once(__DIR__ . '/very-simple-portfolio-customizer.css.php');

	wp_register_style( 'very-simple-portfolio-options', false );
	wp_enqueue_style( 'very-simple-portfolio-options' );

	wp_add_inline_style(
		'very-simple-portfolio-options',
		$css,
	);
}
add_action( 'wp_enqueue_scripts', 'very_simple_portfolio_child_dynamic_styles' );


