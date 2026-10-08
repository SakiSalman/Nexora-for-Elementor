/* Prospects Hive pricing tabs. Plan copy comes from the saved repeater. Availability sticky notes rotate one at a time. */
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

	NexoraPHPricing.prototype.componentDidMount = function () {
		this.startSpots();
	};

	NexoraPHPricing.prototype.componentWillUnmount = function () {
		if (this.spotTimer) clearInterval(this.spotTimer);
		this.spotTimer = null;
	};

	NexoraPHPricing.prototype.startSpots = function () {
		var self = this;
		if (this.spotTimer) clearInterval(this.spotTimer);
		this.spotTimer = null;
		var data = readData(this.root) || {};
		var spots = Array.isArray(data.spots) ? data.spots : [];
		var interval = parseInt(data.spotsInterval, 10) || 4;
		if (!data.spotsRotate || spots.length < 2) return;
		if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
		this.spotTimer = setInterval(function () {
			var box = self.root.querySelector('section');
			if (box && box.matches(':hover')) return;
			var cur = (self.state && self.state.spot) || 0;
			self.setState({ spot: (cur + 1) % spots.length });
		}, interval * 1000);
	};

	NexoraPHPricing.prototype.renderVals = function () {
		var self = this;
		var st = this.state || {};
		var data = readData(this.root) || { plans: [] };
		var plans = Array.isArray(data.plans) ? data.plans : [];
		var plan = st.plan || 0;
		if (plan < 0 || plan >= plans.length) plan = 0;
		var current = plans[plan] || null;
		var spots = Array.isArray(data.spots) ? data.spots : [];
		var spot = st.spot || 0;
		if (spot < 0 || spot >= spots.length) spot = 0;

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
			spots: spots.map(function (item, i) {
				return Object.assign({}, item, {
					cls: i === spot ? ' on' : '',
				});
			}),
		});
	};

	NexoraPH.boot('ele-ph-pricing', '[data-nexora-ph-pricing]', NexoraPHPricing);
})();
