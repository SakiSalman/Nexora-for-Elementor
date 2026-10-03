<?php
/**
 * Canonical catalog of Nexora Elementor widgets.
 *
 * Add new widgets here only — bootstrap + settings read from this registry.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nexora_Ele_Widget_Registry', false ) ) {

	/**
	 * Widget registry.
	 */
	final class Nexora_Ele_Widget_Registry {

		/**
		 * Cached definitions.
		 *
		 * @var array<string, array<string, mixed>>|null
		 */
		private static $widgets = null;

		/**
		 * All widget definitions keyed by id.
		 *
		 * @return array<string, array<string, mixed>>
		 */
		public static function all(): array {
			if ( null !== self::$widgets ) {
				return self::$widgets;
			}

			$definitions = [
				'ele-gtm-funnel' => [
					'id'              => 'ele-gtm-funnel',
					'title'           => __( 'ELE GTM Funnel', 'nexora-elementor' ),
					'description'     => __( 'GTM strategy process cards with an interactive sales funnel.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-gtm-funnel.php',
					'class'           => 'Nexora_GTM_Funnel_Widget',
					'default_enabled' => true,
					'styles'          => [
						[
							'handle'   => 'nexora-plus-jakarta-font',
							'src'      => 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap',
							'deps'     => [],
							'ver'      => null,
							'external' => true,
						],
						[
							'handle' => 'nexora-ele-gtm-funnel',
							'src'    => 'assets/css/nexora-ele-gtm-funnel.css',
							'deps'   => [ 'nexora-plus-jakarta-font' ],
							'ver'    => true,
						],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ele-gtm-funnel',
							'src'       => 'assets/js/nexora-ele-gtm-funnel.js',
							'deps'      => [ 'jquery' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-timeline' => [
					'id'              => 'ele-timeline',
					'title'           => __( 'ELE Timeline', 'nexora-elementor' ),
					'description'     => __( 'Scroll-driven storytelling timeline with sticky image and progress track.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-timeline.php',
					'class'           => 'Nexora_Timeline_Widget',
					'default_enabled' => true,
					'styles'          => [
						[
							'handle'   => 'nexora-plus-jakarta-font',
							'src'      => 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap',
							'deps'     => [],
							'ver'      => null,
							'external' => true,
						],
						[
							'handle' => 'nexora-ele-timeline',
							'src'    => 'assets/css/nexora-ele-timeline.css',
							'deps'   => [ 'nexora-plus-jakarta-font' ],
							'ver'    => true,
						],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ele-timeline',
							'src'       => 'assets/js/nexora-ele-timeline.js',
							'deps'      => [ 'jquery' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-nav' => [
					'id'              => 'ele-ph-nav',
					'title'           => __( 'PH Nav', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive navigation with mega menu and mobile menu.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-nav.php',
					'class'           => 'Nexora_PH_Nav_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-nav',
						'src'    => 'assets/css/nexora-ph-nav.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-runtime',
							'src'       => 'assets/js/nexora-ph-runtime.js',
							'deps'      => [],
							'ver'       => true,
							'in_footer' => true,
						],
						[
							'handle'    => 'nexora-ph-nav',
							'src'       => 'assets/js/nexora-ph-nav.js',
							'deps'      => [ 'jquery', 'nexora-ph-runtime' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-hero' => [
					'id'              => 'ele-ph-hero',
					'title'           => __( 'PH Hero', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive hero with rating and robot artwork.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-hero.php',
					'class'           => 'Nexora_PH_Hero_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-hero',
						'src'    => 'assets/css/nexora-ph-hero.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
				'ele-ph-logos' => [
					'id'              => 'ele-ph-logos',
					'title'           => __( 'PH Logo Strip', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive logo marquee.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-logos.php',
					'class'           => 'Nexora_PH_Logos_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-logos',
						'src'    => 'assets/css/nexora-ph-logos.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
				'ele-ph-video' => [
					'id'              => 'ele-ph-video',
					'title'           => __( 'PH Video', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive video section.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-video.php',
					'class'           => 'Nexora_PH_Video_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-video',
						'src'    => 'assets/css/nexora-ph-video.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-video',
							'src'       => 'assets/js/nexora-ph-video.js',
							'deps'      => [ 'jquery' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-expertise' => [
					'id'              => 'ele-ph-expertise',
					'title'           => __( 'PH Expertise', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive expertise cards.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-expertise.php',
					'class'           => 'Nexora_PH_Expertise_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-expertise',
						'src'    => 'assets/css/nexora-ph-expertise.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-expertise',
							'src'       => 'assets/js/nexora-ph-expertise.js',
							'deps'      => [ 'jquery' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-challenge' => [
					'id'              => 'ele-ph-challenge',
					'title'           => __( 'PH Challenge', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive challenge comparison.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-challenge.php',
					'class'           => 'Nexora_PH_Challenge_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-challenge',
						'src'    => 'assets/css/nexora-ph-challenge.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
				'ele-ph-solutions' => [
					'id'              => 'ele-ph-solutions',
					'title'           => __( 'PH Solutions', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive solutions section.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-solutions.php',
					'class'           => 'Nexora_PH_Solutions_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-solutions',
						'src'    => 'assets/css/nexora-ph-solutions.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
				'ele-ph-framework' => [
					'id'              => 'ele-ph-framework',
					'title'           => __( 'PH GTM Framework', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive GTM framework tabs.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-framework.php',
					'class'           => 'Nexora_PH_Framework_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-framework',
						'src'    => 'assets/css/nexora-ph-framework.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-runtime',
							'src'       => 'assets/js/nexora-ph-runtime.js',
							'deps'      => [],
							'ver'       => true,
							'in_footer' => true,
						],
						[
							'handle'    => 'nexora-ph-framework',
							'src'       => 'assets/js/nexora-ph-framework.js',
							'deps'      => [ 'jquery', 'nexora-ph-runtime' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-process' => [
					'id'              => 'ele-ph-process',
					'title'           => __( 'PH Process', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive scroll process timeline.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-process.php',
					'class'           => 'Nexora_PH_Process_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-process',
						'src'    => 'assets/css/nexora-ph-process.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-process',
							'src'       => 'assets/js/nexora-ph-process.js',
							'deps'      => [ 'jquery' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-impact' => [
					'id'              => 'ele-ph-impact',
					'title'           => __( 'PH GTM Impact', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive GTM impact tabs.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-impact.php',
					'class'           => 'Nexora_PH_Impact_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-impact',
						'src'    => 'assets/css/nexora-ph-impact.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-runtime',
							'src'       => 'assets/js/nexora-ph-runtime.js',
							'deps'      => [],
							'ver'       => true,
							'in_footer' => true,
						],
						[
							'handle'    => 'nexora-ph-impact',
							'src'       => 'assets/js/nexora-ph-impact.js',
							'deps'      => [ 'jquery', 'nexora-ph-runtime' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-growth' => [
					'id'              => 'ele-ph-growth',
					'title'           => __( 'PH Growth System', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive growth system funnel.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-growth.php',
					'class'           => 'Nexora_PH_Growth_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-growth',
						'src'    => 'assets/css/nexora-ph-growth.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-growth',
							'src'       => 'assets/js/nexora-ph-growth.js',
							'deps'      => [ 'jquery' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-why' => [
					'id'              => 'ele-ph-why',
					'title'           => __( 'PH Why Choose', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive why choose section.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-why.php',
					'class'           => 'Nexora_PH_Why_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-why',
						'src'    => 'assets/css/nexora-ph-why.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
				'ele-ph-approach' => [
					'id'              => 'ele-ph-approach',
					'title'           => __( 'PH Approach', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive approach section.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-approach.php',
					'class'           => 'Nexora_PH_Approach_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-approach',
						'src'    => 'assets/css/nexora-ph-approach.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
				'ele-ph-cases' => [
					'id'              => 'ele-ph-cases',
					'title'           => __( 'PH Case Studies', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive case study carousel.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-cases.php',
					'class'           => 'Nexora_PH_Cases_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-cases',
						'src'    => 'assets/css/nexora-ph-cases.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-runtime',
							'src'       => 'assets/js/nexora-ph-runtime.js',
							'deps'      => [],
							'ver'       => true,
							'in_footer' => true,
						],
						[
							'handle'    => 'nexora-ph-cases',
							'src'       => 'assets/js/nexora-ph-cases.js',
							'deps'      => [ 'jquery', 'nexora-ph-runtime' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-industries' => [
					'id'              => 'ele-ph-industries',
					'title'           => __( 'PH Industries', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive industry tiles.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-industries.php',
					'class'           => 'Nexora_PH_Industries_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-industries',
						'src'    => 'assets/css/nexora-ph-industries.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-runtime',
							'src'       => 'assets/js/nexora-ph-runtime.js',
							'deps'      => [],
							'ver'       => true,
							'in_footer' => true,
						],
						[
							'handle'    => 'nexora-ph-industries',
							'src'       => 'assets/js/nexora-ph-industries.js',
							'deps'      => [ 'jquery', 'nexora-ph-runtime' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-tech' => [
					'id'              => 'ele-ph-tech',
					'title'           => __( 'PH Tech Stack', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive tech stack marquee.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-tech.php',
					'class'           => 'Nexora_PH_Tech_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-tech',
						'src'    => 'assets/css/nexora-ph-tech.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
				'ele-ph-pricing' => [
					'id'              => 'ele-ph-pricing',
					'title'           => __( 'PH Pricing', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive pricing tabs.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-pricing.php',
					'class'           => 'Nexora_PH_Pricing_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-pricing',
						'src'    => 'assets/css/nexora-ph-pricing.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-runtime',
							'src'       => 'assets/js/nexora-ph-runtime.js',
							'deps'      => [],
							'ver'       => true,
							'in_footer' => true,
						],
						[
							'handle'    => 'nexora-ph-pricing',
							'src'       => 'assets/js/nexora-ph-pricing.js',
							'deps'      => [ 'jquery', 'nexora-ph-runtime' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-stories' => [
					'id'              => 'ele-ph-stories',
					'title'           => __( 'PH Success Stories', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive testimonial carousel.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-stories.php',
					'class'           => 'Nexora_PH_Stories_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-stories',
						'src'    => 'assets/css/nexora-ph-stories.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-stories',
							'src'       => 'assets/js/nexora-ph-stories.js',
							'deps'      => [ 'jquery' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-partners' => [
					'id'              => 'ele-ph-partners',
					'title'           => __( 'PH Partners', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive partner badges.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-partners.php',
					'class'           => 'Nexora_PH_Partners_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-partners',
						'src'    => 'assets/css/nexora-ph-partners.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
				'ele-ph-faq' => [
					'id'              => 'ele-ph-faq',
					'title'           => __( 'PH FAQ', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive FAQ accordion.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-faq.php',
					'class'           => 'Nexora_PH_Faq_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-faq',
						'src'    => 'assets/css/nexora-ph-faq.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ph-faq',
							'src'       => 'assets/js/nexora-ph-faq.js',
							'deps'      => [ 'jquery' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-ph-contact' => [
					'id'              => 'ele-ph-contact',
					'title'           => __( 'PH Contact', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive contact section.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-contact.php',
					'class'           => 'Nexora_PH_Contact_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-contact',
						'src'    => 'assets/css/nexora-ph-contact.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
				'ele-ph-insights' => [
					'id'              => 'ele-ph-insights',
					'title'           => __( 'PH Insights', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive insights section.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-insights.php',
					'class'           => 'Nexora_PH_Insights_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-insights',
						'src'    => 'assets/css/nexora-ph-insights.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
				'ele-ph-cta' => [
					'id'              => 'ele-ph-cta',
					'title'           => __( 'PH Final CTA', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive final call to action.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-cta.php',
					'class'           => 'Nexora_PH_Cta_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-cta',
						'src'    => 'assets/css/nexora-ph-cta.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
				'ele-ph-footer' => [
					'id'              => 'ele-ph-footer',
					'title'           => __( 'PH Footer', 'nexora-elementor' ),
					'description'     => __( 'Prospects Hive footer.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-ph-footer.php',
					'class'           => 'Nexora_PH_Footer_Widget',
					'default_enabled' => true,
					'styles'          => [
					[
						'handle'   => 'nexora-ph-fonts',
						'src'      => 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap',
						'deps'     => [],
						'ver'      => null,
						'external' => true,
					],
					[
						'handle' => 'nexora-ph-shared',
						'src'    => 'assets/css/nexora-ph-shared.css',
						'deps'   => [ 'nexora-ph-fonts' ],
						'ver'    => true,
					],
					[
						'handle' => 'nexora-ph-footer',
						'src'    => 'assets/css/nexora-ph-footer.css',
						'deps'   => [ 'nexora-ph-shared' ],
						'ver'    => true,
					],
					],
					'scripts'         => [],
				],
			];

			/**
			 * Filter the Nexora widget registry.
			 *
			 * @param array<string, array<string, mixed>> $definitions Widget definitions.
			 */
			self::$widgets = apply_filters( 'nexora_ele_widget_registry', $definitions );

			return self::$widgets;
		}

		/**
		 * Single widget definition.
		 *
		 * @param string $id Widget id.
		 * @return array<string, mixed>|null
		 */
		public static function get( string $id ): ?array {
			$all = self::all();
			return isset( $all[ $id ] ) && is_array( $all[ $id ] ) ? $all[ $id ] : null;
		}

		/**
		 * Widget ids.
		 *
		 * @return string[]
		 */
		public static function ids(): array {
			return array_keys( self::all() );
		}
	}
}
