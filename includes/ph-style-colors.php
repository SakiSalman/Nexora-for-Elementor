<?php
/**
 * Prospects Hive Style > Colors registrar.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

require_once __DIR__ . '/ph-style-colors/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_register_style_colors' ) ) {
	/**
	 * Register color controls from a PH widget inventory.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 * @param string                 $slug   Widget slug, such as hero or nav.
	 */
	function nexora_ph_register_style_colors( $widget, string $slug ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		$slug = sanitize_key( $slug );
		if ( '' === $slug ) {
			return;
		}

		$path = __DIR__ . '/ph-style-colors/' . $slug . '.php';
		if ( ! is_readable( $path ) ) {
			return;
		}

		require_once $path;

		$inventory_function = 'nexora_ph_style_colors_inventory_' . str_replace( '-', '_', $slug );
		if ( ! function_exists( $inventory_function ) ) {
			return;
		}

		$inventory = $inventory_function();
		if ( ! is_array( $inventory ) ) {
			return;
		}

		$solids     = isset( $inventory['solids'] ) && is_array( $inventory['solids'] ) ? $inventory['solids'] : [];
		$gradients  = isset( $inventory['gradients'] ) && is_array( $inventory['gradients'] ) ? $inventory['gradients'] : [];
		$use_shared = ! empty( $inventory['include_shared'] );

		if ( $use_shared ) {
			$shared = nexora_ph_style_colors_shared_catalog();
			$solids = array_merge(
				isset( $shared['solids'] ) && is_array( $shared['solids'] ) ? $shared['solids'] : [],
				$solids
			);
			$gradients = array_merge(
				isset( $shared['gradients'] ) && is_array( $shared['gradients'] ) ? $shared['gradients'] : [],
				$gradients
			);
		}

		$deduped_solids = [];
		foreach ( $solids as $solid ) {
			if ( ! is_array( $solid ) ) {
				continue;
			}

			$token   = isset( $solid['token'] ) ? sanitize_key( (string) $solid['token'] ) : '';
			$default = isset( $solid['default'] ) ? strtoupper( (string) $solid['default'] ) : '';
			if ( '' === $token || '' === $default ) {
				continue;
			}

			$solid['token']   = $token;
			$solid['default'] = $default;
			$dedupe_key       = $token . '|' . $default;
			$deduped_solids[ $dedupe_key ] = $solid;
		}

		$deduped_gradients = [];
		foreach ( $gradients as $gradient ) {
			if ( ! is_array( $gradient ) ) {
				continue;
			}

			$name = isset( $gradient['name'] ) ? sanitize_key( (string) $gradient['name'] ) : '';
			if ( '' === $name ) {
				continue;
			}

			$gradient['name'] = $name;
			$deduped_gradients[ $name ] = $gradient;
		}

		$group_labels = [
			'text'    => 'Colors — Text',
			'accent'  => 'Colors — Accent / Links',
			'surface' => 'Colors — Surfaces / Borders',
			'button'  => 'Colors — Buttons',
			'tag'     => 'Colors — Tags / Chips',
			'fill'    => 'Colors — Fills / Washes',
			'widget'  => 'Colors — Widget-specific',
		];
		$registered_control_names = [];
		foreach ( $deduped_solids as $solid ) {
			$registered_control_names[
				'ph_' . str_replace( '-', '_', $slug ) . '_' . str_replace( '-', '_', $solid['token'] )
			] = true;
		}

		foreach ( $group_labels as $group => $group_label ) {
			$group_solids = array_filter(
				$deduped_solids,
				static function ( array $solid ) use ( $group ): bool {
					return $group === ( isset( $solid['group'] ) ? $solid['group'] : 'widget' );
				}
			);
			$group_gradients = array_filter(
				$deduped_gradients,
				static function ( array $gradient ) use ( $group ): bool {
					return $group === ( isset( $gradient['group'] ) ? $gradient['group'] : 'widget' );
				}
			);

			if ( empty( $group_solids ) && empty( $group_gradients ) ) {
				continue;
			}

			$widget->start_controls_section(
				'section_ph_' . str_replace( '-', '_', $slug ) . '_colors_' . $group,
				[
					'label' => esc_html__( $group_label, 'nexora-elementor' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			foreach ( $group_solids as $solid ) {
				$token          = $solid['token'];
				$is_shared      = ! empty( $solid['shared'] );
				$variable_token = $is_shared ? $token : $slug . '-' . $token;
				$control_token  = str_replace( '-', '_', $token );
				$control_name   = 'ph_' . str_replace( '-', '_', $slug ) . '_' . $control_token;

				$widget->add_control(
					$control_name,
					[
						'label'     => esc_html( isset( $solid['label'] ) ? (string) $solid['label'] : $token ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $solid['default'],
						'selectors' => [
							'{{WRAPPER}} .nexora-ph' => '--ph-' . $variable_token . ': {{VALUE}};',
						],
					]
				);
			}

			foreach ( $group_gradients as $gradient ) {
				$selector = isset( $gradient['selector'] ) ? trim( (string) $gradient['selector'] ) : '';
				if ( '' === $selector ) {
					continue;
				}

				$defaults = isset( $gradient['default'] ) && is_array( $gradient['default'] )
					? $gradient['default']
					: [];
				$control_name = 'ph_' . str_replace( '-', '_', $slug ) . '_' . str_replace( '-', '_', $gradient['name'] );
				$fields_options = [
					'background'     => [
						'default' => isset( $defaults['background'] ) ? $defaults['background'] : 'gradient',
					],
					'color'          => [
						'default' => isset( $defaults['color'] ) ? $defaults['color'] : '',
					],
					'color_b'        => [
						'default' => isset( $defaults['color_b'] ) ? $defaults['color_b'] : '',
					],
					'color_stop'     => [
						'default' => isset( $defaults['color_stop'] )
							? $defaults['color_stop']
							: [ 'unit' => '%', 'size' => 0 ],
					],
					'color_b_stop'   => [
						'default' => isset( $defaults['color_b_stop'] )
							? $defaults['color_b_stop']
							: [ 'unit' => '%', 'size' => 100 ],
					],
					'gradient_type'  => [
						'default' => isset( $defaults['gradient_type'] ) ? $defaults['gradient_type'] : 'linear',
					],
					'gradient_angle' => [
						'default' => isset( $defaults['gradient_angle'] )
							? $defaults['gradient_angle']
							: [ 'unit' => 'deg', 'size' => 180 ],
					],
					'gradient_position' => [
						'default' => isset( $defaults['gradient_position'] )
							? $defaults['gradient_position']
							: 'center center',
					],
				];

				if ( isset( $gradient['fields_options'] ) && is_array( $gradient['fields_options'] ) ) {
					$fields_options = array_replace_recursive( $fields_options, $gradient['fields_options'] );
				}

				if ( isset( $registered_control_names[ $control_name ] ) ) {
					$control_name .= '_gradient';
				}

				while ( isset( $registered_control_names[ $control_name ] ) ) {
					$control_name .= '_gradient';
				}

				$widget->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name'           => $control_name,
						'label'          => esc_html( isset( $gradient['label'] ) ? (string) $gradient['label'] : $gradient['name'] ),
						'types'          => [ 'classic', 'gradient' ],
						'selector'       => $selector,
						'fields_options' => $fields_options,
					]
				);
				$registered_control_names[ $control_name ] = true;
			}

			$widget->end_controls_section();
		}
	}
}
