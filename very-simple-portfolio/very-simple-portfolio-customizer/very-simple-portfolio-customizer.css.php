<?php
/**
 * Dynamyc CSS
 * Used to display Theme CSS options
 */
global $very_simple_portfolio_parameters;

$css = '';
// Layout
if ( isset( $very_simple_portfolio_parameters['header_width'] ) && $very_simple_portfolio_parameters['header_width'] ) :
	$css .= sprintf( '@media screen and (min-width: 769px) { body .vertical .header .header__container {min-width: %spx }} ', esc_attr( $very_simple_portfolio_parameters['header_width'] ) );
	$css .= sprintf( '@media screen and (min-width: 769px) { body .vertical .header .header__container .header__social-block {max-width: calc(%spx - 2rem) }} ', esc_attr( $very_simple_portfolio_parameters['header_width'] ) );
endif;

if ( isset( $very_simple_portfolio_parameters['header_border_width'] ) && $very_simple_portfolio_parameters['header_border_width'] !== '' ) :
	$css .= sprintf( '@media screen and (min-width: 769px) { .vertical .header {border-right-width: %spx!important }} ', esc_attr( $very_simple_portfolio_parameters['header_border_width'] ) );
	$css .= sprintf( '@media screen and (max-width: 768px) { .vertical .header {border-bottom-width: %spx!important }} ', esc_attr( $very_simple_portfolio_parameters['header_border_width'] ) );
endif;


if ( isset( $very_simple_portfolio_parameters['header_border_color'] ) && $very_simple_portfolio_parameters['header_border_color'] ) :
	if ( strlen( $very_simple_portfolio_parameters['header_border_color'] ) <= 6 ) :
		$prefix1 = '#';
	else :
		$prefix1 = '';
	endif;
	$css .= sprintf( '@media screen and (min-width: 769px) { .vertical .header {border-right-color: %s%s!important }} ', $prefix1, esc_attr( $very_simple_portfolio_parameters['header_border_color'] ) );
	$css .= sprintf( '@media screen and (max-width: 768px) { .vertical .header {border-bottom-color: %s%s!important }} ', $prefix1, esc_attr( $very_simple_portfolio_parameters['header_border_color'] ) );
endif;

if ( isset( $very_simple_portfolio_parameters['menu_margin'] ) && $very_simple_portfolio_parameters['menu_margin'] ) :
	$css .= sprintf( '.vertical .menu-main-container {margin-top: %srem!important } ', esc_attr( $very_simple_portfolio_parameters['menu_margin'] ) );
endif;

if ( isset( $very_simple_portfolio_parameters['category_size'] ) && $very_simple_portfolio_parameters['category_size'] ) :
	$css .= sprintf( '.posts__tab-label, .posts-ajax__tab-label {font-size: %spx!important } ', esc_attr( $very_simple_portfolio_parameters['category_size'] ) );
endif;

if ( isset( $very_simple_portfolio_parameters['category_margin_top'] ) && $very_simple_portfolio_parameters['category_margin_top'] ) :
	$css .= sprintf( '@media screen and (min-width: 769px) { .posts-ajax__row, .posts__categories-wrapper {margin-top: %spx!important }} ', esc_attr( $very_simple_portfolio_parameters['category_margin_top'] ) );
endif;

if ( isset( $very_simple_portfolio_parameters['category_align'] ) && $very_simple_portfolio_parameters['category_align'] ) :
	$css .= sprintf( '.posts-ajax__row, .posts__categories-wrapper {justify-content: %s!important } ', esc_attr( $very_simple_portfolio_parameters['category_align'] ) );
endif;

if ( isset( $very_simple_portfolio_parameters['post_card_border_width'] ) && $very_simple_portfolio_parameters['post_card_border_width'] !== '' ) :
	$css .= sprintf( '.post-card {border-width: %spx!important} ', esc_attr( $very_simple_portfolio_parameters['post_card_border_width'] ) );
	$css .= sprintf( '.post-card .post-card__body {border: none!important} ');
	$css .= sprintf( '.post-card .post-card__body {padding-inline: 1rem!important} ');
endif;

if ( isset( $very_simple_portfolio_parameters['post_card_border_color'] ) && $very_simple_portfolio_parameters['post_card_border_color'] ) :
	if ( strlen( $very_simple_portfolio_parameters['post_card_border_color'] ) <= 6 ) :
		$prefix2 = '#';
	else :
		$prefix2 = '';
	endif;
	$css .= sprintf( '.post-card {border-color:%s%s!important} ', $prefix2, esc_attr( $very_simple_portfolio_parameters['post_card_border_color'] ) );
endif;

if ( isset( $very_simple_portfolio_parameters['portfolio_card_title_size'] ) && $very_simple_portfolio_parameters['portfolio_card_title_size'] ) :
	$css .= sprintf( '.portfolio-post-type .post-card .post-card__heading {font-size: %spx!important} ', esc_attr( $very_simple_portfolio_parameters['portfolio_card_title_size'] ) );
endif;


return $css;
