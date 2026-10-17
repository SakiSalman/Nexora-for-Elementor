/* Cold Email problem timeline. Design bindPt: .pt-fill fills with scroll progress and each
   .pt-item lights up (.on) as it passes 55% of the viewport. Scoped to this widget root. */
(function () {
	'use strict';

	var SELECTOR = '[data-nexora-ph-ce-problem]';
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
			var tl = root.querySelector('.pt-tl');
			if (!tl) return;
			var fill = tl.querySelector('.pt-fill');
			var vh = window.innerHeight || document.documentElement.clientHeight;
			var r = tl.getBoundingClientRect();
			var p = Math.max(0, Math.min(1, (vh * 0.55 - r.top) / r.height));
			if (fill) fill.style.transform = 'scaleY(' + p.toFixed(3) + ')';
			Array.prototype.forEach.call(tl.querySelectorAll('.pt-item'), function (it) {
				it.classList.toggle('on', it.getBoundingClientRect().top < vh * 0.55);
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
		elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-ce-problem.default', onReady);
	} else if (!hooked && typeof jQuery !== 'undefined') {
		hooked = true;
		jQuery(window).on('elementor/frontend/init', function () {
			try {
				if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) return;
				elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-ce-problem.default', onReady);
			} catch (e) {}
		});
	}
})();
