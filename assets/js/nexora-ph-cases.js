/* Prospects Hive case studies. Slide copy comes from the saved repeater. 6s carousel from startCs(). */
(function () {
	'use strict';

	function readData(root) {
		var tpl = root && root.querySelector('template.nexora-ph-tpl');
		var node = tpl && tpl.content && tpl.content.querySelector('script.ph-cases-data');
		if (!node) return null;
		try {
			var data = JSON.parse(node.textContent || '');
			return data && typeof data === 'object' ? data : null;
		} catch (e) {
			return null;
		}
	}

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
		var root = this.root;
		var data = readData(root) || { cases: [] };
		var rows = Array.isArray(data.cases) ? data.cases : [];
		return rows.map(function (item) {
			var img = item && item.img ? String(item.img) : '';
			var hasImg = !!(item && item.hasImg && img);
			return Object.assign({}, item, {
				hasImg: hasImg,
				img: hasImg ? NexoraPH.asset(root, img) : '',
			});
		});
	};

	NexoraPHCases.prototype.startCs = function () {
		var self = this;
		if (this.csTimer) clearInterval(this.csTimer);
		this.csTimer = setInterval(function () {
			var box = self.root.querySelector('section');
			if (box && box.matches(':hover')) return;
			var cases = self.list();
			var n = cases.length;
			if (!n) return;
			var cur = (self.state && self.state.cs) || 0;
			self.setState({ cs: (cur + 1) % n });
		}, 6000);
	};

	NexoraPHCases.prototype.renderVals = function () {
		var self = this;
		var cases = this.list();
		var st = this.state || {};
		var cs = st.cs || 0;
		var n = cases.length;
		if (cs < 0 || cs >= n) cs = 0;
		var c = cases[cs] || {
			title: '',
			summary: '',
			client: '',
			industry: '',
			services: '',
			img: '',
			alt: '',
			hasImg: false,
			buttonText: '',
			buttonUrl: '#',
			buttonTarget: false,
			buttonRel: false,
			stats: [],
		};
		return {
			cs: Object.assign({ noImg: !c.hasImg }, c),
			prevCase: function () {
				if (!n) return;
				self.setState({ cs: (cs + n - 1) % n });
				self.startCs();
			},
			nextCase: function () {
				if (!n) return;
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
