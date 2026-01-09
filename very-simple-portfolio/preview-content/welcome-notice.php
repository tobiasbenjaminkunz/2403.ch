<?php

/**
 * Very Simple Portfolio Welcome Notice
 *
 * @package very-simple-portfolio
 */

// Add custom welcome notice for Very Simple Portfolio
add_action( 'after_switch_theme', 'very_simple_portfolio_set_activation_flag' );
add_action( 'admin_notices', 'very_simple_portfolio_welcome_notice' );
add_action( 'wp_ajax_very_simple_portfolio_dismiss_notice', 'very_simple_portfolio_dismiss_notice' );

// Hide parent theme's welcome notice when child theme is active
add_action( 'init', 'very_simple_portfolio_hide_parent_notice' );
function very_simple_portfolio_hide_parent_notice() {
    // Remove parent theme's welcome notice
    remove_action( 'admin_notices', 'minimalio_welcome_notice' );
}

function very_simple_portfolio_set_activation_flag() {
    set_transient( 'very_simple_portfolio_show_welcome_notice', true, WEEK_IN_SECONDS );
}


function very_simple_portfolio_welcome_notice() {
    $screen = get_current_screen();
    if ( $screen->id !== 'themes' ) {
        return;
    }

    if ( ! get_transient( 'very_simple_portfolio_show_welcome_notice' ) ) {
        return;
    }

    if ( get_user_meta( get_current_user_id(), 'very_simple_portfolio_dismissed_notice', true ) ) {
        return;
    }

    // Get current theme info
    $theme = wp_get_theme();
    $theme_name = $theme->get( 'Name' );
    $screenshot = $theme->get_screenshot();
    
    // Prepare welcome message
    $welcome_title = sprintf( __( 'Thank you for using %s!', 'very-simple-portfolio' ), $theme_name );
    $welcome_text = __( 'You are using a child theme based on Minimalio. Please checkout the Minimalio admin page, where you can find the demos, tutorials and more.', 'very-simple-portfolio' );
    $customizer_text = __( 'And please, also check out the video tutorial how to achieve the exact look of this theme!', 'very-simple-portfolio' );
    $screenshot_alt = sprintf( __( '%s Theme Screenshot', 'very-simple-portfolio' ), $theme_name );
    ?>
    <div class="notice notice-success is-dismissible minimalio-welcome-notice very-simple-portfolio-welcome-notice">
        <div class="minimalio-notice-content">
            <div class="minimalio-notice-text">
                <h1><?php echo esc_html( $welcome_title ); ?></h1>
                <p><?php echo esc_html( $welcome_text ); ?></p>
                <p><?php echo esc_html( $customizer_text ); ?></p>
                <div>
                    <a href="https://minimalio.org/very-simple-portfolio-child-theme/" target="_blank" class="button minimalio-premium-button">
                        <?php _e( 'How to Set Up Video Tutorial', 'very-simple-portfolio' ); ?>
                    </a>             
                    <a href="<?php echo admin_url( 'admin.php?page=minimalio-dashboard' ); ?>" style="margin-top: 10px; margin-left: 10px;" class="button">
                        <?php _e( 'Minimalio Admin Page', 'very-simple-portfolio' ); ?>
                    </a>
                </div>
            </div>
            <?php if ( $screenshot ) : ?>
            <div class="minimalio-notice-image">
                <img src="<?php echo esc_url( $screenshot ); ?>" alt="<?php echo esc_attr( $screenshot_alt ); ?>">
            </div>
            <?php endif; ?>
        </div>
    </div>
        <script>
        jQuery(document).ready(function($) {
            $('.very-simple-portfolio-welcome-notice').on('click', '.notice-dismiss', function() {
                $.post(ajaxurl, {
                    action: 'very_simple_portfolio_dismiss_notice',
                    notice: 'very-simple-portfolio-welcome-notice',
                    nonce: '<?php echo wp_create_nonce( 'very_simple_portfolio_dismiss_notice' ); ?>'
                });
            });
        });
        </script>
    <?php
}

function very_simple_portfolio_dismiss_notice() {
    check_ajax_referer( 'very_simple_portfolio_dismiss_notice', 'nonce' );
    
    if ( isset( $_POST['notice'] ) && $_POST['notice'] === 'very-simple-portfolio-welcome-notice' ) {
        // Set user meta to permanently dismiss the notice for this user
        update_user_meta( get_current_user_id(), 'very_simple_portfolio_dismissed_notice', true );
        delete_transient( 'very_simple_portfolio_show_welcome_notice' );
        wp_die();
    }
    
    wp_die();
}