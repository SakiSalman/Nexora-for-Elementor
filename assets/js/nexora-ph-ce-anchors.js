/* Cold Email cross-widget anchors. #contact / #faq / #services live in a different widget, so
   the markup renderer cannot rewrite them; scroll to the [data-ph-anchor] target instead.
   Same lookup nexora-ph-nav.js uses, but bound once at document level. */
(function () {
	'use strict';

	var bound = false;

	function onClick(event) {
		if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
		var anchor = event.target && event.target.closest ? event.target.closest('a[href^="#"]') : null;
		if (!anchor) return;
		var hash = anchor.getAttribute('href') || '';
		var id = hash.charAt(0) === '#' ? hash.slice(1) : '';
		if (!id) return;
		var escaped = window.CSS && CSS.escape ? CSS.escape(id) : id.replace(/"/g, '');
		var target = document.querySelector('[data-ph-anchor="' + escaped + '"]');
		if (!target) return;
		event.preventDefault();
		var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		try {
			target.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth' });
		} catch (e) {
			target.scrollIntoView();
		}
	}

	function bind() {
		if (bound) return;
		bound = true;
		document.addEventListener('click', onClick);
	}

	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', bind);
	else bind();
})();
