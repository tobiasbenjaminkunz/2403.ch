<?php

/**
 * Very Simple Portfolio - Minimalio Child Theme
 *
 * @package verysimpleportfolio
 */

//Add Minimalio default styles
add_action('wp_enqueue_scripts', 'very_simple_portfolio_theme_enqueue_styles', 97);
function very_simple_portfolio_theme_enqueue_styles()
{
    $parenthandle = 'parent-style';
    $theme = wp_get_theme();
    wp_enqueue_style(
        $parenthandle,
        get_template_directory_uri() . '/style.css',
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
}

//Add Very Simple Portfolio styles
add_action('wp_enqueue_scripts', 'very_simple_portfolio_child_add_stylesheet', 99);
function very_simple_portfolio_child_add_stylesheet()
{
    wp_enqueue_style('very-simple-portfolio-child-style', get_stylesheet_directory_uri() . '/style.css', false, '1.0', 'all');
}


//Add Very Simple Portfolio custom styles
add_action('wp_enqueue_scripts', 'very_simple_portfolio_child_add_custom_stylesheet', 98);
function very_simple_portfolio_child_add_custom_stylesheet()
{
    wp_enqueue_style('very-simple-portfolio-child-custom-style', get_stylesheet_directory_uri() . '/assets/very-simple-portfolio-styles.css', false, '1.0', 'all');
}

//Make Very Simple Portfolio available for translation.
load_theme_textdomain('very-simple-portfolio', get_stylesheet_directory_uri() . '/languages');

// Set up the WordPress Theme logo feature.
add_theme_support( 'custom-logo' );


// Include files for the Very Simple Portfolio Customizer options
$very_simple_portfolio_includes = [
    '/very-simple-portfolio-customizer/very-simple-portfolio-customizer.php',
    '/very-simple-portfolio-customizer/very-simple-portfolio-theme-customizer.php',
    '/preview-content/preview-content.php',
    '/preview-content/admin-redirect.php',
    '/preview-content/welcome-notice.php'
];

foreach ($very_simple_portfolio_includes as $file) {
    $filepath = locate_template($file);
    if (! $filepath) {
        trigger_error(sprintf('Error locating /inc%s for inclusion', $file), E_USER_ERROR);
    }
    require_once $filepath;
}
/**
 * Force Spotify embeds to use dark/black theme by adding theme=0 to iframe src.
 */
function mc_spotify_force_dark_iframe_src($html) {
  if (empty($html) || stripos($html, 'spotify.com') === false) {
    return $html;
  }

  if (!preg_match('/src=("|\')([^"\']+)("|\')/i', $html, $m)) {
    return $html;
  }

  $src = $m[2];
  $new_src = add_query_arg('theme', '0', $src);

  return str_replace($src, esc_url($new_src), $html);
}


/**
 * Shortcode:
 *  - Home (limited):      [release_cells boxes="11" pad="1"]
 *  - Artist (all posts):  [release_cells boxes="all" tag="mgs"]
 *
 * Attributes:
 *  boxes: "11" (default) or "all"
 *  tag: artist tag slug
 *  cat: category slug (default "song")
 *  pad: 1/0 (only used when boxes is numeric)
 *  max_posts: how many posts to query when boxes="all" (default 200)
 */
function mc_release_cells_shortcode($atts) {
  $atts = shortcode_atts(array(
    'boxes'     => '11',   // numeric string or "all"
    'tag'       => '',
    'cat'       => 'song',
    'pad'       => '1',
    'max_posts' => '200',
  ), $atts);

  $boxes_raw = strtolower(trim((string) $atts['boxes']));
  $unlimited = in_array($boxes_raw, array('all', 'unlimited', 'none'), true);

  // If limited mode, interpret boxes as integer >= 1
  $target_boxes = 0;
  if (!$unlimited) {
    $target_boxes = max(1, intval($boxes_raw));
  }

  $max_posts = max(1, intval($atts['max_posts']));

  $args = array(
    'post_type'      => 'post',
    'posts_per_page' => $unlimited ? $max_posts : max($target_boxes, $max_posts),
    'orderby'        => 'date',
    'order'          => 'DESC',
    'category_name'  => sanitize_title($atts['cat']),
    'no_found_rows'  => true,
  );

  if (!empty($atts['tag'])) {
    $args['tag'] = sanitize_title($atts['tag']);
  }

  $q = new WP_Query($args);
  if (!$q->have_posts()) {
    return '';
  }

  $used_boxes = 0;

ob_start();
$layout = isset($atts['layout']) ? strtolower(trim((string)$atts['layout'])) : 'spill';
$wrap_class = ($layout === 'grid') ? 'mc-release-grid' : 'mc-release-items';
echo '<div class="' . esc_attr($wrap_class) . '">';



  while ($q->have_posts()) {
    $q->the_post();

    $spotify_url = esc_url_raw(get_post_meta(get_the_ID(), 'spotify_url', true));
    if (!$spotify_url) {
      continue;
    }

    // Determine type + how many "boxes" it consumes (only matters in limited mode)
    $type_class = '';
    $weight = 1;

    if (strpos($spotify_url, '/album/') !== false) {
      $type_class = ' mc-release-album';
      $weight = 2;
    } elseif (strpos($spotify_url, '/playlist/') !== false) {
      $type_class = ' mc-release-playlist';
      $weight = 2;
    }

    // Limited mode: stop/skip to hit exact box count cleanly
    if (!$unlimited) {
      if ($used_boxes >= $target_boxes) {
        break;
      }
      if ($used_boxes + $weight > $target_boxes) {
        // skip oversized item if it doesn't fit the remaining boxes
        continue;
      }
    }

    // Try WP oEmbed first
    $embed = wp_oembed_get($spotify_url);

    // Fallback: iframe
    if (!$embed) {
      $embed_url = preg_replace('#^https://open\.spotify\.com/#', 'https://open.spotify.com/embed/', $spotify_url);
      $embed_url = add_query_arg('theme', '0', $embed_url);
      $embed_url = esc_url($embed_url);

      $height = ($weight === 2) ? 352 : 152;

      $embed = '<iframe style="border-radius:12px" src="' . $embed_url . '" width="100%" height="' . intval($height) . '" frameborder="0" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>';
    }

    // Force dark theme even for oEmbed-generated iframes
    $embed = mc_spotify_force_dark_iframe_src($embed);

    echo '<div class="mc-release-cell' . $type_class . '">' . $embed . '</div>';

    if (!$unlimited) {
      $used_boxes += $weight;
    }
  }

  // Pad only in limited mode
  if (!$unlimited && intval($atts['pad']) === 1) {
    while ($used_boxes < $target_boxes) {
      echo '<div class="mc-release-cell mc-release-empty"></div>';
      $used_boxes += 1;
    }
  }

  echo '</div>';

  wp_reset_postdata();
  return ob_get_clean();
}
add_shortcode('release_cells', 'mc_release_cells_shortcode');


/**
 * =========================================================
 * CONCERTS (KONZERTE) Custom Post Type + Shortcodes
 * Uses:
 *  - CPT: concert
 *  - ACF fields:
 *      concert_date  (Date Picker, return format: Ymd)
 *      concert_venue (Text)
 *  - Artists are WordPress Tags (post_tag): mgs, otis, etc.
 * =========================================================
 */

/**
 * Register CPT "concert" and enable WP tags (post_tag)
 */
function mc_register_concert_cpt() {
  $labels = array(
    'name'               => 'Konzerte',
    'singular_name'      => 'Konzert',
    'menu_name'          => 'Konzerte',
    'name_admin_bar'     => 'Konzert',
    'add_new'            => 'Neu hinzufügen',
    'add_new_item'       => 'Neues Konzert hinzufügen',
    'new_item'           => 'Neues Konzert',
    'edit_item'          => 'Konzert bearbeiten',
    'view_item'          => 'Konzert ansehen',
    'all_items'          => 'Alle Konzerte',
    'search_items'       => 'Konzerte durchsuchen',
    'not_found'          => 'Keine Konzerte gefunden',
    'not_found_in_trash' => 'Keine Konzerte im Papierkorb',
  );

  register_post_type('concert', array(
    'labels'             => $labels,
    'public'             => true,
    'show_in_menu'       => true,
    'menu_icon'          => 'dashicons-tickets-alt',
    'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
    'show_in_rest'       => true,

    // IMPORTANT: avoid conflict with your existing /konzerte/ page
    'has_archive'        => false,

    // Single concert URL: /konzert/slug/
    'rewrite'            => array('slug' => 'konzert'),

    // Reuse existing WP Tags for artists
    'taxonomies'         => array('post_tag'),
  ));
}
add_action('init', 'mc_register_concert_cpt');


/**
 * Helper: format ACF Ymd -> d.m.Y
 */
function mc_format_concert_date($ymd) {
  $ymd = preg_replace('/[^0-9]/', '', (string)$ymd);
  if (strlen($ymd) !== 8) return '';
  $dt = DateTime::createFromFormat('Ymd', $ymd);
  return $dt ? $dt->format('d.m.Y') : '';
}


/**
 * Shortcode:
 *  [concerts type="upcoming" limit="50"]
 *  [concerts type="past" limit="200"]
 * Optional tag filter:
 *  [concerts type="upcoming" tag="mgs,atoggo"]
 *
 * ACF fields expected on each concert:
 *  - concert_date (Ymd)
 *  - concert_venue
 * Artists are taken from WP Tags on the concert post.
 */
function mc_concerts_shortcode($atts) {
  $atts = shortcode_atts(array(
    'type'  => 'upcoming', // upcoming | past
    'limit' => 50,
    'tag'   => '',         // comma-separated tag slugs (optional)
	 
  ), $atts);

  $type  = strtolower(trim((string)$atts['type']));
  $limit = max(1, intval($atts['limit']));

  $today   = current_time('Ymd'); // WP timezone-safe
  $compare = ($type === 'past') ? '<' : '>=';  // past: before today, upcoming: today+future
  $order   = ($type === 'past') ? 'DESC' : 'ASC';

  $tax_query = array();

  // Optional: filter by artist tags (OR logic)
  if (!empty($atts['tag'])) {
    $raw = (string)$atts['tag'];
    $parts = array_filter(array_map('trim', explode(',', $raw)));
    $slugs = array();

    foreach ($parts as $p) {
      $slugs[] = sanitize_title($p);
    }

    if (!empty($slugs)) {
      $tax_query[] = array(
        'taxonomy' => 'post_tag',
        'field'    => 'slug',
        'terms'    => $slugs,
        'operator' => 'IN',
      );
    }
  }

  $args = array(
    'post_type'      => 'concert',
    'posts_per_page' => $limit,
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'concert_date',
    'order'          => $order,
    'meta_query'     => array(
      array(
        'key'     => 'concert_date',
        'value'   => $today,
        'compare' => $compare,
        'type'    => 'NUMERIC',
      )
    ),
    'no_found_rows'  => true,
  );

  if (!empty($tax_query)) {
    $args['tax_query'] = $tax_query;
  }

  $q = new WP_Query($args);
  if (!$q->have_posts()) {
    return '<div class="mc-concerts-empty">IM MOMENT NICHTS ANGEKÜNDIGT.</div>';
  }

  ob_start();
  echo '<div class="mc-concerts mc-concerts-' . esc_attr($type) . '">';

  while ($q->have_posts()) {
    $q->the_post();

$date_raw  = get_post_meta(get_the_ID(), 'concert_date', true);      // ACF Date Picker (Ymd)
$event_name  = get_post_meta(get_the_ID(), 'concert_event_name', true);
$city        = get_post_meta(get_the_ID(), 'concert_city', true);
$venue       = get_post_meta(get_the_ID(), 'concert_venue', true);
$venue_url   = get_post_meta(get_the_ID(), 'concert_venue_url', true);



    // Artists from WP Tags on the concert post
    $terms = get_the_terms(get_the_ID(), 'post_tag');
    $artists = '';
    if (!is_wp_error($terms) && !empty($terms)) {
      $names = wp_list_pluck($terms, 'name');
      $artists = implode(', ', $names);
    }

    $date_display = mc_format_concert_date($date_raw);

   $details_url = get_permalink();
echo '<div class="mc-concert">';

      echo '<div class="mc-concert-date">' . esc_html($date_display ?: $date_raw) . '</div>';
      echo '<div class="mc-concert-meta">';
	  if ($event_name) echo '<div class="mc-concert-event">' . esc_html($event_name) . '</div>';
		if ($city) echo '<div class="mc-concert-city">' . esc_html($city) . '</div>';

        if ($artists) echo '<div class="mc-concert-artists">' . esc_html($artists) . '</div>';
       if ($venue) {
  $venue_url_esc = esc_url($venue_url);

  if ($venue_url_esc) {
    echo '<div class="mc-concert-venue"><a href="' . $venue_url_esc . '" target="_blank" rel="noopener noreferrer">' . esc_html($venue) . '</a></div>';
  } else {
    echo '<div class="mc-concert-venue">' . esc_html($venue) . '</div>';
  }
}

      echo '</div>';
    echo '</div>';

  }

  echo '</div>';

  wp_reset_postdata();
  return ob_get_clean();
}
add_shortcode('concerts', 'mc_concerts_shortcode');

add_action('wp_enqueue_scripts', function () {
  // Google Fonts: Azeret Mono + Abel
  wp_enqueue_style(
    'mc-2403-fonts',
    'https://fonts.googleapis.com/css2?family=Azeret+Mono:wght@400;600;700&family=Abel&display=swap',
    array(),
    null
  );
}, 20);
