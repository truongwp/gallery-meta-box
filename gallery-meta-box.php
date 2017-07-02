<?php
/**
 * Gallery meta box
 *
 * @package Gallery_Meta_Box
 * @author Truong Giang <truongwp@gmail.com>
 */

if ( defined( 'TRUONGWP_GALLERY_META_BOX_PATH' ) ) {
	return;
}

define( 'TRUONGWP_GALLERY_META_BOX_PATH', plugin_dir_path( __FILE__ ) );
define( 'TRUONGWP_GALLERY_META_BOX_URL', plugin_dir_url( __FILE__ ) );

require_once TRUONGWP_GALLERY_META_BOX_PATH . 'class-truongwp-gallery-meta-box.php';

$meta_box = new Truongwp_Gallery_Meta_Box();
$meta_box->init();
