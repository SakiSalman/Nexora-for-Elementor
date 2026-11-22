<?php
/**
 * Default content for the PH Partners "B2B badge cards" template style.
 * Design copy from the B2B Lead Generation export (B2B12 OUR OFFICIAL PARTNERS).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_partners_b2b_defaults' ) ) {
	/**
	 * The design defaults. Badge images are plugin files under assets/images/prospects/.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_partners_b2b_defaults(): array {
		$img = static function ( string $file ): array {
			return array(
				'url' => NEXORA_ELE_URL . 'assets/images/prospects/' . $file,
				'id'  => '',
			);
		};

		return array(
			'heading_tag' => 'h2',
			'eyebrow'     => 'Partners',
			'heading'     => 'Our official',
			'highlight'   => 'partners',
			'description' => 'We are certified partners of the outbound platforms we use every day, so your campaigns run on tools we know inside out.',
			'cards'       => array(
				array(
					'_id'          => 'pb_instantly',
					'card_image'   => $img( 'b2b-badge-instantly.webp' ),
					'card_alt'     => 'Instantly certified partner badge',
					'card_name'    => 'Instantly',
					'card_chip'    => 'Certified partner',
					'card_summary' => 'Cold email sending and warm-up',
				),
				array(
					'_id'          => 'pb_heyreach',
					'card_image'   => $img( 'b2b-badge-heyreach.webp' ),
					'card_alt'     => 'HeyReach certified partner badge',
					'card_name'    => 'HeyReach',
					'card_chip'    => 'Certified partner',
					'card_summary' => 'LinkedIn outreach at scale',
				),
				array(
					'_id'          => 'pb_getsales',
					'card_image'   => $img( 'b2b-badge-getsales.webp' ),
					'card_alt'     => 'GetSales.io certified partner badge',
					'card_name'    => 'GetSales.io',
					'card_chip'    => 'Certified partner',
					'card_summary' => 'Multichannel sales automation',
				),
				array(
					'_id'          => 'pb_lemlist',
					'card_image'   => $img( 'b2b-badge-lemlist.webp' ),
					'card_alt'     => 'lemlist certified partner badge',
					'card_name'    => 'lemlist',
					'card_chip'    => 'Certified partner',
					'card_summary' => 'Personalized outreach sequences',
				),
				array(
					'_id'          => 'pb_apollo',
					'card_image'   => $img( 'b2b-badge-apollo.webp' ),
					'card_alt'     => 'Apollo.io certified partner badge',
					'card_name'    => 'Apollo.io',
					'card_chip'    => 'Certified partner',
					'card_summary' => 'B2B data and prospecting',
				),
			),
		);
	}
}
