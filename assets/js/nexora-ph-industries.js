/* Prospects Hive industries. Tile index state, plus the pointer spotlight from bindExtras(). */
(function () {
	'use strict';

	function NexoraPHIndustries() {
		NexoraPH.DCLogic.call(this);
	}
	NexoraPHIndustries.prototype = Object.create(NexoraPH.DCLogic.prototype);
	NexoraPHIndustries.prototype.constructor = NexoraPHIndustries;

	NexoraPHIndustries.prototype.componentDidMount = function () {
		var root = this.root;
		var signal = this.signal;
		var SEL = '.ind-tile';
		function setPt(el, e) {
			var r = el.getBoundingClientRect();
			el.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100).toFixed(1) + '%');
			el.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100).toFixed(1) + '%');
		}
		function onOver(e) {
			var c = e.target.closest && e.target.closest(SEL);
			if (c && root.contains(c) && !(e.relatedTarget && c.contains(e.relatedTarget))) setPt(c, e);
		}
		root.addEventListener('pointerover', onOver, signal ? { capture: true, signal: signal } : true);
		root.addEventListener('pointerout', onOver, signal ? { capture: true, signal: signal } : true);
	};

	NexoraPHIndustries.prototype.renderVals = function () {
		var self = this;
		var st = this.state || {};
		var cur = st.ind == null ? 0 : st.ind;
		var o = {};
		for (var i = 0; i < 8; i++) {
			(function (i) {
				o['c' + i] = i === cur ? 'ind-tile reveal on' : 'ind-tile reveal';
				o['a' + i] = i === cur ? 'true' : 'false';
				o['p' + i] = function (e) {
					if (e && e.preventDefault) e.preventDefault();
					self.setState({ ind: i });
				};
			})(i);
		}
		return { ind: o };
	};

	NexoraPH.boot('ele-ph-industries', '[data-nexora-ph-industries]', NexoraPHIndustries);
})();
