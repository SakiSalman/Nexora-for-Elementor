/* Prospects Hive GTM framework. 7s tab timer from startFw(). */
(function () {
	'use strict';

	var FW = [
		{ label: 'Research, Strategy & Data', sub: 'Find the right buyers' },
		{ label: 'Multi-Channel Deployment', sub: 'Start real conversations' },
		{ label: 'Sales Agents & RevOps', sub: 'Turn replies into revenue' },
	];

	function NexoraPHFramework() {
		NexoraPH.DCLogic.call(this);
	}
	NexoraPHFramework.prototype = Object.create(NexoraPH.DCLogic.prototype);
	NexoraPHFramework.prototype.constructor = NexoraPHFramework;

	NexoraPHFramework.prototype.componentDidMount = function () {
		this.startFw();
	};

	NexoraPHFramework.prototype.componentWillUnmount = function () {
		if (this.fwTimer) clearInterval(this.fwTimer);
		this.fwTimer = null;
	};

	NexoraPHFramework.prototype.startFw = function () {
		var self = this;
		if (this.fwTimer) clearInterval(this.fwTimer);
		this.fwTimer = setInterval(function () {
			var cur = (self.state && self.state.fw) || 0;
			self.setState({ fw: (cur + 1) % FW.length });
		}, 7000);
	};

	NexoraPHFramework.prototype.renderVals = function () {
		var self = this;
		var st = this.state || {};
		var fw = st.fw || 0;
		return {
			fwTabs: FW.map(function (x, i) {
				return {
					label: x.label,
					sub: x.sub,
					no: '0' + (i + 1),
					cls: i === fw ? 'ftab on' : 'ftab',
					sel: i === fw ? 'true' : 'false',
					pick: function () {
						self.setState({ fw: i });
						self.startFw();
					},
				};
			}),
			fw0: fw === 0,
			fw1: fw === 1,
			fw2: fw === 2,
		};
	};

	NexoraPH.boot('ele-ph-framework', '[data-nexora-ph-framework]', NexoraPHFramework);
})();
