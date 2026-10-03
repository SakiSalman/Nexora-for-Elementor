<?php
/**
 * Elementor content controls for Prospects Hive widgets.
 *
 * Maps in widgets/ph-{slug}/content-map.json describe the original copy.
 * Defaults render the original markup bytes. Edited values are escaped and
 * written into those same text nodes and attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ele_heading_tag' ) ) {
	/**
	 * Allow only heading tags the editor can choose.
	 *
	 * @param mixed  $tag      Saved tag.
	 * @param string $fallback Original tag.
	 */
	function nexora_ele_heading_tag( $tag, $fallback = 'h2' ) {
		$tag      = strtolower( trim( (string) $tag ) );
		$fallback = strtolower( trim( (string) $fallback ) );
		$allowed  = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );
		if ( ! in_array( $fallback, $allowed, true ) ) {
			$fallback = 'h2';
		}
		return in_array( $tag, $allowed, true ) ? $tag : $fallback;
	}
}

if ( ! function_exists( 'nexora_ph_content_map' ) ) {
	/**
	 * Load one widget content map.
	 *
	 * @param string $slug Widget slug.
	 * @return array<string, mixed>
	 */
	function nexora_ph_content_map( $slug ) {
		$slug = preg_replace( '/[^a-z0-9_-]/', '', (string) $slug );
		$file = NEXORA_ELE_PATH . 'widgets/ph-' . $slug . '/content-map.json';
		if ( ! is_readable( $file ) ) {
			return [];
		}
		$data = json_decode( (string) file_get_contents( $file ), true );
		return is_array( $data ) ? $data : [];
	}
}

if ( ! function_exists( 'nexora_ph_register_mapped_repeater_control' ) ) {
	/**
	 * Register one mapped repeater control on an open section.
	 *
	 * @param \Elementor\Widget_Base $widget       Widget.
	 * @param array<string, mixed>   $repeater_map Repeater map.
	 */
	function nexora_ph_register_mapped_repeater_control( $widget, array $repeater_map ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base || empty( $repeater_map['id'] ) || empty( $repeater_map['fields'] ) || ! is_array( $repeater_map['fields'] ) ) {
			return;
		}
		if ( ! class_exists( '\Elementor\Repeater' ) || ! class_exists( '\Elementor\Controls_Manager' ) ) {
			return;
		}

		$manager  = \Elementor\Controls_Manager::class;
		$repeater = new \Elementor\Repeater();
		foreach ( $repeater_map['fields'] as $field ) {
			if ( is_array( $field ) ) {
				nexora_ph_add_mapped_control( $repeater, $field, true );
			}
		}

		$defaults = isset( $repeater_map['defaults'] ) && is_array( $repeater_map['defaults'] ) ? $repeater_map['defaults'] : [];
		foreach ( $defaults as &$default_item ) {
			if ( ! is_array( $default_item ) ) {
				continue;
			}
			foreach ( $default_item as &$default_value ) {
				if ( ! is_array( $default_value ) || empty( $default_value['relative'] ) ) {
					continue;
				}
				$default_value['url'] = NEXORA_ELE_URL . 'assets/images/prospects/' . ltrim( (string) $default_value['relative'], '/' );
				unset( $default_value['relative'] );
			}
			unset( $default_value );
		}
		unset( $default_item );

		$title = '';
		if ( ! empty( $repeater_map['titleField'] ) ) {
			$title = (string) $repeater_map['titleField'];
			// Allow "a — b" templates like GTM Funnel.
			if ( false === strpos( $title, '{{{' ) ) {
				$title = '{{{ ' . $title . ' }}}';
			}
		}

		$widget->add_control(
			(string) $repeater_map['id'],
			[
				'label'         => isset( $repeater_map['label'] ) ? (string) $repeater_map['label'] : esc_html__( 'Items', 'nexora-elementor' ),
				'type'          => $manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => $defaults,
				'prevent_empty' => false,
				'title_field'   => $title,
			]
		);
	}
}

