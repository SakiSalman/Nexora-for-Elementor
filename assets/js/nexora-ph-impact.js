/* Prospects Hive GTM impact tabs. Tab copy comes from the saved repeater. */
(function () {
	'use strict';

	function readData(root) {
		var tpl = root && root.querySelector('template.nexora-ph-tpl');
		var node = tpl && tpl.content && tpl.content.querySelector('script.ph-impact-data');
		if (!node) return null;
		try {
			var data = JSON.parse(node.textContent || '');
			return data && typeof data === 'object' ? data : null;
		} catch (e) {
			return null;
		}
	}

	function NexoraPHImpact() {
		NexoraPH.DCLogic.call(this);
	}
	NexoraPHImpact.prototype = Object.create(NexoraPH.DCLogic.prototype);
	NexoraPHImpact.prototype.constructor = NexoraPHImpact;

	NexoraPHImpact.prototype.renderVals = function () {
		var self = this;
		var st = this.state || {};
		var data = readData(this.root) || { tabs: [] };
		var tabs = Array.isArray(data.tabs) ? data.tabs : [];
		var imp = st.imp || 0;
		if (imp < 0 || imp >= tabs.length) imp = 0;
		var current = tabs[imp] || {
			label: '',
			title: '',
			body: '',
			image: 'assets/impact.webp',
			alt: '',
			points: [],
		};

		return {
			impact: current,
			impactTabs: tabs.map(function (x, i) {
				return {
					label: x.label,
					cls: i === imp ? 'tab on' : 'tab',
					sel: i === imp ? 'true' : 'false',
					pick: function () {
						self.setState({ imp: i });
					},
				};
			}),
		};
	};

	NexoraPH.boot('ele-ph-impact', '[data-nexora-ph-impact]', NexoraPHImpact);
})();
