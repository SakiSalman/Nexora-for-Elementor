/* Prospects Hive template runtime.
   Same look / interp / sc-for / sc-if / on* renderer as the design export.
   Mounts one component per widget root so two copies do not share state.
   An abort on the root clears the previous editor render. */
(function () {
	'use strict';

	var HOLE = /\{\{\s*([^}]+?)\s*\}\}/g;
	var WHOLE = /^\{\{\s*([^}]+?)\s*\}\}$/;

	function look(path, scope) {
		if (path === 'true') return true;
		if (path === 'false') return false;
		if (/^-?\d+(\.\d+)?$/.test(path)) return Number(path);
		var parts = path.split('.');
		var v = scope;
		for (var i = 0; i < parts.length; i++) {
			if (v == null) return undefined;
			v = v[parts[i]];
		}
		return v;
	}

	function interp(str, scope) {
		return str.replace(HOLE, function (_, p) {
			var v = look(p, scope);
			return v == null ? '' : String(v);
		});
	}

	function render(node, scope, out) {
		if (node.nodeType === 3) {
			out.push(document.createTextNode(node.data.indexOf('{{') > -1 ? interp(node.data, scope) : node.data));
			return;
		}
		if (node.nodeType === 8) return;
		if (node.nodeType !== 1) return;
		var tag = node.localName;
		if (tag === 'sc-for') {
			var m = WHOLE.exec(node.getAttribute('list') || '');
			var list = m ? look(m[1], scope) : null;
			var as = node.getAttribute('as') || 'item';
			(list || []).forEach(function (item, i) {
				var s = Object.create(scope);
				s[as] = item;
				s.$index = i;
				kids(node, s, out);
			});
			return;
		}
		if (tag === 'sc-if') {
			var m2 = WHOLE.exec(node.getAttribute('value') || '');
			if (m2 && look(m2[1], scope)) kids(node, scope, out);
			return;
		}
		var el = document.importNode(node, false);
		Array.prototype.slice.call(node.attributes).forEach(function (a) {
			if (a.value.indexOf('{{') < 0) return;
			var w = WHOLE.exec(a.value);
			if (/^on/i.test(a.name)) {
				el.removeAttribute(a.name);
				var fn = w ? look(w[1], scope) : null;
				if (typeof fn === 'function') {
					el.addEventListener(a.name.slice(2).toLowerCase(), function (e) {
						fn(e);
					});
				}
				return;
			}
			if (/^hint-/.test(a.name)) {
				el.removeAttribute(a.name);
				return;
			}
			if (w) {
				var v = look(w[1], scope);
				if (v == null || v === false) el.removeAttribute(a.name);
				else el.setAttribute(a.name, String(v));
			} else el.setAttribute(a.name, interp(a.value, scope));
		});
		var inner = [];
		kids(node.content || node, scope, inner);
		inner.forEach(function (c) {
			el.appendChild(c);
		});
		out.push(el);
	}

	function kids(node, scope, out) {
		for (var c = node.firstChild; c; c = c.nextSibling) render(c, scope, out);
	}

	function keep(o, n) {
		if (!o || !n || !o.classList) return;
		o.classList.forEach(function (c) {
			if (/^(nav-hide|nav-solid|tl-in|ph-off)$/.test(c)) n.classList.add(c);
		});
		var s = o.querySelector && o.querySelector('.tl-fill');
		var t = n.querySelector && n.querySelector('.tl-fill');
		if (s && t && s.style.cssText) t.style.cssText = s.style.cssText;
	}

	function DCLogic() {
		this.state = {};
	}

	DCLogic.prototype.setState = function (p) {
		var n = typeof p === 'function' ? p(this.state, this.props) : p;
		this.state = Object.assign({}, this.state, n);
		if (typeof this._schedule === 'function') this._schedule();
	};

	DCLogic.prototype.forceUpdate = function () {
		if (typeof this._schedule === 'function') this._schedule();
	};

	function liveNode(root) {
		var child = root.firstElementChild;
		while (child) {
			if (child.tagName !== 'TEMPLATE') return child;
			child = child.nextElementSibling;
		}
		return null;
	}

	function asset(root, path) {
		if (!path) return path;
		var base = (root && root.getAttribute('data-ph-base')) || '';
		if (String(path).indexOf('assets/') === 0) return base + String(path).slice(7);
		return path;
	}

	function mount(root, Ctor) {
		if (!root || root.nodeType !== 1) return;
		try {
			if (root._nexoraPhAbort && typeof root._nexoraPhAbort.abort === 'function') root._nexoraPhAbort.abort();
		} catch (e) {}

		var controller = null;
		var signal = null;
		if (typeof AbortController !== 'undefined') {
			try {
				controller = new AbortController();
				signal = controller.signal;
				root._nexoraPhAbort = controller;
			} catch (e2) {
				controller = null;
				signal = null;
			}
		}

		var tpl = root.querySelector('template.nexora-ph-tpl');
		if (!tpl || !tpl.content || !tpl.content.firstElementChild) return;
		var source = tpl.content.firstElementChild;
		var comp = new Ctor();
		comp.root = root;
		comp.signal = signal;
		comp.props = {};
		var dead = false;
		var pending = false;
		var offscreen = true;
		var offIO = null;

		function syncOff() {
			var live = liveNode(root);
			if (!live) return;
			if (live.tagName === 'SECTION' || live.tagName === 'FOOTER') live.classList.toggle('ph-off', offscreen);
		}

		function paint() {
			pending = false;
			if (dead) return;
			var scope = comp.renderVals();
			var out = [];
			render(source, scope, out);
			if (!out[0]) return;
			var current = liveNode(root);
			if (!current) {
				root.insertBefore(out[0], tpl);
			} else {
				keep(current, out[0]);
				current.replaceWith(out[0]);
			}
			syncOff();
		}

		comp._schedule = function () {
			if (dead || pending) return;
			pending = true;
			requestAnimationFrame(paint);
		};

		if (signal) {
			signal.addEventListener('abort', function () {
				dead = true;
				if (offIO) offIO.disconnect();
				if (typeof comp.componentWillUnmount === 'function') comp.componentWillUnmount();
			});
		}

		paint();

		if ('IntersectionObserver' in window) {
			offIO = new IntersectionObserver(
				function (ents) {
					ents.forEach(function (en) {
						if (en.target !== root) return;
						offscreen = !en.isIntersecting;
						syncOff();
					});
				},
				{ rootMargin: '200px 0px' }
			);
			offIO.observe(root);
		} else {
			offscreen = false;
			syncOff();
		}

		if (!dead && typeof comp.componentDidMount === 'function') comp.componentDidMount();
	}

	function boot(widgetName, selector, Ctor) {
		var hooked = false;

		function mountRoot(root) {
			if (root) mount(root, Ctor);
		}

		function initAll() {
			Array.prototype.forEach.call(document.querySelectorAll(selector), mountRoot);
		}

		function onReady($scope) {
			try {
				if (!$scope || !$scope[0] || !$scope[0].querySelector) return;
				var el = $scope[0];
				var root = el.matches && el.matches(selector) ? el : el.querySelector(selector);
				mountRoot(root);
			} catch (e) {}
		}

		function addHook() {
			if (typeof elementorFrontend === 'undefined' || !elementorFrontend.hooks) return false;
			elementorFrontend.hooks.addAction('frontend/element_ready/' + widgetName + '.default', onReady);
			return true;
		}

		if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initAll);
		else initAll();

		if (addHook()) return;

		if (hooked || typeof jQuery === 'undefined') return;
		hooked = true;
		jQuery(window).on('elementor/frontend/init', function () {
			try {
				addHook();
			} catch (e) {}
		});
	}

	window.NexoraPH = {
		mount: mount,
		boot: boot,
		DCLogic: DCLogic,
		asset: asset,
	};
})();
