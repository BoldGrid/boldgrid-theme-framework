/* global ajaxurl, crioQuickStartParams */

( function( $ ) {

	const api = wp.customize;
	const { __ } = wp.i18n;

	class CrioQuickStart {

		/**
		 * Constructor
		 *
		 * @since 5.7.0
		 *
		 * @param {object} crioQuickStartParams
		 */
		constructor( crioQuickStartParams ) {
			this.nonce         = crioQuickStartParams.nonce;
			this.iconUrl       = crioQuickStartParams.iconUrl;
			this.contentMarkup = this.getMarkupAjax();
			this.title         = __( 'Crio Quick Start Guide', 'crio' ),
			this.pos1          = 0,
			this.pos2          = 0,
			this.pos3          = 0,
			this.pos4          = 0,
			this.$elmnt;
		}

		/**
		 * getMarkupAjax
		 *
		 * Retrieves the quickstart markup via Ajax Request
		 *
		 * @since 5.7.0
		 *
		 * @return {string} Quickstart HTML Markup
		 */
		getMarkupAjax() {
			var self   = this,
				markup = '<p>' + __( 'The Quickstart Guide could not be retrieved at this time', 'crio' ) + '</p>';
			$.post( ajaxurl, {
				action: 'crio_get_quick_start_markup',
				nonce: self.nonce
			} )
				.done( function( data ) {
					if ( data.markup ) {
						markup = data.markup;
					}
				} );

			return markup;
		}

		getWrapperMarkup() {
			var markup = `
				<div id="crio-quickstart-wrapper" class="crio-quickstart-wrapper minimized">
					<div class="crio-quickstart-header draggable">
						<div class="crio-icon">
							<img src="${this.iconUrl}"></img>
						</div>
						<h3 class="crio-quickstart-title">${this.title}</h3>
						<div class="action-buttons">
							<span class="minimize dashicons dashicons-minus"></span>
							<span class="dismiss dashicons dashicons-no"></span>
						</div>
					</div>
					<div class="crio-quickstart-content">
						${this.contentMarkup}
					</div>
				</div>
			`;
			return markup;
		}

		/**
		 * insertQuickStart.
		 *
		 * This inserts the quickstart elements into the customizer.
		 *
		 * @since 2.7.0
		 */
		insertQuickStart() {
			var $previewContainer = $( '#customize-preview' ),
				markup           = this.getWrapperMarkup();

			$previewContainer.prepend( markup );
			$( '#crio-quickstart-wrapper .minimize' ).on( 'click', this.minimizeQuickstart );
			$( '#crio-quickstart-wrapper' ).on( 'dblclick', this.unMinimizeQuickstart );
		}

		/**
		 * dragElement
		 *
		 * Initiates the draggable event listeners.
		 *
		 * @since 2.7.0
		 */
		dragElement() {
			var self = this,
				$dragElements;

			this.$elmnt = $( '#crio-quickstart-wrapper' );

			$dragElements = this.$elmnt.find( '.draggable' );
			if ( 0 !== $dragElements.count ) {
				$dragElements.on( 'mousedown', function( e ) {
					e.preventDefault();

					$( window ).on( 'mouseup', function() {
						self.closeDragElement();
					} );

					self.dragMouseDown( e );
				} );
			}
		}

		/**
		 * dragMouseDown
		 *
		 * Triggered when the mouse button is clicked.
		 *
		 * @since 2.7.0
		 *
		 * @param {Event} e The triggering event.
		 */
		dragMouseDown( e ) {
			var self   = this;

			$( '#customize-preview' ).prepend( '<div id="crio-draggable-cover"></div>' );

			// get the mouse cursor position at startup:
			this.pos3 = e.clientX;
			this.pos4 = e.clientY;

			// call a function whenever the cursor moves:
			if ( 0 !== this.$elmnt.count ) {
				$( window ).on( 'mousemove', function( e ) {
					var elementHeight = $( self.$elmnt ).find( '.crio-quickstart-header' ).height(),
						elementWidth  = $( self.$elmnt ).width(),
						coverHeight   = $( '#crio-draggable-cover' ).height(),
						coverWidth    = $( '#crio-draggable-cover' ).width(),
						maxPosX       = coverWidth - elementWidth,
						maxPosY       = coverHeight - elementHeight;

					e.preventDefault();
					self.elementDrag( e, maxPosX, maxPosY );
				} );
			}
		}

		/**
		 * elementDrag
		 *
		 * Handles the actual movement of the element. This is triggered
		 * when the mouse is moved.
		 *
		 * @since 2.7.0
		 *
		 * @param {Event} e The triggering event.
		 */
		elementDrag( e, maxPosX, maxPosY ) {
			e.preventDefault();

			// calculate the new cursor position:
			this.pos1 = this.pos3 - e.clientX;
			this.pos2 = this.pos4 - e.clientY;
			this.pos3 = e.clientX;
			this.pos4 = e.clientY;

			let divPosX = parseInt( $( this.$elmnt ).css( 'left' ), 10 );
			let divPosY = parseInt( $( this.$elmnt ).css( 'top' ), 10 );
			let newDivPosX = divPosX - this.pos1;
			let newDivPosY = divPosY - this.pos2;

			newDivPosX = 0 < newDivPosX ? newDivPosX : 0;
			newDivPosX = maxPosX > newDivPosX ? newDivPosX : maxPosX;
			newDivPosY = 0 < newDivPosY ? newDivPosY : 0;
			newDivPosY = maxPosY > newDivPosY ? newDivPosY : maxPosY;

			// set the element's new position:
			$( this.$elmnt ).css( 'top', newDivPosY + 'px' );
			$( this.$elmnt ).css( 'left', newDivPosX + 'px' );
		}

		/**
		 * closeDragElement
		 *
		 * Triggered when mouse button is released.
		 *
		 * @since 2.7.0
		 */
		closeDragElement() {
			$( window ).off( 'mouseup' );
			$( window ).off( 'mousemove' );
			$( '#crio-draggable-cover' ).remove();
		}

		/**
		 * Minimize QuickStart
		 *
		 * Minimizes the Quickstart window.
		 *
		 * @since 2.7.0
		 */
		minimizeQuickstart() {
			$( '#crio-quickstart-wrapper' ).addClass( 'minimized' );
		}

		/**
		 * unMinimizeQuickStart
		 *
		 * UnMinimizes the Quickstart window.
		 *
		 * @since 2.7.0
		 */
		unMinimizeQuickstart() {
			if ( $( '#crio-quickstart-wrapper' ).hasClass( 'minimized' ) ) {
				$( '#crio-quickstart-wrapper' ).removeClass( 'minimized' );
			}
		}
	}

	let crioQuickStart    = new CrioQuickStart( crioQuickStartParams );
	window.crioQuickStart = crioQuickStart;

	api.bind( 'ready', function() {
		crioQuickStart.insertQuickStart();
		$( '#crio-quickstart-wrapper' ).hide();
		wp.customize.previewer.bind( 'ready', function() {
			$( '#crio-quickstart-wrapper' ).show( 'slow' );
			crioQuickStart.dragElement();
		} );
	} );

} )( jQuery );
