/* Phase 1 scaffold: generate the 8 CE widget class files, render.php, controls-content.php,
   the pipeline data bridge, and the registry entries. Idempotent — re-run refreshes the boilerplate.
   markup.html (golden copy) and the JS behavior files are never touched.
   Run: node bin/ce-scaffold.mjs */

import fs from 'fs'
import path from 'path'
import { ROOT, readTemplate } from './ce-lib.mjs'

const FONTS_SRC = 'https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Poppins:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap'

/* name, title, icon, keywords, registry description, dynamic, script handles */
const CONFIG = [
  { name: 'hero', title: 'Hero', icon: 'eicon-banner', keywords: ['hero', 'cold email', 'logos'], description: 'Cold Email hero with campaign card and client logo strip.', scripts: ['nexora-ph-ce-anchors'] },
  { name: 'problem', title: 'Problem', icon: 'eicon-warning', keywords: ['problem', 'timeline', 'leaks'], description: 'Cold Email problem: sticky leak card image and scrolling timeline.', scripts: ['nexora-ph-ce-problem'] },
  { name: 'solution', title: 'Solution', icon: 'eicon-check-circle', keywords: ['solution', 'delivery', 'cards'], description: 'Cold Email solution: sticky side title and stacked delivery cards.', scripts: ['nexora-ph-ce-solution'] },
  { name: 'services', title: 'Services', icon: 'eicon-posts-grid', keywords: ['services', 'bento', 'offer'], description: 'Cold Email services bento grid with hover-highlight cards.', scripts: ['nexora-ph-ce-hover'] },
  { name: 'pipeline', title: 'Pipeline', icon: 'eicon-flow', keywords: ['pipeline', 'stages', 'b2b'], description: 'Cold Email B2B pipeline with animated stage progress.', scripts: ['nexora-ph-runtime', 'nexora-ph-ce-pipeline'], dynamic: true },
  { name: 'process', title: 'Process', icon: 'eicon-time-line', keywords: ['process', 'steps', 'sticky'], description: 'Cold Email process: sticky stacked step cards with visuals.', scripts: [] },
  { name: 'outlook', title: 'Outlook', icon: 'eicon-skill-bar', keywords: ['outlook', 'growth', 'chart'], description: 'Cold Email 90-day outlook growth chart.', scripts: [] },
  { name: 'why', title: 'Why', icon: 'eicon-favorite', keywords: ['why', 'reasons', 'choose'], description: 'Cold Email reasons to choose us with hover-highlight cards.', scripts: ['nexora-ph-ce-hover'] },
  { name: 'signal', title: 'Signal', icon: 'eicon-alert', keywords: ['signal', 'message', 'personalization'], description: 'Cold Email signal-based message showcase.', scripts: [] }
]

const SCRIPT_SRC = {
  'nexora-ph-ce-anchors': 'assets/js/nexora-ph-ce-anchors.js',
  'nexora-ph-ce-problem': 'assets/js/nexora-ph-ce-problem.js',
  'nexora-ph-ce-solution': 'assets/js/nexora-ph-ce-solution.js',
  'nexora-ph-ce-hover': 'assets/js/nexora-ph-ce-hover.js',
  'nexora-ph-runtime': 'assets/js/nexora-ph-runtime.js',
  'nexora-ph-ce-pipeline': 'assets/js/nexora-ph-ce-pipeline.js'
}

const SCRIPT_DEPS = {
  'nexora-ph-ce-pipeline': ['jquery', 'nexora-ph-runtime']
}

const studly = (name) => name.split('-').map((p) => p.charAt(0).toUpperCase() + p.slice(1)).join('')