if ( ! function_exists( 'nexora_ph_register_mapped_controls' ) ) {
	/**
	 * Register mapped content controls on a widget.
	 *
	 * Optional content-map key `panel` lists section labels in sidebar order.
	 * Matching singleton fields and repeaters are merged into each section
	 * (GTM Funnel-style grouping).
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 * @param string                 $slug   Widget slug.
	 */
	function nexora_ph_register_mapped_controls( $widget, $slug ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base || ! class_exists( '\Elementor\Controls_Manager' ) ) {
			return;
		}

		$map = nexora_ph_content_map( $slug );
		if ( ! $map ) {
			return;
		}

		$manager   = \Elementor\Controls_Manager::class;
		$singles   = isset( $map['singletons'] ) && is_array( $map['singletons'] ) ? $map['singletons'] : [];
		$repeaters = isset( $map['repeaters'] ) && is_array( $map['repeaters'] ) ? $map['repeaters'] : [];

		$groups = [];
		foreach ( $singles as $field ) {
			if ( ! is_array( $field ) ) {
				continue;
			}
			$section = isset( $field['section'] ) && '' !== (string) $field['section'] ? (string) $field['section'] : __( 'Content', 'nexora-elementor' );
			if ( ! isset( $groups[ $section ] ) ) {
				$groups[ $section ] = [];
			}
			$groups[ $section ][] = $field;
		}

		$repeaters_by_section = [];
		foreach ( $repeaters as $repeater_map ) {
			if ( ! is_array( $repeater_map ) || empty( $repeater_map['id'] ) ) {
				continue;
			}
			$section = isset( $repeater_map['section'] ) && '' !== (string) $repeater_map['section']
				? (string) $repeater_map['section']
				: ( isset( $repeater_map['label'] ) ? (string) $repeater_map['label'] : __( 'Items', 'nexora-elementor' ) );
			if ( ! isset( $repeaters_by_section[ $section ] ) ) {
				$repeaters_by_section[ $section ] = [];
			}
			$repeaters_by_section[ $section ][] = $repeater_map;
		}

		$panel = isset( $map['panel'] ) && is_array( $map['panel'] ) ? $map['panel'] : [];
		if ( ! $panel ) {
			$panel = array_values(
				array_unique(
					array_merge( array_keys( $groups ), array_keys( $repeaters_by_section ) )
				)
			);
		}

		$group_index = 0;
		foreach ( $panel as $section_label ) {
			$section_label = (string) $section_label;
			$fields        = isset( $groups[ $section_label ] ) ? $groups[ $section_label ] : [];
			$reps          = isset( $repeaters_by_section[ $section_label ] ) ? $repeaters_by_section[ $section_label ] : [];
			if ( ! $fields && ! $reps ) {
				continue;
			}

			$group_index++;
			$widget->start_controls_section(
				'section_ph_' . $slug . '_' . $group_index,
				[
					'label' => $section_label,
					'tab'   => $manager::TAB_CONTENT,
				]
			);

			foreach ( $fields as $field ) {
				if ( isset( $field['control'] ) && 'divider' === $field['control'] ) {
					$widget->add_control(
						(string) $field['id'],
						[
							'label'     => isset( $field['label'] ) ? (string) $field['label'] : '',
							'type'      => $manager::HEADING,
							'separator' => 'before',
						]
					);
					continue;
				}
				nexora_ph_add_mapped_control( $widget, $field );
			}

			foreach ( $reps as $repeater_map ) {
				nexora_ph_register_mapped_repeater_control( $widget, $repeater_map );
			}

			$widget->end_controls_section();
			unset( $groups[ $section_label ], $repeaters_by_section[ $section_label ] );
		}

		// Leftover sections keep working if panel omitted a label.
		foreach ( $groups as $section_label => $fields ) {
			$reps = isset( $repeaters_by_section[ $section_label ] ) ? $repeaters_by_section[ $section_label ] : [];
			$group_index++;
			$widget->start_controls_section(
				'section_ph_' . $slug . '_' . $group_index,
				[
					'label' => (string) $section_label,
					'tab'   => $manager::TAB_CONTENT,
				]
			);
			foreach ( $fields as $field ) {
				nexora_ph_add_mapped_control( $widget, $field );
			}
			foreach ( $reps as $repeater_map ) {
				nexora_ph_register_mapped_repeater_control( $widget, $repeater_map );
			}
			$widget->end_controls_section();
			unset( $repeaters_by_section[ $section_label ] );
		}
		foreach ( $repeaters_by_section as $section_label => $reps ) {
			$group_index++;
			$widget->start_controls_section(
				'section_ph_' . $slug . '_' . $group_index,
				[
					'label' => (string) $section_label,
					'tab'   => $manager::TAB_CONTENT,
				]
			);
			foreach ( $reps as $repeater_map ) {
				nexora_ph_register_mapped_repeater_control( $widget, $repeater_map );
			}
			$widget->end_controls_section();
		}
	}
}

