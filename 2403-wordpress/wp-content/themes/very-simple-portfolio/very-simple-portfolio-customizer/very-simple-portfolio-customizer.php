<?php

/**
 * Very Simple Portfolio - simpleportfolio Theme Customizer
 *
 * @package very-simple-portfolio
 */

// Exit if accessed directly.
defined('ABSPATH') || exit();

new Very_Simple_Portfolio_Child_Customizer();
class Very_Simple_Portfolio_Child_Customizer
{
	/**
	 * Called on class initialisation
	 */
	public function __construct()
	{
		/* Add the WordPress actions to register customizer components */
		add_action('customize_register', [$this, 'very_simple_portfolio_settings']);
	}

	/**
	 * Register photograpy portfolio customizer area
	 * @return null
	 */
	public function very_simple_portfolio_settings($customizer)
	{
		if (! function_exists('very_simple_portfolio_theme_slug_sanitize_select')) {
			/**
			 * Select sanitization function
			 * @param string               $input Ensure input is a slug.
			 * @param WP_Customize_Setting $setting Settings.
			 * @return string
			 */
			function very_simple_portfolio_theme_slug_sanitize_select($input, $setting)
			{
				// Ensure input is a slug (lowercase alphanumeric characters, dashes and underscores are allowed only).
				$input = sanitize_key($input);
				// Get the list of possible select options.
				$choices = $setting->manager->get_control($setting->id)
					->choices;
				// If the input is a valid key, return it; otherwise, return the default.
				return array_key_exists($input, $choices)
					? $input
					: $setting->default;
			}
			// old definition here
		}





		// Theme Layout options
		$customizer->add_section('very_simple_portfolio_theme_child_options', [
			'title'      => esc_html__('Very Simple Portfolio', 'very-simple-portfolio'),
			'capability' => 'edit_theme_options',
			'panel'      => 'minimalio_panel',
		]);

		// Settings

		$customizer->add_setting('very_simple_portfolio_settings_header_width', [
			'default'           => 250,
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
		]);

		$customizer->add_setting('very_simple_portfolio_settings_header_border_width', [
			'default'           => '',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
		]);

		$customizer->add_setting('very_simple_portfolio_settings_header_border_color', [
			'default'           => '',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
		]);

		$customizer->add_setting('very_simple_portfolio_settings_menu_margin', [
			'default'           => '',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
		]);

		$customizer->add_setting('very_simple_portfolio_settings_category_font_size', [
			'default'           => '',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
		]);

		$customizer->add_setting('very_simple_portfolio_settings_category_margin_top', [
			'default'           => '',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
		]);

		$customizer->add_setting('very_simple_portfolio_settings_category_align', [
			'default'           => 'left',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
		]);
		$customizer->add_setting('very_simple_portfolio_settings_post_card_border_width', [
			'default'           => '',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
		]);
		$customizer->add_setting('very_simple_portfolio_settings_post_card_border_color', [
			'default'           => '',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
		]);

		$customizer->add_setting('very_simple_portfolio_settings_portfolio_card_title_size', [
			'default'           => '',
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
		]);

		// Controls

		$customizer->add_control(
			new WP_Customize_Control(
				$customizer,
				'very_simple_portfolio_options_header_width',
				[
					'label'       => esc_html__('Vertical Header Width', 'very-simple-portfolio'),
					'description' => esc_html__(
						'Number in PX values',
						'very-simple-portfolio'
					),
					'section'     => 'very_simple_portfolio_theme_child_options',
					'settings'    => 'very_simple_portfolio_settings_header_width',
					'type'        => 'number',
				]
			)
		);

		$customizer->add_control(
			new WP_Customize_Control(
				$customizer,
				'very_simple_portfolio_options_header_border_width',
				[
					'label'       => esc_html__('Vertical Header Border Width', 'very-simple-portfolio'),
					'description' => esc_html__(
						'Number in PX values',
						'very-simple-portfolio'
					),
					'section'     => 'very_simple_portfolio_theme_child_options',
					'settings'    => 'very_simple_portfolio_settings_header_border_width',
					'type'        => 'number',
				]
			)
		);

		// Add controls for our settings, within a section
		$customizer->add_control(
			new WP_Customize_Color_Control(
				$customizer,
				'very_simple_portfolio_header_border_color',
				[
					'label'        => esc_html__(
						'Vertical Header Border Color',
						'very-simple-portfolio'
					),
					'description'  => '',
					'section'     => 'very_simple_portfolio_theme_child_options',
					'settings'     => 'very_simple_portfolio_settings_header_border_color',
				]
			)
		);

		$customizer->add_control(
			new WP_Customize_Control(
				$customizer,
				'very_simple_portfolio_options_menu_margin',
				[
					'label'       => esc_html__('Vertical Menu Margin Top ', 'very-simple-portfolio'),
					'description' => esc_html__(
						'Number in REM values (1REM=16px)',
						'very-simple-portfolio'
					),
					'section'     => 'very_simple_portfolio_theme_child_options',
					'settings'    => 'very_simple_portfolio_settings_menu_margin',
					'type'        => 'number',
				]
			)
		);

		$customizer->add_control(
			new WP_Customize_Control(
				$customizer,
				'very_simple_portfolio_options_category_font_size',
				[
					'label'       => esc_html__('Portfolio/Blog Category Filtering Font Size', 'very-simple-portfolio'),
					'description' => esc_html__(
						'Number in PX',
						'very-simple-portfolio'
					),
					'section'     => 'very_simple_portfolio_theme_child_options',
					'settings'    => 'very_simple_portfolio_settings_category_font_size',
					'type'        => 'number',
				]
			)
		);

		$customizer->add_control(
			new WP_Customize_Control(
				$customizer,
				'very_simple_portfolio_options_category_margin_top',
				[
					'label'       => esc_html__('Portfolio/Blog Category Filtering Top Margin', 'very-simple-portfolio'),
					'description' => esc_html__(
						'Number in PX',
						'very-simple-portfolio'
					),
					'section'     => 'very_simple_portfolio_theme_child_options',
					'settings'    => 'very_simple_portfolio_settings_category_margin_top',
					'type'        => 'number',
				]
			)
		);

		$customizer->add_control(
			new WP_Customize_Control(
				$customizer,
				'very_simple_portfolio_options_category_align',
				[
					'label'       => esc_html__('Portfolio/Blog Category Filtering Align ', 'very-simple-portfolio'),
					'section'     => 'very_simple_portfolio_theme_child_options',
					'settings'    => 'very_simple_portfolio_settings_category_align',
					'type'     => 'select',
					'sanitize_callback' =>
					'very_simple_portfolio_theme_slug_sanitize_select',
					'choices'  => [
						'justify-between' => esc_html__('Left', 'very-simple-portfolio'),
						'flex-end' => esc_html__('Right', 'very-simple-portfolio'),
						'center' => esc_html__('Center', 'very-simple-portfolio'),
					],
				]
			)
		);

		$customizer->add_control(
			new WP_Customize_Control(
				$customizer,
				'very_simple_portfolio_options_post_card_border_width',
				[
					'label'       => esc_html__('Portfolio/Blog Post Card Border Width', 'very-simple-portfolio'),
					'description' => esc_html__(
						'Number in PX',
						'very-simple-portfolio'
					),
					'section'     => 'very_simple_portfolio_theme_child_options',
					'settings'    => 'very_simple_portfolio_settings_post_card_border_width',
					'type'        => 'number',
				]
			)
		);



		// Add controls for our settings, within a section
		$customizer->add_control(
			new WP_Customize_Color_Control(
				$customizer,
				'very_simple_portfolio_options_post_card_border_color',
				[
					'label'        => esc_html__(
						'Portfolio/Blog Post Card Border Color',
						'very-simple-portfolio'
					),
					'description'  => '',
					'section'     => 'very_simple_portfolio_theme_child_options',
					'settings'     => 'very_simple_portfolio_settings_post_card_border_color',
				]
			)
		);

		$customizer->add_control(
			new WP_Customize_Control(
				$customizer,
				'very_simple_portfolio_options_portfolio_card_title_size',
				[
					'label'       => esc_html__('Portfolio Card Title Size', 'very-simple-portfolio'),
					'description' => esc_html__(
						'Number in PX',
						'very-simple-portfolio'
					),
					'section'     => 'very_simple_portfolio_theme_child_options',
					'settings'    => 'very_simple_portfolio_settings_portfolio_card_title_size',
					'type'        => 'number',
				]
			)
		);

		return $customizer;
	}
}
