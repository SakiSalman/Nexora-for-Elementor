/* Prospects Hive success stories. Testimonial carousel from bindExtras(). */
(function () {
	'use strict';

	var SELECTOR = '[data-nexora-ph-stories]';
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

	function youtubeId(url) {
		if (!url) return '';
		var m = String(url).match(/(?:youtu\.be\/|youtube(?:-nocookie)?\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/))([A-Za-z0-9_-]{6,})/);
		return m && m[1] ? m[1] : '';
	}

	function vimeoId(url) {
		if (!url) return '';
		var m = String(url).match(/vimeo\.com\/(?:video\/)?(\d+)/);
		return m && m[1] ? m[1] : '';
	}

	function embedUrl(source, src) {
		if (source === 'youtube') {
			var yid = youtubeId(src);
			if (!yid) return '';
			return 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(yid) + '?autoplay=1&rel=0&modestbranding=1';
		}
		if (source === 'vimeo') {
			var vid = vimeoId(src);
			if (!vid) return '';
			return 'https://player.vimeo.com/video/' + encodeURIComponent(vid) + '?autoplay=1';
		}
		return '';
	}

	function startPlayer(player) {
		if (!player || player.getAttribute('data-playing') === '1') return;
		var source = player.getAttribute('data-source') || 'self';
		var src = player.getAttribute('data-src') || '';
		var surface = player.querySelector('.ph-story-surface');
		var play = player.querySelector('.ph-story-play');
		if (!surface || !src) return;

		player.setAttribute('data-playing', '1');
		player.classList.add('is-playing');
		if (play) play.hidden = true;

		var posters = player.querySelectorAll('.ph-story-poster');
		Array.prototype.forEach.call(posters, function (el) {
			el.hidden = true;
		});

		if (source === 'self') {
			var video = player.querySelector('video.ph-story-video-el');
			if (!video) return;
			video.setAttribute('controls', 'controls');
			try {
				var p = video.play();
				if (p && typeof p.catch === 'function') p.catch(function () {});
			} catch (e) {}
			try {
				video.focus();
			} catch (e2) {}
			return;
		}

		var url = embedUrl(source, src);
		if (!url) return;
		surface.innerHTML = '<iframe class="ph-story-frame" src="' + url + '" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>';
	}

	function bindPlayers(root, signal) {
		var players = root.querySelectorAll('[data-ph-video]');
		Array.prototype.forEach.call(players, function (player) {
			if (player._phStoryVideoBound) return;
			player._phStoryVideoBound = true;
			var btn = player.querySelector('.ph-story-play');
			if (!btn) return;
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				startPlayer(player);
			}, signal ? { signal: signal } : false);
			btn.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					e.stopPropagation();
					startPlayer(player);
				}
			}, signal ? { signal: signal } : false);
		});
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
		bindPlayers(root, signal);
		var ts = root.querySelector('.ts');
		if (ts) {
			var track = ts.querySelector('.ts-track');
			var dots = Array.prototype.slice.call(ts.querySelectorAll('.ts-dot'));
			var n = dots.length;
			var i = 0;
			var timer = 0;
			function go(k) {
				if (!track || !n) return;
				i = (k + n) % n;
				track.style.transform = 'translateX(' + (-100 * i) + '%)';
				dots.forEach(function (d, j) { d.classList.toggle('on', j === i); });
			}
			function reset() {
				clearInterval(timer);
				timer = setInterval(function () { go(i + 1); }, 6000);
			}
			if (n) {
				reset();
				ts.addEventListener('click', function (e) {
					var b = e.target.closest('button');
					if (!b) return;
					if (b.dataset.ts === 'prev') go(i - 1);
					else if (b.dataset.ts === 'next') go(i + 1);
					else if (b.dataset.tsI != null) go(+b.dataset.tsI);
					reset();
				}, signal ? { signal: signal } : false);
				ts.addEventListener('mouseenter', function () { clearInterval(timer); }, signal ? { signal: signal } : false);
				ts.addEventListener('mouseleave', reset, signal ? { signal: signal } : false);
				var x0 = null;
				ts.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, signal ? { passive: true, signal: signal } : { passive: true });
				ts.addEventListener('touchend', function (e) {
					if (x0 == null) return;
					var dx = e.changedTouches[0].clientX - x0;
					if (Math.abs(dx) > 40) {
						go(dx < 0 ? i + 1 : i - 1);
						reset();
					}
					x0 = null;
				}, signal ? { signal: signal } : false);
				if (signal) signal.addEventListener('abort', function () { clearInterval(timer); });
			}
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
		elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-stories.default', onReady);
	} else if (!hooked && typeof jQuery !== 'undefined') {
		hooked = true;
		jQuery(window).on('elementor/frontend/init', function () {
			try {
				if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) return;
				elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-stories.default', onReady);
			} catch (e) {}
		});
	}
})();
