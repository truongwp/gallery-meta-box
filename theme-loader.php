<?php
/**
 * Gallery meta box loader for using in theme
 *
 * @package Gallery_Meta_Box
 * @author Truong Giang <truongwp@gmail.com>
 * @version 0.1.2
 */

if ( defined( 'TRUONGWP_GALLERY_META_BOX_PATH' ) ) {
	return;
}

/*
 * Change these value if need.
 */
define( 'TRUONGWP_GALLERY_META_BOX_PATH', get_template_directory() . '/gallery-meta-box/' );
define( 'TRUONGWP_GALLERY_META_BOX_URL', get_template_directory_uri() . '/gallery-meta-box/' );

require_once TRUONGWP_GALLERY_META_BOX_PATH . 'class-truongwp-gallery-meta-box.php';

/**
 * Initialize.
 */
function truongwp_gallery_meta_box_init() {
	$meta_box = new Truongwp_Gallery_Meta_Box();
	$meta_box->init();
}
add_action( 'after_setup_theme', 'truongwp_gallery_meta_box_init' );
