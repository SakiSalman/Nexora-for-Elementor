/* Cold Email solution stack. Design bindSs: cards get .under as the next card stacks over them,
   and the .sv-flow pill highlights the active step. Scoped to this widget root. */
(function () {
	'use strict';

	var SELECTOR = '[data-nexora-ph-ce-solution]';
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

		var raf = 0;
		var upd = function () {
			raf = 0;
			var cards = Array.prototype.slice.call(root.querySelectorAll('.ss-card'));
			var pills = Array.prototype.slice.call(root.querySelectorAll('.sv-flow span'));
			if (!cards.length) return;
			var act = 0;
			cards.forEach(function (c, i) {
				var top = 120 + i * 34;
				if (c.getBoundingClientRect().top <= top + 40) act = i;
			});
			cards.forEach(function (c, i) {
				var n = cards[i + 1];
				c.classList.toggle('under', !!n && n.getBoundingClientRect().top <= 120 + (i + 1) * 34 + 2);
			});
			pills.forEach(function (p, i) {
				p.classList.toggle('on', i === act);
			});
		};
		var onScroll = function () {
			if (!raf) raf = requestAnimationFrame(upd);
		};
		window.addEventListener('scroll', onScroll, signal ? { capture: true, signal: signal } : true);
		window.addEventListener('resize', onScroll, signal ? { capture: true, signal: signal } : true);
		setTimeout(upd, 300);

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
		elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-ce-solution.default', onReady);
	} else if (!hooked && typeof jQuery !== 'undefined') {
		hooked = true;
		jQuery(window).on('elementor/frontend/init', function () {
			try {
				if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) return;
				elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-ce-solution.default', onReady);
			} catch (e) {}
		});
	}
})();
