/**
 * super-guacamole - Super Guacamole!
 * @version v1.0.4
 * @link https://github.com/dkfiresky/super-guacamole#readme
 * @license MIT
*/
( function( $, undefined ) {

	var defaultTemplates = {
		menu: '<li class="%1$s"><a href="%2$s">%3$s</a>%4$s</li>',
		child_wrap: '<ul>%s</ul>',
		child: '<li class="%1$s"><a href="%2$s">%3$s</a></li>'
	};

	/**
	 * Menu constructor
	 *
	 * @access private
	 * @param {object} options Menu options.
	*/
	function Menu( options ) {
		var defaults,
			settings,
			self = this;

		defaults = {
			href: '',
			title: '&middot;&middot;&middot;',
			children: [],
			templates: {},
			container: null
		};

		settings = $.extend( defaults, options );

		self.href = settings.href;
		self.title = settings.title;
		self.children = settings.children;
		self.templates = settings.templates;
		self.$container = settings.container;
		self.node = null;
		self.attachedNode = null;
		self.options = {}; // Shared options
		self.visible = true;
	}

	/**
	 * Set child
	 * @param	{Menu}	 child	 Child menu element
	 * @param	{number} [index] Optional index. If not specified, child will be added into the end.
	 * @return {Menu}
	*/
	Menu.prototype.set = function( child, index ) {
		if ( false === child instanceof Menu ) {
			throw new Error( 'Invalid argument type' );
		}

		if ( undefined === index ) {
			this.children.push( child );
		} else {
			this.children[ index ] = child;
		}

		return this;
	};

	/**
	 * Alias of `Menu.prototype.set`
	*/
	Menu.prototype.push = function( child ) {
		return this.set( child );
	};

	/**
	 * Get child
	 * @param	{number} index
	 * @return {Menu}
	*/
	Menu.prototype.get = function( index ) {
		return this.has( index ) ? this.children[ index ] : null;
	};

	/**
	 * Check if menu has children with the specified `index`
	 * @param	{number} index
	 * @return {boolean}
	*/
	Menu.prototype.has = function( index ) {
		return undefined !== this.children[ index ];
	};


	/**
	 * Return visibility state flag
	 *
	 * @access private
	 * @return {boolean} Visibility state flag
	*/
	Menu.prototype.isVisible = function() {
		return this.visible;
	}

	Menu.prototype.forEach = function( callback ) {
		return this.children.forEach( callback );
	};

	/**
	 * Count the visible attached nodes
	 * @return {number}
	*/
	Menu.prototype.countVisibleAttachedNodes = function() {
		var self = this,
			count = -1;

		self.children.forEach( function( child ) {
			if ( ! $( child.getAttachedNode() ).attr( 'hidden' ) ) {
				count++;
			}
		} );

		return count;
	};

	/**
	 * Count the visible nodes
	 * @return {number}
	*/
	Menu.prototype.countVisibleNodes = function() {
		var self = this,
		count = 0;

		self.children.forEach( function( child ) {
			if ( ! $( child.getNode() ).attr( 'hidden' ) ) {
				count++;
			}
		} );

		return count;
	};

	/**
	 * Count the `{Menu}` nodes
	 * @return {number}
	*/
	Menu.prototype.countVisible = function() {
		var self = this,
			count = 0;

		self.children.forEach( function( child ) {
			if ( child.isVisible() ) {
				count++;
			}
		} );

		return count;
	};


	/**
	 * Get menu `this.node`
	 * @return {jQuery}
	*/
	Menu.prototype.getNode = function() {
		return this.node;
	};

	/**
	 * Return attached node to the menu element
	 * @return {jQuery}
	*/
	Menu.prototype.getAttachedNode = function() {
		return this.attachedNode;
	};

	/**
	 * Set menu node
	 * @param	{jQuery} $node Menu node
	*/
	Menu.prototype.setNode = function( $node ) {
		this.node = $node;
	};

	/**
	 * Attach a node to the menu element
	 * @param	{jQuery} $node Node element
	*/
	Menu.prototype.attachNode = function( $node ) {
		this.attachedNode = $node;
	};

	/**
	 * Cache children selectors
	 * @param	{jQuery} $nodes		 jQuery nodes.
	 * @param	{jQuery} $attachNodes	 jQuery nodes.
	 * @return {Menu}
	*/
	Menu.prototype.cache = function( $nodes, $attachedNodes ) {
		var self = this;

		self.children.forEach( function( child, index ) {
			child.setNode( $nodes[ index ] );
			child.attachNode( $attachedNodes[ index ] );
		} );

		return self;
	};

	/**
	 * Set options
	 * @param {Object} options Options object
	 * @return {Menu}
	*/
	Menu.prototype.setOptions = function( options ) {
		this.options = options;
		return this;
	};

	/**
	 * Get options
	 * @return {Object}
	*/
	Menu.prototype.getOptions = function() {
		return this.options;
	};

	/**
	 * Render the menu
	 *
	 * @access private
	 * @return {Menu}
	*/
	Menu.prototype.render = function() {
		var self = this,
		$menu = self.options.$menu,
		_children_render = [];

		function _render( children_render ) {
			children_render = children_render || '';

			return self.templates.menu.replace( /\%1\$s/g, 'super-guacamole__menu menu-item' + ( '' === children_render ? '' : ' menu-item-has-children' ) )
				.replace( /\%2\$s/g, self.href )
				.replace( /\%3\$s/g, self.title )
				.replace( /\%4\$s/g,
					children_render ?
						self.templates.child_wrap
							.replace( /\%1\$s/g, 'sub-menu' )
							.replace( /\%2\$s/g, children_render )
					: '' )
		}

		function _render_children() {
			_children_render = [];

			self.children.forEach( function( child ) {
				if ( child instanceof Menu ) {
				_children_render.push(
					self.templates.child.replace( /\%1\$s/g, 'super-guacamole__menu__child menu-item' )
							 .replace( /\%2\$s/g, child.href )
							 .replace( /\%3\$s/g, child.title )
				);
				}
			} );

			return _children_render;
		}

		if ( self.$container ) {
			self.$container.append( _render( _render_children().join( '\n' ) ) );

			self.cache(
				self.$container.find( '.super-guacamole__menu * .super-guacamole__menu__child' ),
				$menu.children( self.options.childrenFilter + ':not(.super-guacamole__menu):not(.super-guacamole__menu__child)' )
			);
		}

		return self;
	};

	/**
	 * Extract elements
	 *
	 * @static
	 * @access private
	 * @param	{jQuery} $elements Collection of elements.
	 * @return {array}			Array of Menu elements
	*/
	Menu.extract = function( $elements ) {
		var arr = [],
		$element,
		child;

		$elements.each( function( index, element ) {
			$element = $( element );

			child = new Menu( {
				href: $element.children( 'a' ).attr( 'href' ),
				title: $element.children( 'a' ).text()
			} );

			child.attachNode( $element );

			arr.push( child );
		} );

		return arr;
	};

	/**
	 * Check if attached nodes fit parent container
	 * @return {boolean}
	*/
	Menu.prototype.attachedNodesFit = function() {
		var self = this,
		width = 0, _width = 0,
		$node, $attachNode,
		maxWidth = self.$container.outerWidth( true ) - self.$container.find( '.super-guacamole__menu' ).outerWidth( true );

		self.children.forEach( function( child ) {
			$attachedNode = $( child.getAttachedNode() );
			$node = $( child.getNode() );
			$attachedNode.removeAttr( 'hidden' );
			$node.attr( 'hidden', true );
		} );

		self.children.forEach( function( child, index ) {
			$attachedNode = $( child.getAttachedNode() );
			$node = $( child.getNode() );

			_width = $attachedNode.outerWidth( true );
			if ( 0 < _width ) {
				$attachedNode.data( 'width', _width );
			}

			width += $attachedNode.data( 'width' );

			if ( width > maxWidth && index >= self.options.minChildren ) {
				$attachedNode.attr( 'hidden', true );
				$node.removeAttr( 'hidden' );
			}
		} );

		return true;
	};

	/**
	 * Check if menu fit & has children
	 * @param {bool}	[flag] Apply the class or return boolean.
	 * @return {bool}
	*/
	Menu.prototype.menuFit = function( flag ) {
		var self = this,
			fns = {
				removeAttr: function( el, attr ) {
					return el.removeAttr( attr );
				},
				attr: function( el, attr ) {
					return el.attr( attr, true );
				}
			},
			fn = 'removeAttr',
			threshold = self.options.threshold || 768;

		flag = flag || false;

		if ( 0 === self.countVisibleNodes() ) {
			fn = 'attr';
		}

		if ( ( threshold - 1 ) >= $( window ).width() ) {
			self.children.forEach( function( child ) {
				$attachedNode = $( child.getAttachedNode() );
				$node = $( child.getNode() );
				$attachedNode.removeAttr( 'hidden' );
				$node.attr( 'hidden', true );
			} );

			fn = 'attr';
		}

		if ( ! flag ) {
			fns[ fn ]( self.$container.find( '.super-guacamole__menu' ), 'hidden' );
		}

		return fn === 'removeAttr';
	};

	/**
	 * Watch handler.
	 *
	 * @access private
	 * @return {Menu}
	*/
	Menu.prototype.watch = function( once ) {
		var self = this,
			node,
			_index = -1,
			_visibility = false,
			_attachedNodesCount = 0,
			$attachedNode;

		once = once || false;

		function watcher() {
			self.attachedNodesFit();
			self.menuFit();
		}

		if ( once ) {
			watcher();
			return self;
		}

		function _debounce( threshold ) {
			var _timeout;

			return function _debounced( $jqEvent ) {
				function _delayed() {
					watcher();
					timeout = null;
				}

				if ( _timeout ) {
					clearTimeout( _timeout );
				}

				_timeout = setTimeout( _delayed, threshold );
			};
		}

		$( window ).on( 'resize', _debounce( 10 ) );
		$( window ).on( 'orientationchange', _debounce( 10 ) );

		return self;
	};

	/**
	 * Super Guacamole!
	 *
	 * @access public
	 * @param	{object} options Super Guacamole menu options.
	*/
	$.fn.superGuacamole = function( options ) {
		var defaults,
			settings,
			$menu = $( this ),
			$main_menu = $menu.find( '#main-menu' ),
			$children,
			the_menu;

		defaults = {
			threshold:			544, // Minimal menu width, when this plugin activates
			minChildren: 		3, // Minimal visible children count
			childrenFilter: 	'li', // Child elements selector
			menuTitle:			'&middot;&middot;&middot;', // Menu title
			menuUrl:			'#', // Menu url
			templates:			defaultTemplates // Templates
		};

		settings = $.extend( defaults, options );

		$children = $main_menu.children( settings.childrenFilter + ':not(.super-guacamole__menu):not(.super-guacamole__menu__child)' );
		the_menu = new Menu( {
			title:		settings.menuTitle,
			href:		settings.menuUrl,
			templates:	settings.templates,
			children:	Menu.extract( $children ),
			container:	$main_menu
		} );

		settings.$menu = $main_menu;

		the_menu.setOptions( settings )
		.render()
		.watch( true )
		.watch();
	};

} ( jQuery ) );
