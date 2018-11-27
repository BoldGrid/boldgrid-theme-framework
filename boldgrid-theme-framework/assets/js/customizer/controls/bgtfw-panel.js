const api = wp.customize,
	_embed = api.Panel.prototype.embed,
	_isContextuallyActive = api.Panel.prototype.isContextuallyActive,
	_attachEvents = api.Panel.prototype.attachEvents;

export default {
	attachEvents() {
		var panel;

		_attachEvents.call( this );

		if ( 'bgtfw_panel' !== this.params.type || 'undefined' === typeof this.params.panel ) {
			return;
		}

		panel = this;

		panel.expanded.bind( function( expanded ) {
			var parent = api.panel( panel.params.panel );
			expanded ? parent.contentContainer.addClass( 'current-panel-parent' ) : parent.contentContainer.removeClass( 'current-panel-parent' );
		} );

		panel.container.find( '.customize-panel-back' )
			.off( 'click keydown' )
			.on( 'click keydown', function( event ) {
				if ( api.utils.isKeydownButNotEnterEvent( event ) ) {
					return;
				}

				// Keep this AFTER the key filter above
				event.preventDefault();

				if ( panel.expanded() ) {
					api.panel( panel.params.panel ).expand();
				}
			} );
	},

	embed() {
		var panel, parentContainer;

		_embed.call( this );
		if ( 'bgtfw_panel' !== this.params.type || 'undefined' === typeof this.params.panel ) {
			return;
		}

		panel = this;
		parentContainer = $( '#sub-accordion-panel-' + this.params.panel );

		parentContainer.append( panel.headContainer );
	},

	isContextuallyActive() {
		var panel, children, activeCount;

		if ( 'bgtfw_panel' !== this.params.type ) {
			return _isContextuallyActive.call( this );
		}

		panel = this;
		children = this._children( 'panel', 'section' );

		api.panel.each( function( child ) {
			if ( ! child.params.panel || ( child.params.panel !== panel.id ) ) {
				return;
			}

			children.push( child );
		} );

		children.sort( api.utils.prioritySort );

		activeCount = 0;

		_( children ).each( function( child ) {
			if ( child.active() && child.isContextuallyActive() ) {
				activeCount += 1;
			}
		} );

		return ( 0 !== activeCount );
	},

	/**
	 * Collapse all child sections.
	 *
	 * @since 2.0.0
	 */
	collapseChildren() {
		var children = this._children( 'panel', 'section' );

		_( children ).each( function( child ) {
			if ( child.expanded() ) {
				child.collapse();
			}
		} );
	},

	/**
	 * Wrapper function for the focus() method.
	 *
	 * Because of nested panels, the focus() method does not always work. If you're in a nested
	 * section, it won't focus on the parent panel correctly.
	 *
	 * @since 2.0.0
	 */
	bgtfwFocus() {
		var panel = this;

		if ( panel.expanded() ) {
			panel.collapseChildren();
		} else {
			panel.focus();
		}
	}
};
