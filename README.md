## Gallery Meta Box

This is a library for including in plugin, not a WordPress plugin.

### How to use

If you use composer, run `composer require truongwp/gallery-meta-box`.

Or you can include file `gallery-meta-box.php`.

### Hooks reference

```php
/**
 * Fires after saving gallery data.
 *
 * @var int     $post_id Post ID.
 * @var WP_Post $post    Post object.
 * @var bool    $update  Whether this is an existing post being updated or not.
 */
do_action( 'gallery_meta_box_save', $post_id, $post, $update );
```

```php
/**
 * Filters supported post types.
 *
 * @var array $post_types List supported post types.
 */
apply_filters( 'gallery_meta_box_post_types', $post_types );
```

```php
/**
 * Filters meta key to store the gallery.
 *
 * @var string $meta_key Meta key.
 */
apply_filters( 'gallery_meta_box_meta_key', $meta_key );
```
