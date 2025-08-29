<?php
/**
 * Meta Pixel Tracking Settings Handler for Advanced Tab
 *
 * SETTINGS MANAGEMENT ONLY - Handles saving/retrieving Meta (Facebook) Pixel IDs
 * and validation for link pages. Rendering/output of pixel code is handled
 * in live page files.
 *
 * @package ExtraChillArtistPlatform
 */

defined( 'ABSPATH' ) || exit;

/**
 * Validate Meta Pixel ID format
 *
 * @param string $pixel_id The pixel ID to validate
 * @return bool True if valid, false otherwise
 */
function extrch_validate_meta_pixel_id( $pixel_id ) {
    if ( empty( $pixel_id ) ) {
        return true; // Empty is valid (disabled)
    }

    // Meta Pixel IDs are typically 15-16 digit numbers
    return ctype_digit( $pixel_id ) && strlen( $pixel_id ) >= 15 && strlen( $pixel_id ) <= 16;
}

/**
 * Get Meta Pixel ID for a link page
 *
 * @param int $link_page_id The link page ID
 * @return string The Meta Pixel ID or empty string if not set
 */
function extrch_get_meta_pixel_id( $link_page_id ) {
    if ( empty( $link_page_id ) ) {
        return '';
    }

    return get_post_meta( $link_page_id, '_link_page_meta_pixel_id', true );
}

/**
 * NOTE: Meta Pixel ID saving is now handled by centralized save system
 * in inc/core/actions/save.php - this file only provides helper functions.
 */

/**
 * Check if Meta Pixel tracking is enabled for a link page
 *
 * @param int $link_page_id The link page ID
 * @return bool True if Meta Pixel is enabled, false otherwise
 */
function extrch_is_meta_pixel_enabled( $link_page_id ) {
    $pixel_id = extrch_get_meta_pixel_id( $link_page_id );
    return ! empty( $pixel_id ) && extrch_validate_meta_pixel_id( $pixel_id );
}


/**
 * Get Meta Pixel settings for display in advanced tab
 *
 * @param int $link_page_id The link page ID
 * @return array Array containing Meta Pixel settings
 */
function extrch_get_meta_pixel_settings( $link_page_id ) {
    return array(
        'pixel_id'    => extrch_get_meta_pixel_id( $link_page_id ),
        'is_enabled'  => extrch_is_meta_pixel_enabled( $link_page_id ),
        'is_valid'    => extrch_validate_meta_pixel_id( extrch_get_meta_pixel_id( $link_page_id ) ),
    );
}