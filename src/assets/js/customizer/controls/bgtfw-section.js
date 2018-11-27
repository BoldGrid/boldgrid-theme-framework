const api = wp.customize,
	_embed = api.Section.prototype.embed,
	_isContextuallyActive = api.Section.prototype.isContextuallyActive,
	_attachEvents = api.Section.prototype.attachEvents;

export default {
	attachEvents() {
		var section;
		_attachEvents.call( this );
		if ( 'bgtfw_section' !== this.params.type || 'undefined' === typeof this.params.section ) {
			return;
		}

		section = this;

		section.expanded.bind( function( expanded ) {
			var parent = api.section( section.params.section );
			expanded ? parent.contentContainer.addClass( 'current-section-parent' ) : parent.contentContainer.removeClass( 'current-section-parent' );
		} );

		section.container.find( '.customize-section-back' )
			.off( 'click keydown' )
			.on( 'click keydown', function( event ) {
				if ( api.utils.isKeydownButNotEnterEvent( event ) ) {
					return;
				}

				event.preventDefault(); // Keep this AFTER the key filter above

				if ( section.expanded() ) {
					api.section( section.params.section ).expand();
				}
			} );
	},

	embed() {
		var section, parentContainer;

		_embed.call( this );

		if ( 'bgtfw_section' !== this.params.type || 'undefined' === typeof this.params.section ) {
			return;
		}

		section = this;
		parentContainer = $( '#sub-accordion-section-' + this.params.section );

		parentContainer.append( section.headContainer );
	},

	isContextuallyActive() {
		var section, children, activeCount;

		if ( 'bgtfw_section' !== this.params.type ) {
			return _isContextuallyActive.call( this );
		}

		section = this;
		children = this._children( 'section', 'control' );

		api.section.each( function( child ) {
			if ( ! child.params.section || child.params.section !== section.id ) {
				return;
			}

			children.push( child );
		} );

		children.sort( api.utils.prioritySort );

		activeCount = 0;

		_( children ).each( function( child ) {
			if ( ( 'undefined' !== typeof child.isContextuallyActive ) && ( child.active() && child.isContextuallyActive() ) ) {
				activeCount += 1;
			} else if ( child.active() ) {
				activeCount += 1;
			}
		} );

		return ( 0 !== activeCount );
	}
};
