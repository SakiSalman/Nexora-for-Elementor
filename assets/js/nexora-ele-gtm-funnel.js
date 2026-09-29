( function () {
	'use strict';

	var hookRegistered = false;

	/**
	 * @param {HTMLElement} root
	 * @returns {object}
	 */
	function parseConfig( root ) {
		var defaults = {
			mode: 'hover',
			defaultActive: 'none',
			defaultIndex: 1,
			dimInactive: true,
			autoRotate: false,
			rotateInterval: 3000,
			enableKeyboard: true,
		};

		try {
			var raw = root.getAttribute( 'data-config' );
			if ( ! raw ) {
				return defaults;
			}
			return Object.assign( {}, defaults, JSON.parse( raw ) );
		} catch ( e ) {
			return defaults;
		}
	}

	/**
	 * @param {NodeListOf<Element>|Array} tiers
	 * @returns {string[]}
	 */
	function collectKeys( tiers ) {
		var keys = [];
		Array.prototype.forEach.call( tiers, function ( el ) {
			if ( ! el || ! el.dataset ) {
				return;
			}
			var key = String( el.dataset.tier || '' );
			if ( key && keys.indexOf( key ) === -1 ) {
				keys.push( key );
			}
		} );
		return keys;
	}

	/**
	 * @param {Function} fn
	 * @param {AbortSignal|null} signal
	 * @param {EventTarget} target
	 * @param {string} type
	 * @param {Function} handler
	 * @param {boolean} [capture]
	 */
	function listen( target, type, handler, signal ) {
		if ( ! target || ! target.addEventListener ) {
			return;
		}
		if ( signal && typeof AbortController !== 'undefined' ) {
			target.addEventListener( type, handler, { signal: signal } );
			return;
		}
		target.addEventListener( type, handler );
	}

	/**
	 * @param {HTMLElement} root
	 */
	function initGtmFunnel( root ) {
		if ( ! root || root.nodeType !== 1 ) {
			return;
		}

		try {
			if ( root._nexoraGtmAbort && typeof root._nexoraGtmAbort.abort === 'function' ) {
				root._nexoraGtmAbort.abort();
			}
		} catch ( e ) {
			// ignore
		}

		var controller = null;
		var signal = null;

		if ( typeof AbortController !== 'undefined' ) {
			try {
				controller = new AbortController();
				signal = controller.signal;
				root._nexoraGtmAbort = controller;
			} catch ( e ) {
				controller = null;
				signal = null;
			}
		}

		var config = parseConfig( root );
		var cards = root.querySelectorAll( '.process-card, .nexora-gtm__card' );
		var tiers = root.querySelectorAll( '.funnel-tier, .nexora-gtm__tier' );
		var keys = collectKeys( tiers );
		var reducedMotion = false;

		try {
			reducedMotion = !!(
				window.matchMedia &&
				window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches
			);
		} catch ( e ) {
			reducedMotion = false;
		}

		var locked = false;
		var activeKey = null;
		var rotateTimer = null;
		var rotateIndex = 0;

		function setActive( key, dim ) {
			activeKey = key;
			var shouldDim = typeof dim === 'boolean' ? dim : !!config.dimInactive;

			Array.prototype.forEach.call( cards, function ( el ) {
				var match = String( el.dataset.tier ) === String( key );
				el.classList.toggle( 'active', match );
				el.classList.toggle( 'dimmed', shouldDim && ! match && !! key );
				if ( config.enableKeyboard ) {
					el.setAttribute( 'aria-pressed', match ? 'true' : 'false' );
				}
			} );

			Array.prototype.forEach.call( tiers, function ( el ) {
				var match = String( el.dataset.tier ) === String( key );
				el.classList.toggle( 'active', match );
				el.classList.toggle( 'dimmed', shouldDim && ! match && !! key );
				if ( config.enableKeyboard ) {
					el.setAttribute( 'aria-pressed', match ? 'true' : 'false' );
				}
			} );
		}

		function clearActive() {
			if ( locked && ( config.mode === 'click' || config.mode === 'both' ) ) {
				return;
			}
			activeKey = null;
			Array.prototype.forEach.call( cards, function ( el ) {
				el.classList.remove( 'active', 'dimmed' );
				if ( config.enableKeyboard ) {
					el.setAttribute( 'aria-pressed', 'false' );
				}
			} );
			Array.prototype.forEach.call( tiers, function ( el ) {
				el.classList.remove( 'active', 'dimmed' );
				if ( config.enableKeyboard ) {
					el.setAttribute( 'aria-pressed', 'false' );
				}
			} );
		}

		function applyDefault() {
			if ( ! keys.length ) {
				return;
			}
			if ( config.defaultActive === 'first' ) {
				setActive( keys[ 0 ], config.dimInactive );
				locked = config.mode === 'click' || config.mode === 'both';
				rotateIndex = 0;
			} else if ( config.defaultActive === 'specific' ) {
				var idx = Math.max( 0, ( config.defaultIndex || 1 ) - 1 );
				if ( keys[ idx ] ) {
					setActive( keys[ idx ], config.dimInactive );
					locked = config.mode === 'click' || config.mode === 'both';
					rotateIndex = idx;
				}
			}
		}

		function onEnter( el ) {
			if ( ! el || ! el.dataset ) {
				return;
			}
			if ( config.mode === 'click' ) {
				return;
			}
			if ( locked && config.mode === 'both' ) {
				return;
			}
			setActive( el.dataset.tier, config.dimInactive );
		}

		function onClick( el ) {
			if ( ! el || ! el.dataset ) {
				return;
			}
			if ( config.mode === 'hover' ) {
				return;
			}
			var key = String( el.dataset.tier );
			if ( locked && activeKey === key ) {
				locked = false;
				clearActive();
				return;
			}
			locked = true;
			setActive( key, config.dimInactive );
			rotateIndex = keys.indexOf( key );
			if ( rotateIndex < 0 ) {
				rotateIndex = 0;
			}
		}

		function bindInteractive( elements ) {
			Array.prototype.forEach.call( elements, function ( el ) {
				listen(
					el,
					'mouseenter',
					function () {
						onEnter( el );
					},
					signal
				);

				listen(
					el,
					'click',
					function ( e ) {
						if ( e && typeof e.preventDefault === 'function' ) {
							e.preventDefault();
						}
						onClick( el );
					},
					signal
				);

				if ( config.enableKeyboard ) {
					listen(
						el,
						'keydown',
						function ( e ) {
							if ( ! e ) {
								return;
							}
							if ( e.key === 'Enter' || e.key === ' ' ) {
								e.preventDefault();
								onClick( el );
							}
						},
						signal
					);
				}
			} );
		}

		bindInteractive( cards );
		bindInteractive( tiers );

		listen(
			root,
			'mouseleave',
			function () {
				if ( config.mode === 'hover' || ( config.mode === 'both' && ! locked ) ) {
					if ( config.defaultActive !== 'none' ) {
						applyDefault();
						locked = false;
					} else {
						clearActive();
					}
				}
			},
			signal
		);

		if ( config.enableKeyboard ) {
			listen(
				root,
				'keydown',
				function ( e ) {
					if ( ! e || ! keys.length ) {
						return;
					}
					var current = activeKey ? keys.indexOf( String( activeKey ) ) : -1;
					if ( e.key === 'ArrowDown' || e.key === 'ArrowRight' ) {
						e.preventDefault();
						rotateIndex = ( current + 1 ) % keys.length;
						locked = true;
						setActive( keys[ rotateIndex ], config.dimInactive );
					} else if ( e.key === 'ArrowUp' || e.key === 'ArrowLeft' ) {
						e.preventDefault();
						rotateIndex = current <= 0 ? keys.length - 1 : current - 1;
						locked = true;
						setActive( keys[ rotateIndex ], config.dimInactive );
					} else if ( e.key === 'Escape' ) {
						locked = false;
						clearActive();
					}
				},
				signal
			);
		}

		function stopRotate() {
			if ( rotateTimer ) {
				window.clearInterval( rotateTimer );
				rotateTimer = null;
			}
		}

		function startRotate() {
			if ( ! config.autoRotate || reducedMotion || keys.length < 2 ) {
				return;
			}
			stopRotate();
			var interval = parseInt( config.rotateInterval, 10 );
			if ( ! interval || interval < 1000 ) {
				interval = 3000;
			}
			rotateTimer = window.setInterval( function () {
				if ( ! keys.length ) {
					return;
				}
				rotateIndex = ( rotateIndex + 1 ) % keys.length;
				locked = true;
				setActive( keys[ rotateIndex ], config.dimInactive );
			}, interval );
		}

		listen( root, 'mouseenter', stopRotate, signal );
		listen( root, 'mouseleave', startRotate, signal );

		if ( signal && typeof signal.addEventListener === 'function' ) {
			signal.addEventListener( 'abort', stopRotate );
		}

		applyDefault();
		startRotate();
	}

	function initAll() {
		try {
			var roots = document.querySelectorAll( '[data-nexora-gtm-funnel]' );
			Array.prototype.forEach.call( roots, initGtmFunnel );
		} catch ( e ) {
			// never break the page
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}

	if ( typeof jQuery !== 'undefined' && ! hookRegistered ) {
		hookRegistered = true;
		jQuery( window ).on( 'elementor/frontend/init', function () {
			try {
				if ( typeof elementorFrontend === 'undefined' || ! elementorFrontend.hooks ) {
					return;
				}

				elementorFrontend.hooks.addAction(
					'frontend/element_ready/ele-gtm-funnel.default',
					function ( $scope ) {
						try {
							if ( ! $scope || ! $scope[ 0 ] || ! $scope[ 0 ].querySelector ) {
								return;
							}
							var root = $scope[ 0 ].querySelector( '[data-nexora-gtm-funnel]' );
							if ( root ) {
								initGtmFunnel( root );
							}
						} catch ( e ) {
							// ignore per-widget init errors
						}
					}
				);
			} catch ( e ) {
				// ignore Elementor hook errors
			}
		} );
	}
}() );
