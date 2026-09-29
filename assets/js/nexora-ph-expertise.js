/* Prospects Hive expertise. Pointer spotlight from bindExtras(), scoped to this widget. */
(function () {
	'use strict';

	var SELECTOR = '[data-nexora-ph-expertise]';
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
		var opts = signal ? { capture: true, signal: signal } : true;
		var SEL = '.hv-card';
		function setPt(el, e) {
			var r = el.getBoundingClientRect();
			el.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100).toFixed(1) + '%');
			el.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100).toFixed(1) + '%');
		}
		function onPtr(e) {
			var c = e.target.closest && e.target.closest(SEL);
			if (c && root.contains(c) && !(e.relatedTarget && c.contains(e.relatedTarget))) setPt(c, e);
		}
		root.addEventListener('pointerover', onPtr, opts);
		root.addEventListener('pointerout', onPtr, opts);
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
		elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-expertise.default', onReady);
	} else if (!hooked && typeof jQuery !== 'undefined') {
		hooked = true;
		jQuery(window).on('elementor/frontend/init', function () {
			try {
				if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) return;
				elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-expertise.default', onReady);
			} catch (e) {}
		});
	}
})();