const classFile = (w) => `<?php
/**
 * CE ${w.title} widget.
 *
 * Markup, CSS, and behavior are copied from the Cold Email design export.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __DIR__ ) . '/includes/class-widget-base.php';
require_once __DIR__ . '/ph-ce-${w.name}/controls-content.php';

if ( ! class_exists( 'Nexora_PH_CE_${studly(w.name)}_Widget', false ) ) {

	/**
	 * Cold Email ${w.title.toLowerCase()} widget.
	 */
	class Nexora_PH_CE_${studly(w.name)}_Widget extends Nexora_Ele_Widget_Base {

		/**
		 * Widget name.
		 */
		public function get_name(): string {
			return 'ele-ph-ce-${w.name}';
		}

		/**
		 * Widget title.
		 */
		public function get_title(): string {
			return esc_html__( 'CE ${w.title}', 'nexora-elementor' );
		}

		/**
		 * Widget icon.
		 */
		public function get_icon(): string {
			return '${w.icon}';
		}

		/**
		 * Widget categories.
		 */
		public function get_categories(): array {
			return [ 'nexora' ];
		}

		/**
		 * Widget keywords.
		 */
		public function get_keywords(): array {
			return [ 'nexora', 'cold email', ${w.keywords.map((k) => "'" + k + "'").join(', ')} ];
		}

		/**
		 * Style dependencies.
		 */
		public function get_style_depends(): array {
			if ( ! $this->is_nexora_widget_enabled() ) {
				return [];
			}

			return [ 'nexora-ph-fonts', 'nexora-ph-shared', 'nexora-ph-ce-${w.name}' ];
		}

		/**
		 * Script dependencies.
		 */
		public function get_script_depends(): array {
			if ( ! $this->is_nexora_widget_enabled() ) {
				return [];
			}

			return [${w.scripts.length ? ' ' + w.scripts.map((s) => "'" + s + "'").join(', ') : ''} ];
		}

		/**
		 * Elementor compatibility.
		 */
		public function has_widget_inner_wrapper(): bool {
			return false;
		}

		/**
		 * Register controls.
		 */
		protected function register_controls(): void {
			if ( function_exists( 'nexora_ph_ce_${w.name}_register_content_controls' ) ) {
				nexora_ph_ce_${w.name}_register_content_controls( $this );
			}
		}

		/**
		 * Render widget.
		 */
		protected function render(): void {
			if ( ! $this->is_nexora_widget_enabled() ) {
				$this->render_disabled_placeholder();
				return;
			}

			$raw = (string) $this->get_id();
			if ( '' === $raw ) {
				$raw = wp_unique_id();
			}

			$uid = 'ph' . sanitize_html_class( $raw );

			require NEXORA_ELE_PATH . 'widgets/ph-ce-${w.name}/render.php';
		}
	}
}
`

const controlsFile = (w) => `<?php
/**
 * Content controls for CE ${w.title.toLowerCase()}. Phase 1 serves the static design copy only;
 * content controls arrive with phase 2.
 *
 * @param \\Elementor\\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\\Controls_Manager;

if ( ! function_exists( 'nexora_ph_ce_${w.name}_register_content_controls' ) ) {
	/**
	 * Register content controls.
	 *
	 * @param \\Elementor\\Widget_Base $widget Widget.
	 */
	function nexora_ph_ce_${w.name}_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \\Elementor\\Widget_Base ) {
			return;
		}

		$widget->start_controls_section(
			'section_schema',
			[
				'label' => esc_html__( 'Schema', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'_schema_version',
			[
				'type'    => Controls_Manager::HIDDEN,
				'default' => '1',
			]
		);

		$widget->end_controls_section();
	}
}
`

