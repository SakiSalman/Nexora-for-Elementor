(function () {
	'use strict';

	function qs(sel, root) {
		return (root || document).querySelector(sel);
	}

	function qsa(sel, root) {
		return Array.prototype.slice.call((root || document).querySelectorAll(sel));
	}

	function updateCard(card) {
		var input = qs('.nexora-ele-admin__switch input', card);
		if (!input) {
			return;
		}
		card.classList.toggle('is-enabled', input.checked);

		var status = qs('.nexora-ele-admin__status', card);
		if (status) {
			status.textContent = input.checked ? status.getAttribute('data-on') : status.getAttribute('data-off');
		}
	}

	function updateCount(root) {
		var inputs = qsa('.nexora-ele-admin__switch input', root);
		var enabled = inputs.filter(function (el) {
			return el.checked;
		}).length;
		var pill = qs('[data-nexora-enabled-count]', root);
		var total = qs('[data-nexora-total-count]', root);
		if (pill) {
			pill.textContent = String(enabled);
		}
		if (total) {
			total.textContent = String(inputs.length);
		}
	}

	function init() {
		var root = qs('.nexora-ele-admin');
		if (!root) {
			return;
		}

		qsa('.nexora-ele-admin__card', root).forEach(function (card) {
			updateCard(card);
			var input = qs('.nexora-ele-admin__switch input', card);
			if (input) {
				input.addEventListener('change', function () {
					updateCard(card);
					updateCount(root);
				});
			}
		});

		updateCount(root);

		var enableAll = qs('[data-nexora-enable-all]', root);
		var disableAll = qs('[data-nexora-disable-all]', root);

		if (enableAll) {
			enableAll.addEventListener('click', function () {
				qsa('.nexora-ele-admin__switch input', root).forEach(function (input) {
					input.checked = true;
					updateCard(input.closest('.nexora-ele-admin__card'));
				});
				updateCount(root);
			});
		}

		if (disableAll) {
			disableAll.addEventListener('click', function () {
				qsa('.nexora-ele-admin__switch input', root).forEach(function (input) {
					input.checked = false;
					updateCard(input.closest('.nexora-ele-admin__card'));
				});
				updateCount(root);
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
