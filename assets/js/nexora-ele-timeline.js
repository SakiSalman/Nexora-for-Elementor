( function () {
	'use strict';

	var hookRegistered = false;

	/**
	 * @param {HTMLElement} root
	 * @returns {{ enabled: boolean, swapDelay: number }}
	 */
	function parseConfig( root ) {
		var defaults = {
			enabled: true,
			swapDelay: 150,
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
	 * @param {EventTarget} target
	 * @param {string} type
	 * @param {Function} handler
	 * @param {AbortSignal|null} signal
	 * @param {AddEventListenerOptions|boolean} [options]
	 */
	function listen( target, type, handler, signal, options ) {
		if ( ! target || ! target.addEventListener ) {
			return;
		}

		var opts = options || {};

		if ( signal && typeof AbortController !== 'undefined' ) {
			if ( typeof opts === 'boolean' ) {
				opts = { capture: opts, signal: signal };
			} else {
				opts = Object.assign( {}, opts, { signal: signal } );
			}
			target.addEventListener( type, handler, opts );
			return;
		}

		target.addEventListener( type, handler, opts );
	}

	/**
	 * @param {HTMLElement} root
	 */
	function initTimeline( root ) {
		if ( ! root || root.nodeType !== 1 ) {
			return;
		}

		try {
			if ( root._nexoraTlAbort && typeof root._nexoraTlAbort.abort === 'function' ) {
				root._nexoraTlAbort.abort();
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
				root._nexoraTlAbort = controller;
			} catch ( e ) {
				controller = null;
				signal = null;
			}
		}

		var config = parseConfig( root );

		if ( ! config.enabled ) {
			return;
		}

		var desktopImg = root.querySelector( '.nexora-ele-timeline__desktop-image' );
		var activeNum = root.querySelector( '[data-active-num]' );
		var progressBar = root.querySelector( '[data-progress-line]' );
		var visual = root.querySelector( '[data-timeline-visual]' );
		var leftCol = root.querySelector( '.nexora-ele-timeline__left' );
		var stepElements = Array.prototype.slice.call(
			root.querySelectorAll( '.timeline-step' )
		);

		if ( ! stepElements.length ) {
			return;
		}

		var total = stepElements.length;
		var activeStepId = 1;
		var swapTimer = null;
		var swapDelay = parseInt( String( config.swapDelay ), 10 );
		var positionRaf = 0;

		if ( ! swapDelay || swapDelay < 0 ) {
			swapDelay = 150;
		}

		/**
		 * @returns {boolean}
		 */
		function isDesktopLayout() {
			try {
				return !!( window.matchMedia && window.matchMedia( '(min-width: 1025px)' ).matches );
			} catch ( e ) {
				return window.innerWidth >= 1025;
			}
		}

		/**
		 * @param {number} id
		 * @returns {HTMLElement|null}
		 */
		function getStepById( id ) {
			for ( var i = 0; i < stepElements.length; i++ ) {
				if ( parseInt( stepElements[ i ].getAttribute( 'data-step-id' ) || '0', 10 ) === id ) {
					return stepElements[ i ];
				}
			}
			return null;
		}

		/**
		 * Align left visual panel with the active step (beside the card).
		 */
		function syncVisualPosition() {
			if ( ! visual ) {
				return;
			}

			if ( ! isDesktopLayout() || ! leftCol ) {
				visual.style.transform = '';
				return;
			}

			var activeEl = getStepById( activeStepId );
			if ( ! activeEl ) {
				visual.style.transform = '';
				return;
			}

			var leftRect = leftCol.getBoundingClientRect();
			var stepRect = activeEl.getBoundingClientRect();
			var offset = stepRect.top - leftRect.top;
			var extra = 0;

			try {
				var rawOffset = window.getComputedStyle( root ).getPropertyValue( '--nexora-tl-visual-offset' );
				extra = parseFloat( rawOffset ) || 0;
			} catch ( e ) {
				extra = 0;
			}

			offset += extra;

			var maxOffset = leftCol.offsetHeight - visual.offsetHeight;

			if ( maxOffset < 0 ) {
				maxOffset = 0;
			}

			if ( offset < 0 ) {
				offset = 0;
			} else if ( offset > maxOffset ) {
				offset = maxOffset;
			}

			visual.style.transform = 'translateY(' + Math.round( offset ) + 'px)';
		}

		function scheduleVisualPosition() {
			if ( positionRaf ) {
				window.cancelAnimationFrame( positionRaf );
			}
			positionRaf = window.requestAnimationFrame( function () {
				positionRaf = 0;
				syncVisualPosition();
			} );
		}

		/**
		 * @param {number} id
		 * @param {boolean} [force]
		 */
		function setActiveStep( id, force ) {
			id = parseInt( String( id ), 10 );
			if ( ! id || ( id === activeStepId && ! force ) ) {
				if ( id === activeStepId ) {
					scheduleVisualPosition();
				}
				return;
			}
			if ( id < 1 || id > total ) {
				return;
			}

			activeStepId = id;

			var activeEl = getStepById( id );
			var nextImage = activeEl ? activeEl.getAttribute( 'data-step-image' ) || '' : '';
			var nextNumber = activeEl ? activeEl.getAttribute( 'data-step-number' ) || '' : '';

			if ( desktopImg && nextImage ) {
				desktopImg.style.opacity = '0';
				desktopImg.style.transform = 'scale(0.97)';

				if ( swapTimer ) {
					window.clearTimeout( swapTimer );
					swapTimer = null;
				}

				swapTimer = window.setTimeout( function () {
					desktopImg.src = nextImage;
					if ( activeEl ) {
						var title = activeEl.querySelector( '.step-title' );
						if ( title && title.textContent ) {
							desktopImg.alt = title.textContent;
						}
					}
					desktopImg.style.opacity = '1';
					desktopImg.style.transform = 'scale(1)';
					swapTimer = null;
					scheduleVisualPosition();

					// Clear inline styles after transition so Style opacity control applies.
					window.setTimeout( function () {
						desktopImg.style.opacity = '';
						desktopImg.style.transform = '';
					}, 450 );
				}, swapDelay );
			}

			if ( activeNum && nextNumber ) {
				activeNum.textContent = nextNumber;
			}

			if ( progressBar ) {
				var percent = total > 1 ? ( ( id - 1 ) / ( total - 1 ) ) * 90 : 0;
				progressBar.style.height = percent + '%';
			}

			stepElements.forEach( function ( el ) {
				var stepId = parseInt( el.getAttribute( 'data-step-id' ) || '1', 10 );
				var mobileImg = el.querySelector( '[data-mobile-image]' );

				el.classList.remove( 'active', 'passed' );

				if ( stepId === id ) {
					el.classList.add( 'active' );
					if ( mobileImg ) {
						mobileImg.classList.remove( 'is-hidden' );
					}
				} else if ( stepId < id ) {
					el.classList.add( 'passed' );
					if ( mobileImg ) {
						mobileImg.classList.add( 'is-hidden' );
					}
				} else if ( mobileImg ) {
					mobileImg.classList.add( 'is-hidden' );
				}
			} );

			scheduleVisualPosition();
		}

		stepElements.forEach( function ( el ) {
			listen(
				el,
				'click',
				function ( e ) {
					if ( e && e.target && e.target.closest && e.target.closest( 'a.step-title-link' ) ) {
						return;
					}
					var stepId = parseInt( el.getAttribute( 'data-step-id' ) || '1', 10 );
					setActiveStep( stepId );
					try {
						el.scrollIntoView( { behavior: 'smooth', block: 'center' } );
					} catch ( err ) {
						el.scrollIntoView( true );
					}
				},
				signal
			);

			listen(
				el,
				'keydown',
				function ( e ) {
					if ( ! e ) {
						return;
					}
					if ( e.key === 'Enter' || e.key === ' ' ) {
						e.preventDefault();
						var stepId = parseInt( el.getAttribute( 'data-step-id' ) || '1', 10 );
						setActiveStep( stepId );
						try {
							el.scrollIntoView( { behavior: 'smooth', block: 'center' } );
						} catch ( err ) {
							el.scrollIntoView( true );
						}
					}
				},
				signal
			);
		} );

		function handleScroll() {
			var viewportTrigger = window.innerHeight * 0.4;
			var closestStepId = activeStepId;
			var minDistance = Infinity;

			stepElements.forEach( function ( el ) {
				var rect = el.getBoundingClientRect();
				var elementCenter = rect.top + rect.height / 2;
				var distance = Math.abs( elementCenter - viewportTrigger );

				if ( rect.top <= window.innerHeight * 0.8 && rect.bottom >= window.innerHeight * 0.15 ) {
					if ( distance < minDistance ) {
						minDistance = distance;
						closestStepId = parseInt( el.getAttribute( 'data-step-id' ) || '1', 10 );
					}
				}
			} );

			if ( closestStepId !== activeStepId ) {
				setActiveStep( closestStepId );
			} else {
				scheduleVisualPosition();
			}
		}

		listen( window, 'scroll', handleScroll, signal, { passive: true } );
		listen( window, 'resize', scheduleVisualPosition, signal, { passive: true } );

		if ( signal && typeof signal.addEventListener === 'function' ) {
			signal.addEventListener( 'abort', function () {
				if ( swapTimer ) {
					window.clearTimeout( swapTimer );
					swapTimer = null;
				}
				if ( positionRaf ) {
					window.cancelAnimationFrame( positionRaf );
					positionRaf = 0;
				}
			} );
		}

		// Initial align beside first active card.
		scheduleVisualPosition();
		window.setTimeout( scheduleVisualPosition, 50 );
	}

	function initAll() {
		try {
			var roots = document.querySelectorAll( '[data-nexora-timeline]' );
			Array.prototype.forEach.call( roots, initTimeline );
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
					'frontend/element_ready/ele-timeline.default',
					function ( $scope ) {
						try {
							if ( ! $scope || ! $scope[ 0 ] || ! $scope[ 0 ].querySelector ) {
								return;
							}
							var root = $scope[ 0 ].querySelector( '[data-nexora-timeline]' );
							if ( root ) {
								initTimeline( root );
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
