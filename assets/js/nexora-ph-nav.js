/* Prospects Hive nav. Scroll hide/solid and mega-menu state from bindNav().
   Timeline, card hover, FAQ, growth, and testimonials stay on their own widgets.
   Hash links resolve [data-ph-anchor] because section ids are suffixed per instance. */
(function () {
	'use strict';

	function NexoraPHNav() {
		NexoraPH.DCLogic.call(this);
	}
	NexoraPHNav.prototype = Object.create(NexoraPH.DCLogic.prototype);
	NexoraPHNav.prototype.constructor = NexoraPHNav;

	NexoraPHNav.prototype.componentDidMount = function () {
		var self = this;
		var root = this.root;
		var signal = this.signal;
		this.onKey = function (e) {
			if (e.key === 'Escape' && self.state && self.state.mega) self.setState({ mega: false });
		};
		document.addEventListener('keydown', this.onKey);
		var last = 0;
		this.onScroll = function (e) {
			var t = e && e.target;
			var el = !t || t === document || t === document.documentElement || t === document.body ? document.scrollingElement || document.documentElement : t;
			if (!el || typeof el.scrollTop !== 'number' || el.scrollHeight - el.clientHeight < 200) return;
			var y = el.scrollTop;
			var dy = y - last;
			var navEl = root.querySelector('header.nav');
			if (navEl) navEl.classList.toggle('nav-solid', y > 24);
			if (Math.abs(dy) < 6) return;
			last = y;
			var st = self.state || {};
			var nav = root.querySelector('header.nav');
			if (!nav) return;
			var heroEl = document.querySelector('.hero-sec');
			var heroH = heroEl ? heroEl.offsetHeight : 700;
			var hide = dy > 0 && y > heroH - 90 && !st.mega && !st.menu;
			nav.classList.toggle('nav-hide', hide);
			nav.classList.toggle('nav-solid', y > 24);
		};
		window.addEventListener('scroll', this.onScroll, true);
		this.onAnchor = function (e) {
			var a = e.target && e.target.closest ? e.target.closest('a[href^="#"]') : null;
			if (!a || !root.contains(a)) return;
			var hash = a.getAttribute('href') || '';
			var id = hash.charAt(0) === '#' ? hash.slice(1) : '';
			if (!id) return;
			var escaped = window.CSS && CSS.escape ? CSS.escape(id) : id.replace(/"/g, '');
			var target = document.querySelector('[data-ph-anchor="' + escaped + '"]');
			if (!target) return;
			e.preventDefault();
			target.scrollIntoView();
		};
		root.addEventListener('click', this.onAnchor);
		if (signal) {
			signal.addEventListener('abort', function () {
				self.componentWillUnmount();
			});
		}
	};

	NexoraPHNav.prototype.componentWillUnmount = function () {
		if (this._unbound) return;
		this._unbound = true;
		if (this.onKey) document.removeEventListener('keydown', this.onKey);
		if (this.onScroll) window.removeEventListener('scroll', this.onScroll, true);
		if (this.onAnchor && this.root) this.root.removeEventListener('click', this.onAnchor);
	};

	NexoraPHNav.prototype.renderVals = function () {
		var st = this.state || {};
		return {
			menuOpen: !!st.menu,
			menuExpanded: st.menu ? 'true' : 'false',
			toggleMenu: function () {
				this.setState({ menu: !st.menu });
			}.bind(this),
			closeMenu: function () {
				this.setState({ menu: false, msvc: false });
			}.bind(this),
			megaOpen: !!st.mega,
			megaExpanded: st.mega ? 'true' : 'false',
			megaCls: st.mega ? 'navlink navbtn on' : 'navlink navbtn',
			toggleMega: function () {
				this.setState({ mega: !st.mega });
			}.bind(this),
			closeMega: function () {
				if (this.state && this.state.mega) this.setState({ mega: false });
			}.bind(this),
			openMega: function () {
				if (!(this.state && this.state.mega)) this.setState({ mega: true });
			}.bind(this),
			mSvcOpen: !!st.msvc,
			mSvcExpanded: st.msvc ? 'true' : 'false',
			mSvcCls: st.msvc ? 'm-link on' : 'm-link',
			toggleMSvc: function () {
				this.setState({ msvc: !st.msvc });
			}.bind(this),
		};
	};

	NexoraPH.boot('ele-ph-nav', '[data-nexora-ph-nav]', NexoraPHNav);
})();