if ( ! function_exists( 'nexora_ph_add_mapped_control' ) ) {
	/**
	 * Add one mapped control.
	 *
	 * @param \Elementor\Widget_Base|\Elementor\Repeater $target     Widget or repeater.
	 * @param array<string, mixed>                      $field      Field map.
	 * @param bool                                      $in_repeater Whether defaults live on the repeater item.
	 */
	function nexora_ph_add_mapped_control( $target, array $field, $in_repeater = false ): void {
		if ( empty( $field['id'] ) || empty( $field['control'] ) ) {
			return;
		}
		$manager = \Elementor\Controls_Manager::class;
		$label   = isset( $field['label'] ) ? (string) $field['label'] : (string) $field['id'];
		$id      = (string) $field['id'];
		$control = (string) $field['control'];
		$args    = [
			'label' => $label,
		];

		if ( 'tag' === $control ) {
			$args['type']    = $manager::SELECT;
			$args['default'] = nexora_ele_heading_tag( isset( $field['default'] ) ? $field['default'] : 'h2', 'h2' );
			$args['options'] = [
				'h1'   => 'H1',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6',
				'div'  => 'div',
				'span' => 'span',
				'p'    => 'p',
			];
		} elseif ( 'select' === $control ) {
			$args['type']    = $manager::SELECT;
			$args['default'] = isset( $field['default'] ) ? (string) $field['default'] : '';
			$args['options'] = ( isset( $field['options'] ) && is_array( $field['options'] ) ) ? $field['options'] : [];
		} elseif ( 'textarea' === $control ) {
			$args['type']        = $manager::TEXTAREA;
			$args['default']     = isset( $field['default'] ) ? (string) $field['default'] : '';
			$args['label_block'] = true;
		} elseif ( 'url' === $control ) {
			$args['type']    = $manager::URL;
			$args['default'] = [
				'url'         => isset( $field['default'] ) ? (string) $field['default'] : '',
				'is_external' => ! empty( $field['isExternal'] ),
				'nofollow'    => ! empty( $field['nofollow'] ),
			];
		} elseif ( 'image' === $control || 'media' === $control ) {
			$relative        = isset( $field['relative'] ) ? ltrim( (string) $field['relative'], '/' ) : '';
			$args['type']    = $manager::MEDIA;
			$args['default'] = [
				'url' => '' !== $relative ? NEXORA_ELE_URL . 'assets/images/prospects/' . $relative : '',
				'id'  => '',
			];
			if ( isset( $field['default'] ) && is_array( $field['default'] ) ) {
				$args['default'] = $field['default'];
			}
			if ( isset( $field['mediaTypes'] ) && is_array( $field['mediaTypes'] ) ) {
				$args['media_types'] = array_values( $field['mediaTypes'] );
			} elseif ( 'media' === $control ) {
				$args['media_types'] = [ 'video' ];
			}
		} elseif ( 'icon' === $control ) {
			$args['type']    = $manager::ICONS;
			$args['default'] = [
				'value'   => '',
				'library' => '',
			];
			if ( isset( $field['default'] ) && is_array( $field['default'] ) ) {
				$args['default'] = $field['default'];
			}
		} else {
			$args['type']    = $manager::TEXT;
			$args['default'] = isset( $field['default'] ) ? (string) $field['default'] : '';
		}

		if ( isset( $field['condition'] ) && is_array( $field['condition'] ) ) {
			$args['condition'] = $field['condition'];
		}
		if ( isset( $field['description'] ) && is_string( $field['description'] ) && '' !== $field['description'] ) {
			$args['description'] = $field['description'];
		}

		if ( $in_repeater && 'image' !== $control && 'media' !== $control && 'url' !== $control && 'icon' !== $control && 'select' !== $control ) {
			$args['label_block'] = true;
		}

		$target->add_control( $id, $args );
	}
}

