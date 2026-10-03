/* Prospects Hive video player. Self-hosted, YouTube, Vimeo + poster overlay. */
(function () {
	'use strict';

	var SELECTOR = '[data-nexora-ph-video]';
	var hooked = false;

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

	function startPlayer(root) {
		if (!root || root.getAttribute('data-playing') === '1') return;
		var source = root.getAttribute('data-source') || 'self';
		var src = root.getAttribute('data-src') || '';
		var surface = root.querySelector('.ph-video-surface');
		var play = root.querySelector('.ph-video-play');
		if (!surface || !src) return;

		root.setAttribute('data-playing', '1');
		root.classList.add('is-playing');
		if (play) play.hidden = true;

		var covers = root.querySelectorAll('.ph-video-cover');
		Array.prototype.forEach.call(covers, function (el) {
			el.hidden = true;
		});

		if (source === 'self') {
			var video = root.querySelector('video.ph-video-el');
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
		surface.innerHTML = '<iframe class="ph-video-frame" src="' + url + '" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>';
	}

	function bind(root) {
		if (!root || root.nodeType !== 1) return;
		var players = root.querySelectorAll('[data-ph-video]');
		Array.prototype.forEach.call(players, function (player) {
			if (player._phVideoBound) return;
			player._phVideoBound = true;
			var btn = player.querySelector('.ph-video-play');
			if (!btn) return;
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				startPlayer(player);
			});
			btn.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					startPlayer(player);
				}
			});
		});
	}

	function initAll() {
		Array.prototype.forEach.call(document.querySelectorAll(SELECTOR), bind);
	}

	function onReady($scope) {
		try {
			if (!$scope || !$scope[0] || !$scope[0].querySelector) return;
			var el = $scope[0];
			var root = el.matches && el.matches(SELECTOR) ? el : el.querySelector(SELECTOR);
			if (root) bind(root);
		} catch (e) {}
	}

	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initAll);
	else initAll();

	if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
		elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-video.default', onReady);
	} else if (!hooked && typeof jQuery !== 'undefined') {
		hooked = true;
		jQuery(window).on('elementor/frontend/init', function () {
			try {
				if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) return;
				elementorFrontend.hooks.addAction('frontend/element_ready/ele-ph-video.default', onReady);
			} catch (e) {}
		});
	}
})();
