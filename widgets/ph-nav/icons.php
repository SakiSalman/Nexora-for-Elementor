<?php
/**
 * Original nav icons, in desktop card order then other-services order.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_nav_seed_icons' ) ) {
	/**
	 * Seed SVGs. Index 12 is unused by defaults. Index 9 is the shared fallback.
	 *
	 * @return array<int, string>
	 */
	function nexora_ph_nav_seed_icons(): array {
		return [
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link, #C2410C)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 2L11 13"></path><path d="M22 2l-7 20-4-9-9-4z"></path></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link, #C2410C)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="3"></rect><path d="M8 11v5M8 8v.01M12 16v-5M16 16v-3a2 2 0 0 0-4 0"></path></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link, #C2410C)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M3 7l9 6 9-6"></path></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link, #C2410C)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="5"></circle><circle cx="12" cy="12" r="1.5"></circle></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link, #C2410C)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="8" ry="3"></ellipse><path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"></path></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link, #C2410C)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><path d="M14 3v6h6M8 13h8M8 17h5"></path></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link, #C2410C)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2L3 14h8l-1 8 10-12h-8z"></path></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link, #C2410C)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 11l14-6v14L3 13z"></path><path d="M7 13v5a2 2 0 0 0 4 0v-3.5"></path><path d="M20 9v6"></path></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link, #C2410C)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"></path></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link-blue, #1A5A87)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link-blue, #1A5A87)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"></circle><circle cx="5" cy="5" r="2"></circle><circle cx="19" cy="5" r="2"></circle><circle cx="5" cy="19" r="2"></circle><circle cx="19" cy="19" r="2"></circle><path d="M7 7l3 3M17 7l-3 3M7 17l3-3M17 17l-3-3"></path></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link-blue, #1A5A87)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"></path><path d="M7 15l4-4 3 3 5-6"></path></svg>',
		'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ph-link, #C2410C)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 2L11 13"></path><path d="M22 2l-7 20-4-9-9-4z"></path></svg>',
		];
	}
}
