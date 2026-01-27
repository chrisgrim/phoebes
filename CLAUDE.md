# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Phoebes is a custom WordPress theme for "The Phoebe's Film Festival" - a film festival in Petaluma focused on independent storytelling. Built on the Underscores (_s) starter theme with video streaming capabilities and film festival-specific features.

## Build Commands

```bash
# Development - watch mode for Tailwind CSS
npm run dev

# Production build - minified CSS
npm run build

# PHP coding standards check
composer run lint:wpcs
```

## Architecture

### Core Theme Structure

- **functions.php** - Main hub: theme setup, custom meta boxes, video upload AJAX handler, festival editions taxonomy, nginx cache purging
- **homepage.php** - Custom homepage template with video player and festival categories
- **phoebe-post-template.php** - Video post template with director/actor info, rankings, and video metadata
- **category.php** - Archive with subcategory filtering and ranking-based sorting

### Custom WordPress Features

**Post Meta Fields:**
- `_video_url` - Featured video URL
- `ranking` - Post rating (0-10 scale)
- `actor_user` / `director_user` - User ID arrays for credits

**Custom Taxonomy:**
- `festival-editions` - Hierarchical taxonomy for organizing by festival year

**AJAX Video Upload:**
- Endpoint: `wp_ajax_nopriv_handle_video_upload`
- Max sizes: 512MB video, 5MB thumbnail
- Password protected via `VIDEO_UPLOAD_PASSWORD` constant in wp-config

### JavaScript Files

- `js/navigation.js` - Mobile/hamburger menu
- `js/custom-video.js` - Homepage video player controls
- `js/video-upload.js` - AJAX upload with progress tracking
- `js/video-meta-box.js` - WordPress media library picker for video selection
- `js/category-filters.js` - Client-side subcategory filtering

### CSS Architecture

- `style.css` - Main WordPress stylesheet with normalize and base styles
- `css/app.css` - Compiled Tailwind output
- `css/tailwind.css` - Tailwind source
- `custom-style.css` - Video player animations and custom components

**Tailwind Theme Colors:**
- `amuse-purple`: #573B7F
- `amuse-pink`: #f70099
- `amuse-dark-purple`: #210B3D

### Conventions

- Function prefix: `phoebes_`
- Handle prefix: `phoebes-`
- Private meta keys prefixed with `_`
- Uses Tailwind utility classes extensively
- Category archives sorted by `ranking` meta descending, then date
