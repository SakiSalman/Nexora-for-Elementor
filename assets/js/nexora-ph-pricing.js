/* Prospects Hive pricing tabs. */
(function () {
	'use strict';

	var PLANS = ['GTM Foundation', 'GTM Growth Engine', 'Custom GTM'];

	function NexoraPHPricing() {
		NexoraPH.DCLogic.call(this);
	}
	NexoraPHPricing.prototype = Object.create(NexoraPH.DCLogic.prototype);
	NexoraPHPricing.prototype.constructor = NexoraPHPricing;

	NexoraPHPricing.prototype.renderVals = function () {
		var self = this;
		var st = this.state || {};
		var plan = st.plan || 0;
		return {
			plans: PLANS.map(function (l, i) {
				return {
					label: l,
					cls: i === plan ? 'tab on' : 'tab',
					sel: i === plan ? 'true' : 'false',
					pick: function () {
						self.setState({ plan: i });
					},
				};
			}),
			isFoundation: plan === 0,
			isOther: plan !== 0,
			curPlan: PLANS[plan],
		};
	};

	NexoraPH.boot('ele-ph-pricing', '[data-nexora-ph-pricing]', NexoraPHPricing);
})();
