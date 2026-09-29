/* Prospects Hive GTM impact tabs. */
(function () {
	'use strict';

	var IMPACT = [
		{
			label: 'Better Targeting',
			title: 'Find the accounts that matter',
			body: 'A strong GTM system starts with knowing who to target. We help B2B teams focus on high-fit accounts using ICP development, account research and buyer insights.',
			points: ['Clear ideal customer profiles and target segments', 'Prioritised accounts based on fit and opportunity', 'More focused outreach toward relevant prospects', 'Better alignment between marketing and sales'],
		},
		{
			label: 'Relevant Engagement',
			title: 'Reach buyers with messages that land',
			body: 'Outreach works when it feels timely and personal. We shape every touch around the buyer, their role and what is happening in their business.',
			points: ['Messaging shaped by role, pains and timing', 'Outreach triggered by real buying signals', 'Email and LinkedIn working as one conversation', 'More replies from the right people'],
		},
		{
			label: 'Scalable Execution',
			title: 'Grow outreach without growing headcount',
			body: 'Automation and repeatable plays let you do more without adding more people or more manual work.',
			points: ['Automated research, enrichment and follow-ups', 'Repeatable plays your team can run', 'Safe sending across domains and profiles', 'More campaigns without more busywork'],
		},
		{
			label: 'Revenue Visibility',
			title: 'See exactly what drives pipeline',
			body: 'When outreach, CRM and reporting are connected, you always know what is working and where your next deals are coming from.',
			points: ['Every reply and meeting tracked in your CRM', 'Stage-by-stage reporting from outreach to revenue', 'A clear view of which plays and segments win', 'Faster decisions backed by real data'],
		},
		{
			label: 'Predictable Growth',
			title: 'Build a pipeline you can plan around',
			body: 'A connected system turns outbound from a guessing game into a steady, measurable source of new business.',
			points: ['A steady flow of qualified meetings', 'Continuous testing and improvement', 'A documented system your team owns', 'Forecasts built on consistent inputs'],
		},
	];

	function NexoraPHImpact() {
		NexoraPH.DCLogic.call(this);
	}
	NexoraPHImpact.prototype = Object.create(NexoraPH.DCLogic.prototype);
	NexoraPHImpact.prototype.constructor = NexoraPHImpact;

	NexoraPHImpact.prototype.renderVals = function () {
		var self = this;
		var st = this.state || {};
		var imp = st.imp || 0;
		return {
			impact: IMPACT[imp],
			impactTabs: IMPACT.map(function (x, i) {
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
