( function( $ ) {
	"use strict";

	var gallery = window.truongwpGallery = {

		buttonId: '#truongwp-add-gallery',

		imageTpl: wp.media.template('gallery-meta-box-image'),

		imageContainer: '#truongwp-gallery-container',

		idsInput: '#truongwp-gallery-ids',

		getIds: function() {
			var _this = gallery,
				ids = $( _this.idsInput ).val();

			return ids ? ids.split(',') : [];
		},

		frame: function() {
			if ( this._frame )
				return this._frame;

			this._frame = wp.media( {
				title: wp.media.view.l10n.addToGalleryTitle,
				button: {
					text: wp.media.view.l10n.addToGallery
				},
				multiple: true,
				library: {
					type: 'image'
				}
			} );

			this._frame.on( 'open', this.open );

			this._frame.state( 'library' ).on( 'select', this.select );

			return this._frame;
		},

		open: function() {
			var _this = gallery,
				ids = _this.getIds(),
				library = _this.frame().state().get('library'),
				selection = _this.frame().state().get('selection');


			for ( var i in ids ) {
				var attachment = wp.media.attachment( ids[i] );
				attachment.fetch();
				selection.add( attachment );
				// library.add( attachment ? [ attachment ] : [] );
			}
		},

		select: function() {
			var _this = gallery,
				selection = this.get( 'selection' );

			$( _this.imageContainer ).html('');
			selection.map( _this.showAttachments );
		},

		showAttachments: function( attachment ) {
			var _this = gallery, url, tpl;

			url = attachment.attributes.sizes.thumbnail ? attachment.attributes.sizes.thumbnail.url : attachment.attributes.url;

			tpl = _this.imageTpl( { id: attachment.id, url: url } );

			$( _this.imageContainer ).append( tpl );
		},

		init: function() {
			var _this = this;

			$( this.buttonId ).on( 'click', function( ev ) {
				ev.preventDefault();

				_this.frame().open();
			});

			$( this.imageContainer ).on( 'click', '.gallery-remove', function( ev ) {
				ev.preventDefault();

				$(this).closest('.gallery-item').remove();
			});

			$( this.imageContainer ).sortable({
				placeholder: 'gallery-placeholder'
			});
		}
	};

	$(document).ready( function() {
		gallery.init();
	});
})( jQuery );