if ( ! function_exists( 'nexora_ph_apply_content' ) ) {
	/**
	 * Replace mapped content. Unedited defaults keep the original bytes.
	 *
	 * @param string               $html     Original markup.
	 * @param string               $slug     Widget slug.
	 * @param array<string, mixed> $settings Widget settings.
	 */
	function nexora_ph_apply_content( $html, $slug, array $settings ) {
		$map = nexora_ph_content_map( $slug );
		if ( ! $map ) {
			return $html;
		}

		$ranges = [];
		$singles = isset( $map['singletons'] ) && is_array( $map['singletons'] ) ? $map['singletons'] : [];
		foreach ( $singles as $field ) {
			if ( ! is_array( $field ) ) {
				continue;
			}
			foreach ( nexora_ph_control_ranges( $field, $settings ) as $range ) {
				$ranges[] = $range;
			}
		}

		$repeaters = isset( $map['repeaters'] ) && is_array( $map['repeaters'] ) ? $map['repeaters'] : [];
		foreach ( $repeaters as $repeater_map ) {
			$built = nexora_ph_render_repeater( $repeater_map, $settings );
			if ( null === $built ) {
				continue;
			}
			$ranges[] = [
				'start' => (int) $repeater_map['start'],
				'end'   => (int) $repeater_map['end'],
				'value' => $built,
			];
		}

		usort(
			$ranges,
			static function ( $a, $b ) {
				return $b['start'] <=> $a['start'];
			}
		);

		foreach ( $ranges as $range ) {
			if ( ! isset( $range['value'] ) || null === $range['value'] ) {
				continue;
			}
			$start = (int) $range['start'];
			$end   = (int) $range['end'];
			if ( $start < 0 || $end < $start || $end > strlen( $html ) ) {
				continue;
			}
			$html = substr_replace( $html, (string) $range['value'], $start, $end - $start );
		}

		return $html;
	}
}

