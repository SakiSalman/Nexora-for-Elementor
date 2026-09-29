/* Prospects Hive growth system. Step and funnel sync from bindExtras(). */
(function () {
	'use strict';

	var SELECTOR = '[data-nexora-ph-growth]';
	var hooked = false;

	function listen(target, type, handler, signal, options) {
		var opts = options || {};
		if (signal) {
			if (typeof opts === 'boolean') opts = { capture: opts, signal: signal };
			else opts = Object.assign({}, opts, { signal: signal });
		}
		target.addEventListener(type, handler, opts);
	}

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
		var gsSteps = Array.prototype.slice.call(root.querySelectorAll('.gs-step'));
		var gsBars = Array.prototype.slice.call(root.querySelectorAll('.gs-fclip .funnel'));
		var gsCur = -1;
		function gsPick(k) {
			if (k === gsCur) return;
			gsCur = k;
			gsSteps.forEach(function (el, j) { el.classList.toggle('on', j === k); });
			gsBars.forEach(function (el, j) { el.classList.toggle('on', j === k); });
			[gsSteps[k], gsBars[k]].forEach(function (el) {
				if (!el) return;
				el.classList.remove('bump');
				void el.offsetWidth;
				el.classList.add('bump');
			});
		}
		if (gsSteps.length && gsBars.length) {
			gsPick(0);
			gsSteps.forEach(function (el, j) {
				listen(el, 'mouseenter', function () { gsPick(j); }, signal);
				listen(el, 'focus', function () { gsPick(j); }, signal);
				listen(el, 'click', function () { gsPick(j); }, signal);
				listen(el, 'keydown', function (e) {
					if (e.key === 'Enter' || e.key === ' ') {
						e.preventDefault();
						gsPick(j);
					}
				}, signal);
			});
			gsBars.forEach(function (el, j) {
				listen(el, 'mouseenter', function () { gsPick(j); }, signal);
				listen(el, 'click', function () { gsPick(j); }, signal);
			});
		}
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
		elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-growth.default', onReady);
	} else if (!hooked && typeof jQuery !== 'undefined') {
		hooked = true;
		jQuery(window).on('elementor/frontend/init', function () {
			try {
				if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) return;
				elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-growth.default', onReady);
			} catch (e) {}
		});
	}
})();
