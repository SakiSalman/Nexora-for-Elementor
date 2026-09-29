/* Prospects Hive process timeline. Scroll fill from bindTl(), scoped to this widget. */
(function () {
	'use strict';

	var SELECTOR = '[data-nexora-ph-process]';
	var hooked = false;

	function pauseOff(root, signal) {
		if (!('IntersectionObserver' in window)) return;
		var nodes = root.querySelectorAll('section, footer');
		if (!nodes.length) return;
		var io = new IntersectionObserver(
			function (ents) {
				ents.forEach(function (en) {
					en.target.classList.toggle('ph-off', !en.isIntersecting);
				});
			},
			{ rootMargin: '200px 0px' }
		);
		Array.prototype.forEach.call(nodes, function (el) {
			el.classList.add('ph-off');
			io.observe(el);
		});
		if (signal) signal.addEventListener('abort', function () { io.disconnect(); });
	}

	function mount(root) {
		if (!root || root.nodeType !== 1) return;
		try {
			if (root._nexoraPhAbort && typeof root._nexoraPhAbort.abort === 'function') root._nexoraPhAbort.abort();
		} catch (e) {}
		var signal = null;
		if (typeof AbortController !== 'undefined') {
			try {
				var controller = new AbortController();
				signal = controller.signal;
				root._nexoraPhAbort = controller;
			} catch (e2) {}
		}
		var dead = false;
		if (signal) signal.addEventListener('abort', function () { dead = true; });
		var rows = Array.prototype.slice.call(root.querySelectorAll('.tl-row'));
		var wrap = root.querySelector('.tl-wrap');
		var fill = root.querySelector('.tl-fill');
		if (!rows.length || !wrap) {
			pauseOff(root, signal);
			return;
		}
		var tlIO = null;
		if ('IntersectionObserver' in window) {
			tlIO = new IntersectionObserver(
				function (entries) {
					entries.forEach(function (en) {
						en.target.classList.toggle('tl-in', en.isIntersecting);
					});
				},
				{ rootMargin: '0px 0px -22% 0px', threshold: 0.2 }
			);
			rows.forEach(function (r) { tlIO.observe(r); });
			if (signal) signal.addEventListener('abort', function () { tlIO.disconnect(); });
		} else {
			rows.forEach(function (r) { r.classList.add('tl-in'); });
		}
		var raf = 0;
		function upd() {
			raf = 0;
			if (dead) return;
			var r = wrap.getBoundingClientRect();
			var vh = window.innerHeight || document.documentElement.clientHeight;
			var p = Math.max(0, Math.min(1, (vh * 0.62 - r.top) / r.height));
			if (fill) fill.style.setProperty('--tlp', p.toFixed(3));
		}
		function onTlScroll() {
			if (!raf) raf = requestAnimationFrame(upd);
		}
		window.addEventListener('scroll', onTlScroll, signal ? { capture: true, signal: signal } : true);
		window.addEventListener('resize', onTlScroll, signal ? { signal: signal } : false);
		upd();
		pauseOff(root, signal);
	}

	function initAll() {
		Array.prototype.forEach.call(document.querySelectorAll(SELECTOR), mount);
	}

	function onReady($scope) {
		try {
			if (!$scope || !$scope[0] || !$scope[0].querySelector) return;
			var el = $scope[0];
			var root = el.matches && el.matches(SELECTOR) ? el : el.querySelector(SELECTOR);
			if (root) mount(root);
		} catch (e) {}
	}

	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initAll);
	else initAll();

	if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
		elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-process.default', onReady);
	} else if (!hooked && typeof jQuery !== 'undefined') {
		hooked = true;
		jQuery(window).on('elementor/frontend/init', function () {
			try {
				if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) return;
				elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-process.default', onReady);
			} catch (e) {}
		});
	}
})();
