<?php
/**
 * Theme basic setup.
 *
 * @package very-simple-portfolio
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'minimalio_setup' );

if ( ! function_exists( 'minimalio_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function minimalio_setup() {
	

		// Check and setup theme default settings.
		// minimalio_setup_theme_default_settings();

		add_theme_support( 'starter-content', array(
			'posts' => [
				'theme-preview' => [
					'post_type' => 'page',
					'post_title' => 'Theme Preview',
					'post_content' => file_get_contents(__DIR__ . '/preview-content.html'),
				],
			],
			'options' => [
				'show_on_front' => 'page',
				'page_on_front' => '{{theme-preview}}',
			],
			
		),
	);
	}
}