const renderFile = (w) => {
  // phase 2: template.php owns the markup; render.php just wires settings and the renderer
  if (fs.existsSync(path.join(ROOT, 'widgets', 'ph-ce-' + w.name, 'template.php'))) {
    return `<?php
/**
 * Render Cold Email ${w.name} markup through the phase 2 template.
 *
 * @var string $uid Element id suffix.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $uid ) || ! is_string( $uid ) ) {
	return;
}

if ( ! function_exists( 'nexora_ph_render_section' ) ) {
	require_once NEXORA_ELE_PATH . 'includes/ph-markup.php';
}
if ( ! function_exists( 'nexora_ph_ce_setting' ) ) {
	require_once NEXORA_ELE_PATH . 'widgets/ph-ce-lib/fields.php';
}

$settings = [];
if ( isset( $this ) && is_object( $this ) && method_exists( $this, 'get_settings_for_display' ) ) {
	$settings = $this->get_settings_for_display();
}
if ( ! is_array( $settings ) ) {
	$settings = [];
}

ob_start();
include NEXORA_ELE_PATH . 'widgets/ph-ce-${w.name}/template.php';
$html = (string) ob_get_clean();

nexora_ph_render_section( 'ce-${w.name}', $uid, ${w.dynamic ? 'true' : 'false'}, [], $html );
`
  }
  return w.dynamic ? `<?php
/**
 * Render Cold Email pipeline markup. Dynamic section: nexora-ph-runtime.js mounts the
 * {{ }} / sc-for markup from the stage JSON, same bridge as PH Impact.
 *
 * @var string $uid Element id suffix.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $uid ) || ! is_string( $uid ) ) {
	return;
}

if ( ! function_exists( 'nexora_ph_render_section' ) ) {
	require_once NEXORA_ELE_PATH . 'includes/ph-markup.php';
}

if ( ! function_exists( 'nexora_ph_ce_pipeline_json' ) ) {
	require_once NEXORA_ELE_PATH . 'widgets/ph-ce-pipeline/data.php';
}

$file = NEXORA_ELE_PATH . 'widgets/ph-ce-pipeline/markup.html';
if ( ! is_readable( $file ) ) {
	return;
}
$html = file_get_contents( $file );
if ( ! is_string( $html ) || '' === $html ) {
	return;
}

// Stage data rides along as JSON. markup.html stays the golden copy; the parity tool strips this tag.
$html = preg_replace_callback(
	'/<section\\b[^>]*>/',
	static function ( $matches ) {
		$json = nexora_ph_ce_pipeline_json( nexora_ph_ce_pipeline_default_stages() );
		return $matches[0] . '<script type="application/json" class="ph-ce-pipeline-data">' . $json . '</script>';
	},
	$html,
	1
);

nexora_ph_render_section( 'ce-pipeline', $uid, true, [], $html );
` : `<?php
/**
 * Render Cold Email ${w.name} markup.
 *
 * @var string $uid Element id suffix.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $uid ) || ! is_string( $uid ) ) {
	return;
}

if ( ! function_exists( 'nexora_ph_render_section' ) ) {
	require_once NEXORA_ELE_PATH . 'includes/ph-markup.php';
}

$settings = [];
if ( isset( $this ) && is_object( $this ) && method_exists( $this, 'get_settings_for_display' ) ) {
	$settings = $this->get_settings_for_display();
}
if ( ! is_array( $settings ) ) {
	$settings = [];
}

nexora_ph_render_section( 'ce-${w.name}', $uid, false, $settings );
`
}

