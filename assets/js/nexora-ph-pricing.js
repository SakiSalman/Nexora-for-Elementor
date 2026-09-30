/* Prospects Hive pricing tabs. Plan copy comes from the saved repeater. */
(function () {
	'use strict';

	function readData(root) {
		var tpl = root && root.querySelector('template.nexora-ph-tpl');
		var node = tpl && tpl.content && tpl.content.querySelector('script.ph-pricing-data');
		if (!node) return null;
		try {
			var data = JSON.parse(node.textContent || '');
			return data && typeof data === 'object' ? data : null;
		} catch (e) {
			return null;
		}
	}

	function NexoraPHPricing() {
		NexoraPH.DCLogic.call(this);
	}
	NexoraPHPricing.prototype = Object.create(NexoraPH.DCLogic.prototype);
	NexoraPHPricing.prototype.constructor = NexoraPHPricing;

	NexoraPHPricing.prototype.renderVals = function () {
		var self = this;
		var st = this.state || {};
		var data = readData(this.root) || { plans: [] };
		var plans = Array.isArray(data.plans) ? data.plans : [];
		var plan = st.plan || 0;
		if (plan < 0 || plan >= plans.length) plan = 0;
		var current = plans[plan] || null;

		return Object.assign({}, data, {
			plans: plans.map(function (item, i) {
				return Object.assign({}, item, {
					cls: i === plan ? 'tab on' : 'tab',
					sel: i === plan ? 'true' : 'false',
					show: i === plan,
					pick: function () {
						self.setState({ plan: i });
					},
				});
			}),
			showAfter: !current || current.template !== 'custom',
		});
	};

	NexoraPH.boot('ele-ph-pricing', '[data-nexora-ph-pricing]', NexoraPHPricing);
})();