if ( ! function_exists( 'nexora_ph_control_ranges' ) ) {
	/**
	 * Replacement ranges for one control. Empty keeps the original bytes.
	 *
	 * @param array<string, mixed> $field    Field map.
	 * @param array<string, mixed> $settings Settings bag.
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_control_ranges( array $field, array $settings ) {
		$control = isset( $field['control'] ) ? (string) $field['control'] : 'text';
		if ( 'divider' === $control || 'select' === $control || 'media' === $control ) {
			return [];
		}
		if ( 'tag' === $control ) {
			return nexora_ph_tag_ranges( $field, $settings );
		}
		if ( ! isset( $field['start'] ) ) {
			return [];
		}
		$range = nexora_ph_field_range( '', $field, $settings, false );
		return null === $range ? [] : [ $range ];
	}
}

if ( ! function_exists( 'nexora_ph_tag_ranges' ) ) {
	/**
	 * Replace an opening and closing tag name when the editor picks a different tag.
	 *
	 * @param array<string, mixed> $field    Tag field.
	 * @param array<string, mixed> $settings Settings bag.
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_tag_ranges( array $field, array $settings ) {
		$default = nexora_ele_heading_tag( isset( $field['default'] ) ? $field['default'] : 'h2', 'h2' );
		$id      = isset( $field['id'] ) ? (string) $field['id'] : '';
		$has     = '' !== $id && array_key_exists( $id, $settings );
		$value   = nexora_ele_heading_tag( $has ? $settings[ $id ] : $default, $default );
		if ( $value === $default ) {
			return [];
		}
		return [
			[
				'start' => isset( $field['openStart'] ) ? (int) $field['openStart'] : 0,
				'end'   => isset( $field['openEnd'] ) ? (int) $field['openEnd'] : 0,
				'value' => $value,
			],
			[
				'start' => isset( $field['closeStart'] ) ? (int) $field['closeStart'] : 0,
				'end'   => isset( $field['closeEnd'] ) ? (int) $field['closeEnd'] : 0,
				'value' => $value,
			],
		];
	}
}

if ( ! function_exists( 'nexora_ph_field_range' ) ) {
	/**
	 * Build a replacement range, or null when the original bytes should stay.
	 *
	 * @param string               $html       Markup or shell.
	 * @param array<string, mixed> $field      Field map.
	 * @param array<string, mixed> $settings   Settings bag.
	 * @param bool                 $in_repeater Field lives on a repeater item.
	 * @return array<string, mixed>|null
	 */
	function nexora_ph_field_range( $html, array $field, array $settings, $in_repeater ) {
		unset( $html, $in_repeater );
		$control = isset( $field['control'] ) ? (string) $field['control'] : 'text';
		$id      = isset( $field['id'] ) ? (string) $field['id'] : '';
		$start   = isset( $field['start'] ) ? (int) $field['start'] : 0;
		$end     = isset( $field['end'] ) ? (int) $field['end'] : 0;
		$has     = '' !== $id && array_key_exists( $id, $settings );
		$raw     = $has ? $settings[ $id ] : null;

		if ( 'icon' === $control ) {
			if ( ! $has || ! is_array( $raw ) || empty( $raw['value'] ) || ! class_exists( '\Elementor\Icons_Manager' ) ) {
				return null;
			}
			ob_start();
			\Elementor\Icons_Manager::render_icon( $raw, [ 'aria-hidden' => 'true' ] );
			$icon_html = trim( (string) ob_get_clean() );
			if ( '' === $icon_html ) {
				return null;
			}
			return [
				'start' => $start,
				'end'   => $end,
				'value' => $icon_html,
			];
		}

		if ( 'image' === $control ) {
			$relative = isset( $field['relative'] ) ? ltrim( (string) $field['relative'], '/' ) : '';
			$default  = NEXORA_ELE_URL . 'assets/images/prospects/' . $relative;
			$url      = $default;
			if ( $has ) {
				if ( is_array( $raw ) ) {
					$url = isset( $raw['url'] ) ? (string) $raw['url'] : '';
				} elseif ( is_string( $raw ) ) {
					$url = $raw;
				}
			}
			$stored_relative = is_array( $raw ) && isset( $raw['relative'] ) ? ltrim( (string) $raw['relative'], '/' ) : '';
			if ( $url === $default || ( '' !== $relative && $url === 'assets/' . $relative ) || ( '' !== $stored_relative && '' === $url ) ) {
				return null;
			}
			return [
				'start' => $start,
				'end'   => $end,
				'value' => esc_url( $url ),
			];
		}

		if ( 'url' === $control ) {
			$default = isset( $field['default'] ) ? (string) $field['default'] : '';
			$url     = $default;
			if ( $has ) {
				if ( is_array( $raw ) ) {
					$url = isset( $raw['url'] ) ? (string) $raw['url'] : '';
				} elseif ( is_string( $raw ) ) {
					$url = $raw;
				}
			}
			if ( $url === $default ) {
				return null;
			}
			return [
				'start' => $start,
				'end'   => $end,
				'value' => esc_url( $url ),
			];
		}

		$default = isset( $field['default'] ) ? (string) $field['default'] : '';
		$value   = $has ? (string) $raw : $default;
		if ( $value === $default ) {
			return null;
		}
		$escaped = 'attr' === ( isset( $field['context'] ) ? (string) $field['context'] : 'text' ) ? esc_attr( $value ) : esc_html( $value );
		return [
			'start' => $start,
			'end'   => $end,
			'value' => $escaped,
		];
	}
}

