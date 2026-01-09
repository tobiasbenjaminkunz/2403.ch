<?php

/**
 * Very Simple Portfolio Admin Redirect and Dashboard Customization
 *
 * @package very-simple-portfolio
 */

// Add Very Simple Portfolio section to the top of Minimalio admin page
add_action( 'admin_init', 'very_simple_portfolio_modify_admin_output' );
function very_simple_portfolio_modify_admin_output() {
    add_action( 'admin_head', 'very_simple_portfolio_buffer_admin_page' );
}

function very_simple_portfolio_buffer_admin_page() {
    global $pagenow;
    if ( isset( $_GET['page'] ) && $_GET['page'] === 'minimalio-dashboard' ) {
        ob_start( 'very_simple_portfolio_inject_admin_content' );
    }
}

function very_simple_portfolio_inject_admin_content( $buffer ) {
    // Find the position after the h1 title
    $search = '<h1>' . __( 'Minimalio Dashboard', 'very-simple-portfolio' ) . '</h1>';
    $pos = strpos( $buffer, $search );
    
    if ( $pos !== false ) {
        $very_simple_portfolio_section = '
        <div class="minimalio-admin-card very-simple-portfolio-welcome-section">
            <div class="minimalio-two-columns">
                <div class="minimalio-column-text">
                    <h2>' . __( 'Welcome to Very Simple Portfolio!', 'very-simple-portfolio' ) . '</h2>
                    <p class="minimalio-moto">' . __( 'Child theme of Minimalio', 'very-simple-portfolio' ) . '</p>
                    
                    <p>' . __( 'Very Simple Portfolio extends Minimalio with additional Theme Settings.', 'very-simple-portfolio' ) . '</p>

                    <p>' . __( 'Please, check out this tutorial for using this child theme.', 'very-simple-portfolio' ) . '</p>
                    
                    <a href="https://minimalio.org/very-simple-portfolio-child-theme/" target="_blank" class="button button-primary">
                        ' . __( 'Very Simple Portfolio Tutorial', 'very-simple-portfolio' ) . '
                    </a>
                    
                    <a href="https://charles.minimalio.org"  target="_blank" class="button button-secondary">
                        ' . __( 'View Demo Site', 'very-simple-portfolio' ) . '
                    </a>
                </div>
                <div class="minimalio-column-video">
                   
                    <img style="max-width:300px; float:right; border: 1px solid #000;" src="' . esc_url( get_stylesheet_directory_uri() . '/screenshot.jpg' ) . '" style="" alt="' . esc_attr__( 'Very Simple Portfolio Theme Screenshot', 'very-simple-portfolio' ) . '">
                </div>
            </div>
        </div>';
        
        // Insert after the h1 title
        $insert_pos = $pos + strlen( $search );
        $buffer = substr_replace( $buffer, $very_simple_portfolio_section, $insert_pos, 0 );
    }
    
    return $buffer;
}
