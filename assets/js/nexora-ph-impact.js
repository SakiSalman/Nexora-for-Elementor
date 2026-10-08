/* Prospects Hive GTM impact tabs. Tab copy comes from the saved repeater. */
(function () {
	'use strict';

	function readData(root) {
		var tpl = root && root.querySelector('template.nexora-ph-tpl');
		var node = tpl && tpl.content && tpl.content.querySelector('script.ph-impact-data');
		if (!node) return null;
		try {
			var data = JSON.parse(node.textContent || '');
			return data && typeof data === 'object' ? data : null;
		} catch (e) {
			return null;
		}
	}

	/* Mobile tab arrows: the strip scrolls sideways, these two step it. */
	var STEP_ATTR = 'data-ph-tabs-step';

	function tabsStrip(root) {
		return root && root.querySelector ? root.querySelector('.tabs-scroll') : null;
	}

	function syncTabArrows(root) {
		var strip = tabsStrip(root);
		if (!strip) return;
		var prev = root.querySelector('[' + STEP_ATTR + '="-1"]');
		var next = root.querySelector('[' + STEP_ATTR + '="1"]');
		var room = strip.scrollWidth - strip.clientWidth - 1;
		var overflow = room > 0;
		if (prev) {
			prev.hidden = !overflow;
			prev.disabled = !overflow || strip.scrollLeft <= 1;
		}
		if (next) {
			next.hidden = !overflow;
			next.disabled = !overflow || strip.scrollLeft >= room;
		}
	}

	function NexoraPHImpact() {
		NexoraPH.DCLogic.call(this);
	}
	NexoraPHImpact.prototype = Object.create(NexoraPH.DCLogic.prototype);
	NexoraPHImpact.prototype.constructor = NexoraPHImpact;

	NexoraPHImpact.prototype.renderVals = function () {
		var self = this;
		var st = this.state || {};
		var data = readData(this.root) || { tabs: [] };
		var tabs = Array.isArray(data.tabs) ? data.tabs : [];
		var imp = st.imp || 0;
		if (imp < 0 || imp >= tabs.length) imp = 0;
		var current = tabs[imp] || {
			label: '',
			title: '',
			body: '',
			image: 'assets/impact.webp',
			alt: '',
			points: [],
		};

		this.queueTabSync();

		return {
			impact: current,
			impactTabs: tabs.map(function (x, i) {
				return {
					label: x.label,
					cls: i === imp ? 'tab on' : 'tab',
					sel: i === imp ? 'true' : 'false',
					pick: function () {
						self.setState({ imp: i });
					},
				};
			}),
		};
	};

	/* The runtime swaps the whole section on every paint, so the strip comes
	   back at scrollLeft 0. Put the reader back where they left the tabs. */
	NexoraPHImpact.prototype.queueTabSync = function () {
		var self = this;
		var root = this.root;
		if (!root) return;
		var run = function () {
			var strip = tabsStrip(root);
			if (strip && typeof self._tabScrollLeft === 'number') {
				strip.scrollLeft = self._tabScrollLeft;
			}
			syncTabArrows(root);
		};
		if (typeof requestAnimationFrame === 'function') requestAnimationFrame(run);
		else setTimeout(run, 0);
	};

	NexoraPHImpact.prototype.componentDidMount = function () {
		var root = this.root;
		if (!root || root._phImpactTabsBound) return;
		root._phImpactTabsBound = true;
		var self = this;

		function prefersReducedMotion() {
			return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
		}

		root.addEventListener('click', function (e) {
			var btn = e.target && e.target.closest ? e.target.closest('[' + STEP_ATTR + ']') : null;
			if (!btn || !root.contains(btn)) return;
			var step = parseInt(btn.getAttribute(STEP_ATTR), 10) || 0;
			var strip = tabsStrip(root);
			if (!strip || !step) return;
			var distance = Math.max(Math.round(strip.clientWidth * 0.7), 180);
			if (typeof strip.scrollBy === 'function') {
				strip.scrollBy({ left: step * distance, behavior: prefersReducedMotion() ? 'auto' : 'smooth' });
			} else {
				strip.scrollLeft += step * distance;
			}
			syncTabArrows(root);
		});

		/* scroll does not bubble, so listen in the capture phase. */
		root.addEventListener(
			'scroll',
			function (e) {
				var el = e.target;
				if (!el || !el.classList || !el.classList.contains('tabs-scroll')) return;
				self._tabScrollLeft = el.scrollLeft;
				syncTabArrows(root);
			},
			true
		);

		this._tabResize = function () {
			syncTabArrows(root);
		};
		window.addEventListener('resize', this._tabResize, { passive: true });

		if (document.fonts && document.fonts.ready && typeof document.fonts.ready.then === 'function') {
			document.fonts.ready.then(function () {
				syncTabArrows(root);
			});
		}

		syncTabArrows(root);
	};

	NexoraPHImpact.prototype.componentWillUnmount = function () {
		if (this._tabResize) {
			window.removeEventListener('resize', this._tabResize);
			this._tabResize = null;
		}
	};

	NexoraPH.boot('ele-ph-impact', '[data-nexora-ph-impact]', NexoraPHImpact);
})();