if ( ! function_exists( 'nexora_ph_render_repeater' ) ) {
	/**
	 * Rebuild one repeated group from its original shells.
	 *
	 * @param array<string, mixed> $repeater_map Repeater map.
	 * @param array<string, mixed> $settings     Widget settings.
	 * @return string|null Null keeps the original span.
	 */
	function nexora_ph_render_repeater( array $repeater_map, array $settings ) {
		$id = isset( $repeater_map['id'] ) ? (string) $repeater_map['id'] : '';
		if ( '' === $id || ! isset( $repeater_map['shells'] ) || ! is_array( $repeater_map['shells'] ) ) {
			return null;
		}
		$defaults = isset( $repeater_map['defaults'] ) && is_array( $repeater_map['defaults'] ) ? $repeater_map['defaults'] : [];
		$items    = $defaults;
		if ( array_key_exists( $id, $settings ) && is_array( $settings[ $id ] ) ) {
			$items = $settings[ $id ];
		}
		$shells = $repeater_map['shells'];
		$seps   = isset( $repeater_map['separators'] ) && is_array( $repeater_map['separators'] ) ? $repeater_map['separators'] : [];
		$shell_field_sets = isset( $repeater_map['shellFields'] ) && is_array( $repeater_map['shellFields'] ) ? $repeater_map['shellFields'] : [];
		if ( ! $shells ) {
			return null;
		}

		$changed = count( $items ) !== count( $defaults );
		$out     = '';
		$index   = 0;
		foreach ( $items as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}
			$shell_index = min( $index, count( $shells ) - 1 );
			$shell       = (string) $shells[ $shell_index ];
			$fields      = isset( $shell_field_sets[ $shell_index ] ) && is_array( $shell_field_sets[ $shell_index ] ) ? $shell_field_sets[ $shell_index ] : [];
			$ranges      = [];
			foreach ( $fields as $field ) {
				if ( ! is_array( $field ) ) {
					continue;
				}
				$key = isset( $field['id'] ) ? (string) $field['id'] : '';
				$bag = $item;
				if ( '' !== $key && ! array_key_exists( $key, $bag ) && isset( $defaults[ $shell_index ][ $key ] ) ) {
					$bag[ $key ] = $defaults[ $shell_index ][ $key ];
				}
				$found = nexora_ph_control_ranges( $field, $bag );
				if ( $found ) {
					foreach ( $found as $range ) {
						$ranges[] = $range;
					}
					$changed = true;
				}
			}
			usort(
				$ranges,
				static function ( $a, $b ) {
					return $b['start'] <=> $a['start'];
				}
			);
			foreach ( $ranges as $range ) {
				$start = (int) $range['start'];
				$end   = (int) $range['end'];
				if ( $start < 0 || $end < $start || $end > strlen( $shell ) ) {
					continue;
				}
				$shell = substr_replace( $shell, (string) $range['value'], $start, $end - $start );
			}
			if ( $index > 0 ) {
				$sep_index = min( $index - 1, max( count( $seps ) - 1, 0 ) );
				$out      .= isset( $seps[ $sep_index ] ) ? (string) $seps[ $sep_index ] : '';
			}
			$out .= $shell;
			$index++;
		}

		if ( ! $changed ) {
			return null;
		}
		return $out;
	}
}
