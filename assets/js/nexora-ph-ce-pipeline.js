/* Cold Email B2B pipeline. Stage data comes from the JSON script; nexora-ph-runtime.js renders
   the {{ }} / sc-for markup. Auto-advance fires when the .pl-line progress animation ends (7 s),
   same as the design's startPl/plVals. */
(function () {
	'use strict';

	var SELECTOR = '[data-nexora-ph-ce-pipeline]';

	function readData(root) {
		var tpl = root && root.querySelector('template.nexora-ph-tpl');
		var node = tpl && tpl.content && tpl.content.querySelector('script.ph-ce-pipeline-data');
		if (!node) return null;
		try {
			var data = JSON.parse(node.textContent || '');
			if (Array.isArray(data)) return data;
			return data && Array.isArray(data.stages) ? data.stages : null;
		} catch (e) {
			return null;
		}
	}

	function NexoraPHCEPipeline() {
		NexoraPH.DCLogic.call(this);
	}
	NexoraPHCEPipeline.prototype = Object.create(NexoraPH.DCLogic.prototype);
	NexoraPHCEPipeline.prototype.constructor = NexoraPHCEPipeline;

	NexoraPHCEPipeline.prototype.plVals = function () {
		var self = this;
		var st = this.state || {};
		var stages = this.stages || [];
		var k = st.pl || 0;
		if (k < 0 || k >= stages.length) k = 0;
		var v = {
			plTrack: 'pl-track pl-f' + k,
			plCur: Object.assign({ no: '0' + (k + 1) }, stages[k] || { title: '', body: '', points: [] })
		};
		stages.forEach(function (x, i) {
			v['pl' + i] = {
				cls: 'pl-node' + (i === k ? ' on' : i < k ? ' done' : ''),
				sel: i === k ? 'true' : 'false',
				pick: function () {
					self.setState({ pl: i });
					self.startPl();
				}
			};
		});
		return v;
	};

	NexoraPHCEPipeline.prototype.renderVals = function () {
		this.stages = readData(this.root) || [];
		return this.plVals();
	};

	NexoraPHCEPipeline.prototype.startPl = function () {
		// the design bound one document-level animationend; we bind on this widget's root instead
	};

	NexoraPHCEPipeline.prototype.componentDidMount = function () {
		var self = this;
		var root = this.root;
		if (!root) return;
		// rebind on every mount: the runtime aborts the previous signal on editor re-render,
		// so a bound-once guard would lose the listener
		var opts = this.signal ? { capture: true, signal: this.signal } : true;
		root.addEventListener(
			'animationend',
			function (e) {
				var t = e.target;
				if (!t || !t.matches || !t.matches('.pl-line span')) return;
				var stages = self.stages || [];
				if (!stages.length) return;
				var cur = (self.state && self.state.pl) || 0;
				self.setState({ pl: (cur + 1) % stages.length });
			},
			opts
		);
	};

	NexoraPHCEPipeline.prototype.componentWillUnmount = function () {
		if (this.plTimer) clearInterval(this.plTimer);
	};

	NexoraPH.boot('ele-ph-ce-pipeline', SELECTOR, NexoraPHCEPipeline);
})();