const pipelineData = () => {
  const template = readTemplate()
  const m = template.match(/const PL = (\[[\s\S]*?\]);/)
  if (!m) throw new Error('PL array not found in template')
  JSON.parse(m[1])
  const embedded = m[1].replace(/\\/g, '\\\\').replace(/'/g, "\\'")
  return `<?php
/**
 * Cold Email pipeline stage data. Defaults are the five design stages,
 * copied verbatim from the design export's PL array.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_ce_pipeline_default_stages' ) ) {
	/**
	 * The five pipeline stages from the design export.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_ce_pipeline_default_stages(): array {
		$json = '${embedded}';
		$data = json_decode( $json, true );

		return is_array( $data ) ? $data : [];
	}
}

if ( ! function_exists( 'nexora_ph_ce_pipeline_json' ) ) {
	/**
	 * JSON safe to place inside a script tag.
	 *
	 * @param array<int, array<string, mixed>> $stages Stages.
	 */
	function nexora_ph_ce_pipeline_json( $stages ): string {
		$flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
		$json  = function_exists( 'wp_json_encode' )
			? wp_json_encode( $stages, $flags )
			: json_encode( $stages, $flags );

		return is_string( $json ) ? $json : '[]';
	}
}
`
}

const registryEntries = () => {
  const out = []
  for (const w of CONFIG) {
    out.push(`\t\t\t\t'ele-ph-ce-${w.name}' => [`)
    out.push(`\t\t\t\t\t'id'              => 'ele-ph-ce-${w.name}',`)
    out.push(`\t\t\t\t\t'title'           => __( 'CE ${w.title}', 'nexora-elementor' ),`)
    out.push(`\t\t\t\t\t'description'     => __( '${w.description.replace(/'/g, "\\'")}', 'nexora-elementor' ),`)
    out.push(`\t\t\t\t\t'file'            => 'widgets/class-ele-ph-ce-${w.name}.php',`)
    out.push(`\t\t\t\t\t'class'           => 'Nexora_PH_CE_${studly(w.name)}_Widget',`)
    out.push(`\t\t\t\t\t'default_enabled' => true,`)
    out.push(`\t\t\t\t\t'styles'          => [`)
    out.push(`\t\t\t\t\t\t[`)
    out.push(`\t\t\t\t\t\t\t'handle'   => 'nexora-ph-fonts',`)
    out.push(`\t\t\t\t\t\t\t'src'      => '${FONTS_SRC}',`)
    out.push(`\t\t\t\t\t\t\t'deps'     => [],`)
    out.push(`\t\t\t\t\t\t\t'ver'      => null,`)
    out.push(`\t\t\t\t\t\t\t'external' => true,`)
    out.push(`\t\t\t\t\t\t],`)
    out.push(`\t\t\t\t\t\t[`)
    out.push(`\t\t\t\t\t\t\t'handle' => 'nexora-ph-shared',`)
    out.push(`\t\t\t\t\t\t\t'src'    => 'assets/css/nexora-ph-shared.css',`)
    out.push(`\t\t\t\t\t\t\t'deps'   => [ 'nexora-ph-fonts' ],`)
    out.push(`\t\t\t\t\t\t\t'ver'    => true,`)
    out.push(`\t\t\t\t\t\t],`)
    out.push(`\t\t\t\t\t\t[`)
    out.push(`\t\t\t\t\t\t\t'handle' => 'nexora-ph-ce-${w.name}',`)
    out.push(`\t\t\t\t\t\t\t'src'    => 'assets/css/nexora-ph-ce-${w.name}.css',`)
    out.push(`\t\t\t\t\t\t\t'deps'   => [ 'nexora-ph-shared' ],`)
    out.push(`\t\t\t\t\t\t\t'ver'    => true,`)
    out.push(`\t\t\t\t\t\t],`)
    out.push(`\t\t\t\t\t],`)
    if (w.scripts.length) {
      out.push(`\t\t\t\t\t'scripts'         => [`)
      for (const handle of w.scripts) {
        out.push(`\t\t\t\t\t\t[`)
        out.push(`\t\t\t\t\t\t\t'handle'    => '${handle}',`)
        out.push(`\t\t\t\t\t\t\t'src'       => '${SCRIPT_SRC[handle]}',`)
        out.push(`\t\t\t\t\t\t\t'deps'      => [${(SCRIPT_DEPS[handle] || []).length ? ' ' + SCRIPT_DEPS[handle].map((d) => "'" + d + "'").join(', ') : ''} ],`)
        out.push(`\t\t\t\t\t\t\t'ver'       => true,`)
        out.push(`\t\t\t\t\t\t\t'in_footer' => true,`)
        out.push(`\t\t\t\t\t\t],`)
      }
      out.push(`\t\t\t\t\t],`)
    } else {
      out.push(`\t\t\t\t\t'scripts'         => [],`)
    }
    out.push(`\t\t\t\t],`)
  }
  return out.join('\n')
}

/* --- write ------------------------------------------------------------------ */

const crlf = (text) => text.replace(/\r\n/g, '\n').replace(/\n/g, '\r\n')

let written = 0
for (const w of CONFIG) {
  const dir = path.join(ROOT, 'widgets', 'ph-ce-' + w.name)
  fs.mkdirSync(dir, { recursive: true })
  const files = [
    [path.join(ROOT, 'widgets', 'class-ele-ph-ce-' + w.name + '.php'), classFile(w)],
    [path.join(dir, 'render.php'), renderFile(w)]
  ]
  // phase 2 owns controls-content.php once template.php exists
  if (!fs.existsSync(path.join(dir, 'template.php'))) {
    files.push([path.join(dir, 'controls-content.php'), controlsFile(w)])
  }
  if (w.dynamic) files.push([path.join(dir, 'data.php'), pipelineData()])
  for (const [file, content] of files) {
    fs.writeFileSync(file, crlf(content), 'utf8')
    written++
  }
}

const registryPath = path.join(ROOT, 'includes', 'class-widget-registry.php')
const registry = fs.readFileSync(registryPath, 'utf8')
if (registry.includes("'ele-ph-ce-hero'")) {
  console.log('registry: CE entries already present (delete them first to rebuild)')
} else {
  const body = registry.replace(/\r\n/g, '\n')
  const tail = "\t\t\t\t\t'scripts'         => [],\n\t\t\t\t],\n\t\t\t];"
  const at = body.lastIndexOf(tail)
  if (at === -1) throw new Error('registry insertion point not found')
  const updated = body.slice(0, at) + "\t\t\t\t\t'scripts'         => [],\n\t\t\t\t],\n" + registryEntries() + '\n\t\t\t];' + body.slice(at + tail.length)
  fs.writeFileSync(registryPath, crlf(updated), 'utf8')
  console.log('registry: 13 CE entries inserted')
}

console.log('scaffold: ' + written + ' PHP files written for ' + CONFIG.length + ' widgets')
