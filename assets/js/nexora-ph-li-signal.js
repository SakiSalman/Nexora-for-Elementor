/* LI Signal radar: one .hv-card.hv-on per .hv-grid; --mx/--my follow the pointer;
   pause off-screen animations with ph-off. Scoped to [data-nexora-ph-li-signal]. */
(function () {
	'use strict'

	var SELECTOR = '[data-nexora-ph-li-signal]'
	var HOOK = 'frontend/element_ready/ele-ph-li-signal.default'

	function setPt(el, e) {
		var r = el.getBoundingClientRect()
		el.style.setProperty('--mx', (((e.clientX - r.left) / r.width) * 100).toFixed(1) + '%')
		el.style.setProperty('--my', (((e.clientY - r.top) / r.height) * 100).toFixed(1) + '%')
	}

	function pauseOff(root, signal) {
		if (!('IntersectionObserver' in window)) return
		var nodes = root.querySelectorAll('section, footer')
		if (!nodes.length) return
		var io = new IntersectionObserver(
			function (ents) {
				ents.forEach(function (en) {
					en.target.classList.toggle('ph-off', !en.isIntersecting)
				})
			},
			{ rootMargin: '200px 0px' }
		)
		Array.prototype.forEach.call(nodes, function (el) {
			el.classList.add('ph-off')
			io.observe(el)
		})
		if (signal) signal.addEventListener('abort', function () { io.disconnect() })
	}

	function mount(root) {
		if (!root || root.nodeType !== 1) return
		try {
			if (root._nexoraPhAbort && typeof root._nexoraPhAbort.abort === 'function') root._nexoraPhAbort.abort()
		} catch (e) {}
		var signal = null
		if (typeof AbortController !== 'undefined') {
			try {
				var controller = new AbortController()
				signal = controller.signal
				root._nexoraPhAbort = controller
			} catch (e2) {}
		}
		var opts = signal ? { capture: true, signal: signal } : true

		root.addEventListener(
			'pointerover',
			function (e) {
				var c = e.target && e.target.closest ? e.target.closest('.hv-grid .hv-card') : null
				if (!c) return
				var g = c.closest('.hv-grid')
				if (!c.classList.contains('hv-on')) {
					Array.prototype.forEach.call(g.querySelectorAll('.hv-card.hv-on'), function (x) {
						x.classList.remove('hv-on')
					})
					c.classList.add('hv-on')
				}
				if (!(e.relatedTarget && c.contains(e.relatedTarget))) setPt(c, e)
			},
			opts
		)
		root.addEventListener(
			'pointerout',
			function (e) {
				var c = e.target && e.target.closest ? e.target.closest('.hv-card') : null
				if (c && !(e.relatedTarget && c.contains(e.relatedTarget))) setPt(c, e)
			},
			opts
		)

		pauseOff(root, signal)
	}

	function initAll() {
		Array.prototype.forEach.call(document.querySelectorAll(SELECTOR), mount)
	}

	function hook($scope) {
		try {
			if (!$scope || !$scope[0] || !$scope[0].querySelector) return
			var el = $scope[0]
			var root = el.matches && el.matches(SELECTOR) ? el : el.querySelector(SELECTOR)
			if (root) mount(root)
		} catch (e) {}
	}

	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initAll)
	else initAll()

	function addHook() {
		if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) return false
		elementorFrontend.hooks.addAction(HOOK, hook)
		return true
	}

	if (addHook()) return
	if (typeof jQuery === 'undefined') return
	jQuery(window).on('elementor/frontend/init', function () {
		try {
			addHook()
		} catch (e) {}
	})
})()
