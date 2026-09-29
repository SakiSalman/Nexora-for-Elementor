/* Prospects Hive case studies. 6s carousel from startCs(). Image paths use data-ph-base. */
(function () {
	'use strict';

	var CASES = [
		{
			hasImg: true,
			img: 'assets/case1.webp',
			alt: 'Dual-track deal pipeline diagram',
			title: 'Building a dual-track deal pipeline for a boutique M&A firm',
			summary: 'We built a fully managed LinkedIn outbound system that engaged 18,260 prospects across two tracks at once: deal flow and capital partners.',
			client: 'Boutique investment management firm',
			industry: 'M&A advisory (lower middle market)',
			services: 'LinkedIn outreach & CRM optimisation',
			stats: [
				{ v: '18,260', l: 'Prospects engaged' },
				{ v: '45', l: 'Meetings booked' },
				{ v: '19.6%', l: 'Reply rate' },
			],
		},
		{
			hasImg: true,
			img: 'assets/case2.webp',
			alt: 'AI voice verification workflow diagram',
			title: 'Eliminating manual verification calls for an insurance factoring firm',
			summary: 'A fully automated AI voice system now handles every pre-funding verification call and updates the CRM in real time, with zero staff hours needed.',
			client: 'National Claims Funding',
			industry: 'Insurance receivables factoring',
			services: 'AI voice automation & CRM integration',
			stats: [
				{ v: '100%', l: 'Calls automated' },
				{ v: '0 min', l: 'Staff time per call' },
				{ v: '24/7', l: 'Call capacity' },
			],
		},
		{
			hasImg: true,
			img: 'assets/case3.webp',
			alt: 'Investor meeting pipeline diagram',
			title: 'Booking 25 qualified meetings to support a $1M investment round',
			summary: 'A conversation-first outbound system that turned high-intent replies into qualified investor meetings, inside a fixed fundraising window.',
			client: 'High-growth startup seeking investment',
			industry: '[Industry]',
			services: 'Signal-based outbound & reply qualification',
			stats: [
				{ v: '25', l: 'Qualified meetings' },
				{ v: '$1M', l: 'Round secured' },
				{ v: '43.2%', l: 'Avg. open rate' },
			],
		},
	];

	function NexoraPHCases() {
		NexoraPH.DCLogic.call(this);
	}
	NexoraPHCases.prototype = Object.create(NexoraPH.DCLogic.prototype);
	NexoraPHCases.prototype.constructor = NexoraPHCases;

	NexoraPHCases.prototype.componentDidMount = function () {
		this.startCs();
	};

	NexoraPHCases.prototype.componentWillUnmount = function () {
		if (this.csTimer) clearInterval(this.csTimer);
		this.csTimer = null;
	};

	NexoraPHCases.prototype.list = function () {
		if (!this.cases) {
			var root = this.root;
			this.cases = CASES.map(function (item) {
				return Object.assign({}, item, { img: NexoraPH.asset(root, item.img) });
			});
		}
		return this.cases;
	};

	NexoraPHCases.prototype.startCs = function () {
		var self = this;
		if (this.csTimer) clearInterval(this.csTimer);
		this.csTimer = setInterval(function () {
			var box = self.root.querySelector('section');
			if (box && box.matches(':hover')) return;
			var cur = (self.state && self.state.cs) || 0;
			self.setState({ cs: (cur + 1) % self.list().length });
		}, 6000);
	};

	NexoraPHCases.prototype.renderVals = function () {
		var self = this;
		var cases = this.list();
		var st = this.state || {};
		var cs = st.cs || 0;
		var n = cases.length;
		var c = cases[cs];
		return {
			cs: Object.assign({ noImg: !c.hasImg }, c),
			prevCase: function () {
				self.setState({ cs: (cs + n - 1) % n });
				self.startCs();
			},
			nextCase: function () {
				self.setState({ cs: (cs + 1) % n });
				self.startCs();
			},
			caseDots: cases.map(function (x, i) {
				return {
					cls: i === cs ? 'dot on' : 'dot',
					label: 'Show case study ' + (i + 1),
					pick: function () {
						self.setState({ cs: i });
					},
				};
			}),
		};
	};

	NexoraPH.boot('ele-ph-cases', '[data-nexora-ph-cases]', NexoraPHCases);
})();
