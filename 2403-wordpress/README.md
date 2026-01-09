# 2403.ch
Abgabe Repository 2403.ch IM5


# 2403.ch – WordPress Website (Elementor Free) for a Music Collective

Live: https://2403.ch

This repository contains the code and documentation for the **2403** music collective website. The site was built in WordPress using **Elementor (Free)** and a lightweight theme setup (Minimalio + “Very Simple Portfolio” child theme). The main goal was to create a strong visual identity for a **young, urban rap audience** while keeping long-term maintenance and content updates as simple as possible.

---

## Project Description

### Overall goals & audience
The design is meant to appeal to a **young, urban target audience** that listens to rap. It’s expected that the website is mainly used by:
- people who already know the collective and want quick access to releases/links, or
- people who look for basic information (artists, booking, concerts).

Because the website will be used and updated continuously, the focus was on:
- **easy content creation** (new releases / new concerts),
- **automation** (lists update themselves),
- keeping the editing workflow simple inside WordPress (no external build pipeline).

### Authorship & assets
- Texts on the single artist pages were **written by the artists themselves**.
- Background images were **provided by a friend** of the collective.

### Current page structure
- **HOME**: landing + newest releases (Spotify embeds)
- **ARTISTS**: roster overview using **custom graphic name images** (clickable) linking to artist pages  
  *(no portrait photos)*
- **BOOKING**: booking email + roster links + **press text directly on the page** + downloadable press photo folder (ZIP)
- **KONZERTE**: upcoming + past concerts, auto-sorted by date
- **Single artist pages**: no longer include image galleries; they include:
  - short description (artist-written)
  - relevant links (socials, Spotify)
  - newest releases via Spotify embeds (automated)

---

## Process Overview

1. **WordPress setup & layout**
   - Installed WordPress on Hostinger.
   - Designed pages with **Elementor Free**.

2. **Content model planning**
   - Defined a workflow for:
     - Releases (“Song” posts + Spotify URLs)
     - Concerts (CPT + ACF fields + tag-based artists)

3. **Automation via shortcodes**
   - Built shortcodes to output:
     - Spotify embeds in a grid (Home + artist pages)
     - Concert lists split into Upcoming / Past

4. **Styling & UX**
   - Minimal, high-contrast visual system (thin black strokes, uppercase typography).
   - Fixed-image backgrounds for selected pages with mobile-safe implementation.
   - Roster graphics for artists instead of portrait photography.

5. **Deployment**
   - Implemented changes in the child theme (PHP + CSS) and migrated Customizer CSS into versioned assets.

---

## Technical Decisions (and Why)

### WordPress + Elementor Free
Elementor Free does not provide advanced dynamic “Posts/Loop Grid” features. To keep design control while still using dynamic content, the website uses **custom shortcodes** inserted via Elementor’s Shortcode widget.

### Releases as Posts + ACF field
Each new release is created as a WordPress post:
- Category: `song`
- Artist assignment: WordPress tags (e.g. `mgs`, `otis`, `etee`)
- ACF field: `spotify_url`

This avoids brittle scraping and makes content updates simple: publish once, and the release appears automatically where relevant.

### Spotify embeds (tradeoff: performance vs platform traffic)
Spotify embeds were chosen even though pages containing many embeds load slower. This approach was preferred because:
- it directly links users into Spotify (where the collective wants traffic),
- it avoids manually mirroring audio files or building a separate player system,
- it streamlines publishing workflow.

A future improvement would be hosting audio metadata or previews locally to reduce page load time, while still linking to Spotify.

### Concerts as a Custom Post Type (CPT)
Concerts are separated from releases to keep the admin organized:
- CPT: `concert`
- ACF fields:
  - `concert_date` (Ymd, used for sorting)
  - `concert_event_name`
  - `concert_city`
  - `concert_venue`
  - `concert_venue_url`
- Artists on concerts are assigned using **WordPress tags** (reusing the same artist tags as releases)

### Dynamic display in Elementor grids
- Home page uses a “spill into grid cells” technique (`display: contents;`) to integrate releases into a custom Elementor grid layout.
- Artist pages use an **internal grid** layout for releases to ensure all items render reliably and avoid grid collisions.

---

## Functionality / Automation

### 1) Release embeds (Spotify)
**Shortcode:** `release_cells`

Examples:
- Home (limited layout):
  - `[release_cells boxes="11" pad="0"]`
- Artist pages (show all):
  - `[release_cells boxes="all" tag="mgs" layout="grid"]`

