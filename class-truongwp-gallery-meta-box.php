<?php
/**
 * Gallery meta box class
 *
 * @package Gallery_Meta_Box
 */

/**
 * Class Truongwp_Gallery_Meta_Box
 */
class Truongwp_Gallery_Meta_Box {

	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'add' ) );

		foreach ( $this->post_types() as $post_type ) {
			add_action( 'save_post_' . $post_type, array( $this, 'save' ), 10, 3 );
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		add_action( 'admin_footer', array( $this, 'js_template' ) );
	}

	/**
	 * Enqueue necessary js and css.
	 */
	public function enqueue() {
		if ( ! $this->is_editing_screen() ) {
			return;
		}

		wp_enqueue_style( 'truongwp-gallery-meta-box', TRUONGWP_GALLERY_META_BOX_URL . 'css/gallery-meta-box.css', array(), false );
		wp_enqueue_script( 'truongwp-gallery-meta-box', TRUONGWP_GALLERY_META_BOX_URL . 'js/gallery-meta-box.js', array( 'backbone', 'jquery' ), false, true );
	}

	/**
	 * Add meta box.
	 *
	 * @param string $post_type Post type name.
	 */
	public function add( $post_type ) {
		if ( ! in_array( $post_type, $this->post_types() ) ) {
			return;
		}

		add_meta_box(
			'truongwp-gallery',
			__( 'Gallery', 'gallery-meta-box' ),
			array( $this, 'render' ),
			$post_type,
			'side',
			'default'
		);
	}

	/**
	 * Save meta data.
	 *
	 * @param int     $post_id Post id.
	 * @param WP_Post $post    Post object.
	 * @param boolean $update  Is updating or not.
	 * @return mixed
	 */
	public function save( $post_id, $post, $update ) {
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['gallery_meta_box_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['gallery_meta_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'gallery_meta_box' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// Save data.
		if ( isset( $_POST['gallery_meta_box'] ) ) {
			$value = array_map( 'absint', $_POST['gallery_meta_box'] );
			update_post_meta( $post_id, $this->meta_key(), $value );
		} else {
			delete_post_meta( $post_id, $this->meta_key() );
		}

		/**
		 * Fires after save gallery data.
		 */
		do_action( 'gallery_meta_box_save', $post_id, $post, $update );

		return $post_id;
	}

	/**
	 * Render meta box output.
	 *
	 * @param WP_Post $post Post object.
	 */
	public function render( $post ) {
		wp_nonce_field( 'gallery_meta_box', 'gallery_meta_box_nonce' );
		$ids = get_post_meta( $post->ID, $this->meta_key(), true );
		if ( ! $ids ) {
			$ids = array();
		}
		?>
		<div id="truongwp-gallery-container" class="gallery">
			<?php foreach ( $ids as $id ) : ?>
				<div id="gallery-image-<?php echo absint( $id ); ?>" class="gallery-item">
					<?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?>

					<a href="#" class="gallery-remove">&times;</a>

					<input type="hidden" name="gallery_meta_box[]" value="<?php echo absint( $id ); ?>">
				</div>
			<?php endforeach; ?>
		</div>

		<a href="#" id="truongwp-add-gallery"><?php esc_html_e( 'Set gallery images', 'gallery-meta-box' ); ?></a>

		<input type="hidden" id="truongwp-gallery-ids" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>">
		<?php
	}

	public function js_template() {
		if ( ! $this->is_editing_screen() ) {
			return;
		}
		?>
		<script type="text/html" id="tmpl-gallery-meta-box-image">
			<div id="gallery-image-{{{ data.id }}}" class="gallery-item">
				<img src="{{{ data.url }}}">

				<a href="#" class="gallery-remove">&times;</a>

				<input type="hidden" name="gallery_meta_box[]" value="{{{ data.id }}}">
			</div>
		</script>
		<?php
	}

	/**
	 * Get post types for this meta box.
	 *
	 * @return array
	 */
	protected function post_types() {
		return apply_filters( 'gallery_meta_box_post_types', array( 'post' ) );
	}

	/**
	 * Returns gallery meta key.
	 *
	 * @return string
	 */
	protected function meta_key() {
		return apply_filters( 'gallery_meta_box_meta_key', 'truongwp_gallery' );
	}

	protected function is_editing_screen() {
		$screen = get_current_screen();
		return in_array( $screen->id, $this->post_types() );
	}
}
