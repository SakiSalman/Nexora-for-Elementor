/* Prospects Hive nav. One open panel per widget root. */
(function () {
	'use strict';

	function bind(root) {
		if (!root || root.nodeType !== 1) return;
		var header = root.querySelector('header.nav');
		if (!header) return;
		try {
			if (root._nexoraNavAbort && typeof root._nexoraNavAbort.abort === 'function') root._nexoraNavAbort.abort();
		} catch (e) {}
		var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
		var signal = controller ? controller.signal : undefined;
		if (controller) root._nexoraNavAbort = controller;

		var open = null;
		var phone = null;
		var menu = false;

		function panel(index) {
			return header.querySelector('[data-nav-panel="' + index + '"]');
		}

		function closeDesktop() {
			open = null;
			header.querySelectorAll('[data-nav-panel]').forEach(function (node) {
				node.hidden = true;
			});
			var scrim = header.querySelector('[data-nav-scrim]');
			if (scrim) scrim.hidden = true;
			header.querySelectorAll('[data-nav-open]').forEach(function (button) {
				button.classList.remove('on');
				button.setAttribute('aria-expanded', 'false');
			});
		}

		function placeDropdown(node, button) {
			if (!node.classList.contains('dd') || !button) return;
			var hb = header.getBoundingClientRect();
			var bb = button.getBoundingClientRect();
			node.hidden = false;
			var width = node.offsetWidth || 320;
			var left = bb.left - hb.left;
			var max = Math.max(16, header.clientWidth - width - 16);
			if (left > max) left = max;
			if (left < 16) left = 16;
			node.style.left = left + 'px';
		}

		function showDesktop(index, button) {
			if (open === index) return;
			closeDesktop();
			var node = panel(index);
			if (!node) return;
			node.hidden = false;
			placeDropdown(node, button);
			if (node.classList.contains('mm')) {
				var scrim = header.querySelector('[data-nav-scrim]');
				if (scrim) scrim.hidden = false;
			}
			if (button) {
				button.classList.add('on');
				button.setAttribute('aria-expanded', 'true');
			}
			open = index;
		}

		function setPhone(next) {
			menu = next;
			var box = header.querySelector('[data-nav-phone-menu]');
			var toggle = header.querySelector('[data-nav-menu]');
			if (box) box.hidden = !menu;
			if (toggle) toggle.setAttribute('aria-expanded', menu ? 'true' : 'false');
			header.classList.toggle('m-open', menu);
			if (!menu) setPhoneSub(null);
		}

		function setPhoneSub(index) {
			phone = index;
			header.querySelectorAll('[data-nav-sub]').forEach(function (node) {
				node.hidden = node.getAttribute('data-nav-sub') !== String(index);
			});
			header.querySelectorAll('[data-nav-phone]').forEach(function (button) {
				var on = button.getAttribute('data-nav-phone') === String(index);
				button.classList.toggle('on', on);
				button.setAttribute('aria-expanded', on ? 'true' : 'false');
			});
		}

		header.addEventListener(
			'click',
			function (event) {
				var openButton = event.target.closest ? event.target.closest('[data-nav-open]') : null;
				if (openButton && header.contains(openButton)) {
					showDesktop(openButton.getAttribute('data-nav-open'), openButton);
					return;
				}
				var phoneButton = event.target.closest ? event.target.closest('[data-nav-phone]') : null;
				if (phoneButton && header.contains(phoneButton)) {
					var id = phoneButton.getAttribute('data-nav-phone');
					setPhoneSub(phone === id ? null : id);
					return;
				}
				if (event.target.closest && event.target.closest('[data-nav-menu]')) {
					setPhone(!menu);
					return;
				}
				if (event.target.closest && event.target.closest('[data-nav-scrim]')) {
					closeDesktop();
					return;
				}
				if (event.target.closest && event.target.closest('[data-nav-link]')) {
					closeDesktop();
					setPhone(false);
				}
			},
			{ signal: signal }
		);

		header.addEventListener(
			'mouseover',
			function (event) {
				var openButton = event.target.closest ? event.target.closest('[data-nav-open]') : null;
				if (openButton && header.contains(openButton)) {
					if (open !== openButton.getAttribute('data-nav-open')) showDesktop(openButton.getAttribute('data-nav-open'), openButton);
					return;
				}
				if (event.target.closest && event.target.closest('[data-nav-link]') && event.target.closest('.hide-md')) {
					closeDesktop();
				}
			},
			{ signal: signal }
		);

		header.addEventListener(
			'mouseleave',
			function () {
				closeDesktop();
			},
			{ signal: signal }
		);

		function onKey(event) {
			if (event.key !== 'Escape') return;
			closeDesktop();
			setPhone(false);
		}
		document.addEventListener('keydown', onKey, { signal: signal });

		var last = 0;
		function syncScrollSolid(y) {
			header.classList.toggle('nav-solid', y > 24);
		}
		function onScroll(event) {
			var t = event && event.target;
			var el = !t || t === document || t === document.documentElement || t === document.body ? document.scrollingElement || document.documentElement : t;
			if (!el || typeof el.scrollTop !== 'number') return;
			var y = el.scrollTop;
			var dy = y - last;
			syncScrollSolid(y);
			if (el.scrollHeight - el.clientHeight < 200) return;
			if (Math.abs(dy) < 6) return;
			last = y;
			var heroEl = document.querySelector('.hero-sec');
			var heroH = heroEl ? heroEl.offsetHeight : 700;
			var hide = dy > 0 && y > heroH - 90 && open === null && !menu;
			header.classList.toggle('nav-hide', hide);
		}
		window.addEventListener('scroll', onScroll, true);
		if (signal) {
			signal.addEventListener('abort', function () {
				window.removeEventListener('scroll', onScroll, true);
			});
		}
		try {
			var startEl = document.scrollingElement || document.documentElement;
			var startY = startEl && typeof startEl.scrollTop === 'number' ? startEl.scrollTop : 0;
			last = startY;
			syncScrollSolid(startY);
		} catch (e) {
			syncScrollSolid(0);
		}

		root.addEventListener(
			'click',
			function (event) {
				var anchor = event.target && event.target.closest ? event.target.closest('a[href^="#"]') : null;
				if (!anchor || !root.contains(anchor)) return;
				var hash = anchor.getAttribute('href') || '';
				var id = hash.charAt(0) === '#' ? hash.slice(1) : '';
				if (!id) return;
				var escaped = window.CSS && CSS.escape ? CSS.escape(id) : id.replace(/"/g, '');
				var target = document.querySelector('[data-ph-anchor="' + escaped + '"]');
				if (!target) return;
				event.preventDefault();
				target.scrollIntoView();
			},
			{ signal: signal }
		);
	}

	function initAll() {
		Array.prototype.forEach.call(document.querySelectorAll('[data-nexora-ph-nav]'), bind);
	}

	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initAll);
	else initAll();

	function addHook() {
		if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) return false;
		elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-nav.default', function ($scope) {
			try {
				if (!$scope || !$scope[0]) return;
				var el = $scope[0];
				var root = el.matches && el.matches('[data-nexora-ph-nav]') ? el : el.querySelector('[data-nexora-ph-nav]');
				bind(root);
			} catch (err) {}
		});
		return true;
	}

	if (addHook()) return;
	if (typeof jQuery === 'undefined') return;
	jQuery(window).on('elementor/frontend/init', function () {
		try {
			addHook();
		} catch (err) {}
	});
})();