Behavior:
- Pulls posts from category `song`, sorted by publish date (newest first)
- Filters by artist tag when provided
- Reads Spotify URL from ACF meta key: `spotify_url`
- Forces dark theme for Spotify embeds

### 2) Concert list (Upcoming / Past)
**Shortcode:** `concerts`

Examples:
- Upcoming:
  - `[concerts type="upcoming" limit="50"]`
- Past:
  - `[concerts type="past" limit="200"]`

Behavior:
- Uses CPT `concert`
- Automatically splits into upcoming vs past based on `concert_date`
- Displays: date, event name, city, artists (from tags), venue (linked if URL is provided)

Empty state (upcoming):
- `IM MOMENT NICHTS ANGEKÜNDIGT`

---

## Problems Encountered (and Fixes)

### Elementor Free lacks dynamic post grids
**Problem:** No built-in filtering/post grid with custom design.
**Fix:** Implemented shortcodes and custom CSS grid logic.

### Spotify embeds not clickable on Home
**Problem:** A transparent Elementor background overlay blocked iframe interactions.
**Fix:** Removed the overlay and validated via DevTools.

### Albums/EPs affected grid consistency
**Problem:** Album embeds are taller than track embeds.
**Fix:** Treated albums/playlists as “2-row items” with grid span rules.

### Releases missing on some artist pages
**Problem:** The “spill into parent grid” method caused layout collisions on artist pages.
**Fix:** Scoped spill behavior to the home layout only; used internal grid rendering on artist pages.

### Fixed background behavior on mobile
**Problem:** `background-attachment: fixed` is unreliable on mobile devices.
**Fix:** Implemented a fixed `::before` background layer with header offsets and mobile-safe sizing.

### Header randomly becoming very large (partially unresolved)
**Problem:** The theme header sometimes expands unexpectedly.
**Work done:** Added CSS stability rules (reduced padding, prevented wrapping).
**Status:** Not completely fixed. There is a strong suspicion the issue is related to
having WordPress/Elementor editor tabs open while viewing the site; after closing all
editing tabs the issue seemed to disappear.

### Performance: pages with many Spotify embeds load slowly (known limitation)
**Problem:** Pages with many embeds have slower loading behavior.
**Reason:** Many third-party iframes are heavy.
**Planned improvement:** In the future this could be optimized by storing more data locally
(e.g. audio previews/metadata) while still keeping Spotify links as the primary listening destination.

---

## Deployment Notes

- Hosting: **Hostinger**
- Site: https://2403.ch
- Deployment method:
  - custom PHP (shortcodes/CPT) stored in the child theme `functions.php`
  - CSS stored in versioned theme assets (migrated from Customizer where possible)
  - ACF field groups exported for reproducibility

Recommended long-term workflow:
- Keep custom functionality inside the child theme or move to a small plugin/mu-plugin if it grows
- Export ACF field groups and store them in `exports/`
- Avoid committing WordPress core, uploads, and any secrets

---

## Repository Contents

This repo focuses on **project-relevant code and documentation** rather than WordPress core:

- `wp-content/themes/very-simple-portfolio-child/`  
  Child theme including custom PHP (shortcodes/CPT) and CSS assets.
- `exports/`  
  Exports of configuration normally stored in the database (e.g. ACF field groups).
- `docs/`  
  Setup notes, deployment notes, and screenshots.

---

## Setup (Local Reproduction)

1. Install WordPress locally (any standard method).
2. Install and activate:
   - Parent theme: **Minimalio**
   - Child theme from this repo: `very-simple-portfolio-child`
3. Install plugins:
   - Elementor (Free)
   - Advanced Custom Fields (ACF)
4. Import ACF field groups (ACF → Tools → Import) from `exports/`
5. Create required pages and insert shortcodes into Elementor layouts:
   - Home: `release_cells` (spill mode for layout)
   - Artist pages: `release_cells` (grid mode)
   - Konzerte: two `concerts` shortcodes (upcoming/past)

---

## Learnings (so far)

- WordPress + Elementor Free can still support dynamic layouts when combining:
  - clean content modeling (posts/CPT + ACF fields)
  - shortcodes as renderers
  - CSS grid techniques for layout control
- Avoiding scraping and using WordPress-native content structures leads to more stable automation.
- Third-party embeds simplify linking and publishing but come with performance costs.

---

## Notes on Sensitive Data

This repository does **not** include:
- `wp-config.php`
- any `.env` files
- Hostinger/SFTP configs (e.g. `sftp.json`)
- database dumps
- `/wp-content/uploads`
