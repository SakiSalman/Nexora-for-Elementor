# Nexora Widget Development Guide

**Plugin:** Nexora for Elementor  
**Version observed:** `1.1.1` (`Nexora_For_Elementor::VERSION` and the plugin header)  
**Repository root:** `wp-content/plugins/nexora-for-elementor/`  
**Text domain:** `nexora-elementor`  
**Status of this document:** Architecture specification derived from the current source. It does not change plugin behavior.

This guide is the contract for future Nexora widgets. Read it before adding a widget. When this document and the code disagree, the code wins.

---

## How to read labels

Every claim in this guide uses one of these labels.

| Label | Meaning |
| --- | --- |
| **CURRENT** | Confirmed in the repository at the time this guide was written. |
| **STANDARD** | Convention future widgets must follow, because the existing plugin already works this way. |
| **RECOMMENDED** | Proposed improvement. Do not implement it unless the task asks for it. |
| **WIDGET-SPECIFIC** | Belongs to one existing widget. Do not copy it into a new widget. |
| **UNKNOWN** | Cannot be determined from the current repository. |

The task brief described “one existing widget.” **CURRENT:** the repository contains **two** widgets:

| Registry id | Class | Title |
| --- | --- | --- |
| `ele-gtm-funnel` | `Nexora_GTM_Funnel_Widget` | ELE GTM Funnel |
| `ele-timeline` | `Nexora_Timeline_Widget` | ELE Timeline |

Both are first-party Elementor widgets. Neither calls a remote application API. Content comes from Elementor controls saved with the page.

---

## 1. Purpose

**STANDARD.** Use this guide to add a Nexora widget that:

- Registers through `Nexora_Ele_Widget_Registry`.
- Extends `Nexora_Ele_Widget_Base`.
- Can be enabled or disabled from **Nexora → Widgets** without destroying saved page data.
- Loads its own CSS and JavaScript only when that widget is enabled.
- Keeps business markup, controls, and scripts inside that widget’s folder.
- Reuses the plugin bootstrap, settings, and soft-disable behavior.

This is a WordPress plugin that adds widgets to Elementor. It is not a React application, not a REST service, and not a multi-package monorepo.

---

## 2. Nexora Overview

### CURRENT runtime

| Item | Value |
| --- | --- |
| Language | PHP for registration, controls, and HTML. Vanilla JavaScript (IIFE) for front-end interaction. CSS for presentation. |
| Framework | WordPress plugin API + Elementor `Widget_Base`. |
| Minimum WordPress | 6.0 (plugin header `Requires at least`) |
| Minimum PHP | 7.4 (plugin header `Requires PHP`) |
| Required plugin | `elementor` (`Requires Plugins: elementor`) |
| Bootstrap | `nexora-for-elementor.php` instantiates `Nexora_For_Elementor` |
| Widget system | One PHP class per widget, registered on `elementor/widgets/register` |
| Data source | Elementor widget settings (`get_settings_for_display()`). No HTTP client, no custom database tables, no REST routes. |
| Persistence of widget content | Elementor document data (WordPress post meta managed by Elementor). |
| Persistence of enable/disable | WordPress option `nexora_ele_widget_status` |
| Admin UI | Top-level menu **Nexora**, slug `nexora-for-elementor` |
| Capability | `manage_options` (`Nexora_Ele_Settings::capability()`) |
| Autoloader | None. Files are loaded with `require_once`. |
| Package manager | None in the repository. No `composer.json`, no `package.json`, no test runner, no linter config. |
| Tests | None. |
| Internationalization | `esc_html__()` / `__()` with text domain `nexora-elementor`. No `.pot` file is present. |

### CURRENT behavior in one sentence

Nexora catalogs widgets in `Nexora_Ele_Widget_Registry::all()`, registers every catalog class with Elementor, hides disabled widgets from the panel, skips their front-end output and assets, and keeps their saved instances so re-enabling restores them.

### What Nexora is not

**CURRENT.** There is no:

- A second widget category. Every widget is in the Nexora category.
- Shared design-system component library (no buttons, cards, modals, or tables as PHP/JS components).
- Authentication layer, API keys, tokens, or user-permission model beyond WordPress `manage_options` for the settings screen.
- Server-side “widget result” object. The output is HTML.
- Build script checked into the repo. `widgets/gtm-funnel/README.md` documents `npm install` and `npm run build`, but those files are absent. See section 27.

---

## 3. Current Project Architecture

```text
Nexora for Elementor
│
├── Plugin entry
│   └── nexora-for-elementor.php
│       class Nexora_For_Elementor
│
├── Widget system
│   ├── includes/class-widget-registry.php     catalog + assets metadata
│   ├── includes/class-widget-base.php         soft enable/disable
│   ├── includes/class-settings.php            option nexora_ele_widget_status
│   └── includes/class-admin-settings.php      Nexora → Widgets screen
│
├── Widgets
│   ├── widgets/class-ele-gtm-funnel.php
│   ├── widgets/gtm-funnel/                    controls, defaults, render
│   ├── widgets/lib/class-funnel-geometry.php  GTM-only geometry
│   ├── widgets/class-ele-timeline.php
│   └── widgets/timeline/                      controls, defaults, render
│
├── Front-end assets
│   ├── assets/css/nexora-ele-gtm-funnel.css
│   ├── assets/css/nexora-ele-timeline.css
│   ├── assets/css/nexora-ele-admin.css
│   ├── assets/js/nexora-ele-gtm-funnel.js
│   ├── assets/js/nexora-ele-timeline.js
│   ├── assets/js/nexora-ele-admin.js
│   ├── assets/icons/*.svg                     GTM packaged icons
│   └── assets/images/timeline/*.jpg           Timeline default images
│
├── Shared services / API client
│   └── none
│
├── State management library
│   └── none (Elementor settings + per-instance DOM state)
│
└── Tests
    └── none
```

### Plugin entry

**CURRENT.** `Nexora_For_Elementor` in `nexora-for-elementor.php`.

| Piece | Detail |
| --- | --- |
| Constants | `NEXORA_ELE_FILE`, `NEXORA_ELE_PATH`, `NEXORA_ELE_URL` |
| Version | `Nexora_For_Elementor::VERSION` = `1.1.1` |
| Boot | Constructor hooks `plugins_loaded` → `init()` |
| Admin | If `is_admin()`, calls `Nexora_Ele_Admin_Settings::init()` before the Elementor check |
| Elementor missing | `elementor_missing_notice()` for users who can `activate_plugins`, then `return` (widgets are not registered) |
| Assets | `register_assets()` on `wp_enqueue_scripts` |
| Editor preview | `enqueue_preview_styles()` and `enqueue_preview_scripts()` |
| Widgets | `register_widgets()` on `elementor/widgets/register` |

**Inputs:** WordPress and Elementor hooks.  
**Outputs:** Registered styles/scripts, registered Elementor widget instances, or an admin notice.  
**Reusable:** The class itself. Do not copy its methods into a widget.

### Widget system

**CURRENT.** Three collaborators:

1. **Registry** — declarative catalog. Source of widget id, title, description, PHP file, class name, `default_enabled`, `styles[]`, `scripts[]`.
2. **Settings** — reads and writes which ids are enabled.
3. **Base class** — `show_in_panel()`, `is_nexora_widget_enabled()`, `render_disabled_placeholder()`.

`register_widgets()` loads `includes/class-widget-base.php` only after Elementor has loaded, then `require_once`s each registry `file` and does `$widgets_manager->register( new $class() )`. Unreadable files and missing classes are skipped with no log.

Disabled widgets stay registered. The comment in `register_widgets()` states why: Elementor must keep page data. Hiding is `show_in_panel()`. Output and asset dependencies are skipped inside each widget.

### Data / API layer

**CURRENT.** There is no API layer.

```text
Elementor editor controls
        ↓
Saved widget settings (Elementor document)
        ↓
Widget::render() → get_settings_for_display()
        ↓
Widget render.php normalizes settings
        ↓
HTML (+ data-config JSON for the widget script)
        ↓
Widget JS reads data-config and binds behavior
```

### Services

**CURRENT.** The only shared “services” are settings and the registry. `Nexora_Funnel_Geometry` is a GTM-only calculator, not a plugin service.

### Shared utilities

**CURRENT.** There is no `includes/utils.php`. Small helpers live next to the widget that introduced them (`nexora_gtm_*`, `nexora_timeline_*`). Color sanitizing is duplicated. See section 32.

### UI

**CURRENT.** Each widget prints its own HTML in `render.php` and styles it with a scoped stylesheet. The admin screen is a separate PHP template in `Nexora_Ele_Admin_Settings::render_page()`.

### State

**CURRENT.**

| State | Where |
| --- | --- |
| Widget content | Elementor settings |
| Enabled / disabled | `get_option( 'nexora_ele_widget_status' )` |
| Active step / tier, timers, scroll position | Closure variables inside the widget IIFE |
| Editor re-render isolation | `AbortController` stored on the root element |

### Configuration

**CURRENT.** No `.env`. No feature-flag framework. The enable map is the only runtime configuration.

### Tests

**CURRENT.** No PHPUnit, Jest, Playwright, or wp-env config exists in this repository.

---

## 4. Repository Structure

**CURRENT** file map (source only; `.git` omitted):

```text
nexora-for-elementor/
├── nexora-for-elementor.php
├── README.md
├── includes/
│   ├── class-admin-settings.php
│   ├── class-settings.php
│   ├── class-widget-base.php
│   └── class-widget-registry.php
├── widgets/
│   ├── class-ele-gtm-funnel.php
│   ├── class-ele-timeline.php
│   ├── gtm-funnel/
│   │   ├── controls-content.php
│   │   ├── controls-style.php
│   │   ├── defaults.php
│   │   ├── render.php
│   │   └── README.md
│   ├── lib/
│   │   └── class-funnel-geometry.php
│   └── timeline/
│       ├── controls-content.php
│       ├── controls-style.php
│       ├── defaults.php
│       └── render.php
└── assets/
    ├── css/
    │   ├── nexora-ele-admin.css
    │   ├── nexora-ele-gtm-funnel.css
    │   └── nexora-ele-timeline.css
    ├── js/
    │   ├── nexora-ele-admin.js
    │   ├── nexora-ele-gtm-funnel.js
    │   └── nexora-ele-timeline.js
    ├── icons/
    │   ├── briefcase.svg
    │   ├── calendar.svg
    │   ├── mail.svg
    │   ├── message.svg
    │   ├── trophy.svg
    │   ├── user.svg
    │   └── users.svg
    └── images/timeline/
        └── step1_buyers_….jpg … step6_analytics_….jpg
```

**STANDARD** placement for a new widget named conceptually `{slug}` with Elementor name `ele-{slug}`:

```text
widgets/class-ele-{slug}.php
widgets/{slug}/defaults.php
widgets/{slug}/controls-content.php
widgets/{slug}/controls-style.php
widgets/{slug}/render.php
assets/css/nexora-ele-{slug}.css
assets/js/nexora-ele-{slug}.js          # only if the widget has front-end behavior
assets/images/{slug}/                   # only if the widget ships media
```

`widgets/lib/` is **WIDGET-SPECIFIC** today (funnel geometry only). Do not drop unrelated helpers there.

---

## 5. Existing Widget

### CURRENT catalog

Both definitions live in `Nexora_Ele_Widget_Registry::all()` and are filtered by `nexora_ele_widget_registry`.

#### ELE GTM Funnel

| Field | Value |
| --- | --- |
| Widget id / `get_name()` | `ele-gtm-funnel` |
| Title | ELE GTM Funnel |
| Class | `Nexora_GTM_Funnel_Widget` |
| Entry file | `widgets/class-ele-gtm-funnel.php` |
| Icon | `eicon-favorite` |
| Category | `nexora` |
| Keywords | `gtm`, `funnel`, `process`, `nexora`, `pipeline` |
| `default_enabled` | `true` |
| Style handles | `nexora-plus-jakarta-font` (external Google Fonts CSS), `nexora-ele-gtm-funnel` |
| Script handle | `nexora-ele-gtm-funnel` (depends on `jquery`, footer) |
| DOM root | `.nexora-ele-gtm-funnel` with `data-nexora-gtm-funnel` |
| Instance id | `nexora-gtm-{elementor_element_id}` in render scope as `$uid` |
| Elementor hook | `frontend/element_ready/ele-gtm-funnel.default` |
| Schema | Hidden control `_schema_version` default `'1'` |

#### ELE Timeline

| Field | Value |
| --- | --- |
| Widget id / `get_name()` | `ele-timeline` |
| Title | ELE Timeline |
| Class | `Nexora_Timeline_Widget` |
| Entry file | `widgets/class-ele-timeline.php` |
| Icon | `eicon-time-line` |
| Category | `nexora` |
| Keywords | `timeline`, `scroll`, `process`, `story`, `nexora` |
| `default_enabled` | `true` |
| Style handles | `nexora-plus-jakarta-font`, `nexora-ele-timeline` |
| Script handle | `nexora-ele-timeline` (depends on `jquery`, footer) |
| DOM root | `#nexora-tl-{id}.nexora-ele-timeline` with `data-nexora-timeline` |
| Instance id | `nexora-tl-{elementor_element_id}` |
| Elementor hook | `frontend/element_ready/ele-timeline.default` |
| Schema | Hidden control `_schema_version` default `'1'` |
| `get_custom_help_url()` | Not implemented (GTM returns `''`) |

**STANDARD.** `get_name()` must equal the registry key. `Nexora_Ele_Settings::is_widget_enabled( $this->get_name() )` looks up that key. A mismatch disables asset skipping and the panel toggle for the wrong id, or for no id.

---

## 6. Existing Widget Deep Dive

### 6.1 Shared render pipeline

**CURRENT.** Both widget classes follow the same method shape.

```text
register_controls()
    → nexora_{slug}_register_content_controls( $this )
    → nexora_{slug}_register_style_controls( $this )

render()
    → if disabled: render_disabled_placeholder(); return
    → $settings = get_settings_for_display()
    → $uid = prefix + sanitize_key( get_id() )
    → include widgets/{slug}/render.php   ($settings and $uid are in scope)
```

Neither class implements `content_template()`. Elementor renders these widgets with PHP (including in the editor), not a JS Underscore template.

`has_widget_inner_wrapper()` returns `false` on both (Elementor 3.3+ markup compatibility).

`get_style_depends()` and `get_script_depends()` return `[]` when the widget is disabled, and the handle string when it is enabled. Those handles must already be registered by `Nexora_For_Elementor::register_assets()` from the registry entry.

### 6.2 ELE GTM Funnel

**WIDGET-SPECIFIC** except where a row is marked otherwise.

#### Identity and files

| File | Responsibility |
| --- | --- |
| `widgets/class-ele-gtm-funnel.php` | Elementor identity, asset depends, control registration, render gate |
| `widgets/gtm-funnel/controls-content.php` | `nexora_gtm_funnel_register_content_controls()` |
| `widgets/gtm-funnel/controls-style.php` | `nexora_gtm_funnel_register_style_controls()` |
| `widgets/gtm-funnel/defaults.php` | Default steps, tiers, icon helpers |
| `widgets/gtm-funnel/render.php` | Markup, grouping, color/SVG sanitizing, `data-config` |
| `widgets/lib/class-funnel-geometry.php` | `Nexora_Funnel_Geometry::resolve()` |
| `assets/css/nexora-ele-gtm-funnel.css` | Scoped Tailwind v4.3.3 output plus custom properties |
| `assets/js/nexora-ele-gtm-funnel.js` | `initGtmFunnel()` |
| `assets/icons/*.svg` | Default step and node icons |
| `widgets/gtm-funnel/README.md` | Widget notes. Conflicts with the repo on npm (section 27). |

The class file `require_once`s the base, geometry, defaults, and both control files. The registry also `require_once`s the class file. `class_exists` / `function_exists` guards make the double include safe.

#### Input (Elementor settings)

There is no JSON Schema library. The schema is the Elementor control list. Validation is control types plus clamps inside `render.php`.

**Content — Schema**

| Control | Type | Default | Notes |
| --- | --- | --- | --- |
| `_schema_version` | hidden | `'1'` | Stored for future migrations. No migration code reads it. |

**Content — Layout** (`section_layout`)

| Control | Type | Default | Allowed / notes |
| --- | --- | --- | --- |
| `funnel_position` | select | `right` | `left`, `right` |
| `process_col_span` | select | `6` | `4`–`8`. Render clamps with `max(4, min(8, (int) …))`. |
| `funnel_col_span` | select | `6` | Same. Description says the two spans should total 12. The code does not enforce the sum. |
| `use_min_height` | switcher | `yes` | `'yes'` or empty |
| `mobile_stack_order` | select | `process_first` | `process_first`, `funnel_first` |
| `hide_funnel_mobile` | switcher | `''` | |
| `show_section_heading` | switcher | `''` | |
| `section_eyebrow` | text, dynamic | `''` | Condition: heading shown |
| `section_title` | text, dynamic | `''` | Condition: heading shown |
| `section_subtitle` | textarea, dynamic | `''` | Condition: heading shown |

**Content — Process Steps** repeater `process_steps`  
Default: `nexora_gtm_funnel_default_steps()` (4 steps, two groups: “OUR RESPONSIBILITY” and “YOUR ROLE”).  
Title field: `{{{ step_number }}} — {{{ step_title }}}`.

| Field | Type | Default | Notes |
| --- | --- | --- | --- |
| `group_heading` | text, dynamic | `''` | Non-empty starts a new group. Empty continues the previous group. Elementor has no nested repeater; this is the grouping mechanism. |
| `step_number` | text, dynamic | `01` | Printed in the badge. Not used as the tier key. |
| `step_icon` | icons | packaged `users.svg` via `nexora_gtm_default_icon()` | |
| `icon_frame` | switcher | `''` | `'yes'` wraps the icon |
| `icon_tone` | select | `accent` | `accent`, `muted` |
| `step_title` | text, dynamic | `''` | |
| `step_description` | textarea, dynamic | `''` | Omitted from DOM when empty |
| `badge_style` | select | `dark` | `accent`, `dark`, `blue`. Other values become `dark` at render. |
| `linked_tier` | number, min 1 | `1` | 1-based index into `funnel_tiers` |

Legacy key `step_icon_svg` is still read by `nexora_gtm_resolve_icon_html()` if the icons control does not resolve. It is not a current control.

**Content — Funnel Tiers** repeater `funnel_tiers`  
Default: `nexora_gtm_funnel_default_tiers()` (4 tiers).  
Title field: `{{{ tier_value }}} — {{{ tier_label }}}`.

| Field | Type | Default |
| --- | --- | --- |
| `tier_value` | text, dynamic | `1,000+` (repeater field default; catalog defaults differ per row) |
| `tier_label` | text, dynamic | `''` |
| `tier_sublabel` | text, dynamic | `''` |
| `gradient_start` | color | `#7325e8` |
| `gradient_mid` | color | `#8432f6` |
| `gradient_end` | color | `#8e37f8` |
| `show_node` | switcher | `yes` |
| `node_color` | color | `#6d26e4` (only if node shown) |
| `node_icon` | icons | packaged `users.svg` (only if node shown) |

Elementor also stores repeater row `_id`. Render uses `_id` as `data-tier` when present, otherwise the numeric index.

**Content — Interaction** (`section_interaction`)

| Control | Type | Default | Render / JS |
| --- | --- | --- | --- |
| `interaction_mode` | select | `hover` | Clamped to `hover`, `click`, `both` |
| `default_active` | select | `none` | `none`, `first`, `specific` |
| `default_active_index` | number, min 1 | `1` | Shown when `default_active` is `specific`. Render uses `max(1, (int) …)`. |
| `dim_inactive` | switcher | `yes` | Boolean in `data-config` |
| `auto_rotate` | switcher | `''` | |
| `rotate_interval` | number, min 1000, step 100 | `3000` | Render `max(1000, (int) …)`. JS also rejects intervals under 1000 and uses 3000. |
| `enable_keyboard` | switcher | `yes` | When off, cards/tiers are not given `role="button"` or `tabindex` |

**Style controls** write CSS variables or Elementor selectors. They are numerous. Names that render.php reads directly (not only via CSS selectors):

| Control | Used for |
| --- | --- |
| `top_width`, `bottom_width`, `top_y`, `tier_height`, `tier_gap`, `curve_depth`, `node_offset`, `node_radius` | `Nexora_Funnel_Geometry::resolve()` |
| `card_bg_start`, `card_bg_mid`, `card_bg_end` | Inline `--ngtm-card-bg` |
| `card_active_bg_start`, `card_active_bg_mid`, `card_active_bg_end` | Inline `--ngtm-card-active-bg` |
| `glow_dy`, `glow_blur`, `glow_color`, `glow_opacity` | SVG `feDropShadow` |

Other style controls (`wrapper_padding`, badge colors, typography groups, `funnel_max_width`, tier type scales, and so on) target `{{WRAPPER}}` or `.nexora-gtm__*` through Elementor selectors. See `controls-style.php`.

**Edge cases implemented in render**

- Non-array settings become `[]`.
- Empty `$uid` becomes `nexora-gtm-` + `wp_unique_id()`.
- Non-array repeater rows are dropped.
- Empty steps or tiers fall back to the PHP default arrays (the widget does not render an empty state).
- `wp_json_encode` failure becomes `'{}'`, and JS then applies its own defaults.
- Colors that fail `nexora_safe_color()` fall back to hard-coded hex values.
- Geometry throws are caught inside `Nexora_Funnel_Geometry::resolve()` and replaced with the 4-tier preset.
- Tier count is clamped to 1–24 inside `resolve()`.
- Four tiers plus default geometry uses the hand-tuned `preset_four()` paths. Any other count, or any geometry slider that differs from `Nexora_Funnel_Geometry::defaults()`, uses `generate()`.

#### Processing

```text
Elementor settings
    ↓
render() disabled check
    ↓
render.php
    ↓ sanitize colors, clamp spans, clamp interaction enums
    ↓ group steps on non-empty group_heading
    ↓ resolve linked_tier → tier _id (or index)
    ↓ Nexora_Funnel_Geometry::resolve( count(tiers), geometry settings )
    ↓ nexora_gtm_resolve_icon_html() for step and node icons
    ↓ HTML + SVG
    ↓ data-config JSON
    ↓
nexora-ele-gtm-funnel.js initGtmFunnel()
    ↓ class toggles: active, dimmed
```

Icon resolution order in `nexora_gtm_resolve_icon_html()`:

1. Icons control, library `svg`, attachment id via `get_attached_file()`.
2. Icons control URL that starts with `{NEXORA_ELE_URL}assets/icons/`, read from disk and passed through the SVG allowlist.
3. `\Elementor\Icons_Manager::render_icon()`.
4. Legacy `step_icon_svg` / `node_icon_svg` string, allowlisted.
5. Empty string.

SVG allowlist in render strips `<script>`, `on*` attributes, and `foreignObject`, `iframe`, `embed`, `object`, `link`, `meta`. The funnel node then wraps the cleaned icon in its own `<foreignObject>` so the icon can sit in the SVG. That wrapper is plugin markup, not user SVG.

#### UI

| Piece | Implementation |
| --- | --- |
| Root | `div.nexora-ele-gtm-funnel.nexora-gtm__wrapper` |
| Optional heading | `.nexora-gtm__section-heading` |
| Process column | `section.nexora-gtm__process` with `aria-label` “Strategy Execution Steps” |
| Groups | `.nexora-gtm__group` / `.nexora-gtm__group-heading` / `.nexora-gtm__group-steps` |
| Card | `article.nexora-gtm__card.process-card` with `data-tier` |
| Funnel | `section.nexora-gtm__funnel-col`, inline SVG, `g.nexora-gtm__tier.funnel-tier` |
| Loading | None. HTML is server-rendered. |
| Empty | Not shown. Defaults are substituted. |
| Error | No error UI. Missing render file returns nothing. Disabled widget shows the editor placeholder only. |
| Interactions | Hover, click, both, keyboard arrows, Escape, optional auto-rotate. JS in `initGtmFunnel()`. |
| Navigation | None. Click `preventDefault()`s so cards do not navigate. |
| Styling | CSS variables `--ngtm-*` on the widget. Breakpoints 640px and **1025px**. |
| Responsive | One column below 1025px. Twelve-column grid at `min-width: 1025px`. Classes `nexora-gtm--funnel-left`, `nexora-gtm--stack-funnel-first`, `nexora-gtm--hide-funnel-mobile`, `nexora-gtm--no-min-height`. |

Legacy class names `process-card`, `funnel-tier`, and `card-body` are kept because the script queries `.process-card, .nexora-gtm__card` and `.funnel-tier, .nexora-gtm__tier`.

#### Front-end config and behavior

`data-config` object written by PHP:

```json
{
  "mode": "hover",
  "defaultActive": "none",
  "defaultIndex": 1,
  "dimInactive": true,
  "autoRotate": false,
  "rotateInterval": 3000,
  "enableKeyboard": true
}
```

JS defaults in `parseConfig()` match those keys. Invalid JSON keeps the JS defaults.

`initGtmFunnel()`:

- Aborts a previous `AbortController` on `root._nexoraGtmAbort` before rebinding (editor re-renders and multi-instance safety).
- Sets `.active` and `.dimmed` on cards and tiers that share `data-tier`.
- Sets `aria-pressed` only when `enableKeyboard` is true.
- `hover` ignores click activation. `click` ignores mouseenter. `both` allows hover until a click locks a tier; clicking the locked tier again unlocks.
- Arrow keys move the active key and lock it. Escape clears.
- Auto-rotate starts unless `prefers-reduced-motion: reduce`, fewer than 2 keys, or `autoRotate` is false. Mouseenter pauses it.
- Failures are swallowed. The script is written so a widget error does not break the rest of the page.

Elementor hook: `elementorFrontend.hooks.addAction( 'frontend/element_ready/ele-gtm-funnel.default', … )`.

#### Output

The widget does not return a PHP DTO. It prints HTML. The meaningful machine-readable output is:

| Output | Shape |
| --- | --- |
| Wrapper classes | Position, min-height, stack, hide-funnel modifiers |
| Inline style | `--ngtm-process-span`, `--ngtm-funnel-span`, `--ngtm-glow`, card gradients |
| `data-instance` | `nexora-gtm-{id}` |
| `data-config` | JSON above |
| SVG ids | `grad-{uid}-{index}`, `active-glow-{uid}`, `node-shadow-{uid}` so multiple instances do not collide |
| Tier key | Repeater `_id` or numeric index |
| Card key | Same tier key via `linked_tier` |

### 6.3 ELE Timeline

**WIDGET-SPECIFIC** except the shared pipeline in 6.1.

#### Files

| File | Responsibility |
| --- | --- |
| `widgets/class-ele-timeline.php` | Identity and render gate |
| `widgets/timeline/controls-content.php` | `nexora_timeline_register_content_controls()` |
| `widgets/timeline/controls-style.php` | `nexora_timeline_register_style_controls()` |
| `widgets/timeline/defaults.php` | Default items, media URLs, `nexora_timeline_slider_css()`, `nexora_timeline_safe_color()`, `nexora_timeline_build_root_styles()` |
| `widgets/timeline/render.php` | Item normalization and markup |
| `assets/css/nexora-ele-timeline.css` | Hand-written scoped CSS (not Tailwind output) |
| `assets/js/nexora-ele-timeline.js` | `initTimeline()` |
| `assets/images/timeline/*.jpg` | Six default photographs |

#### Input

**Content — Schema:** `_schema_version` hidden, default `'1'`. Same as GTM. Nothing reads it.

**Content — Timeline Items** repeater `timeline_items`  
Default: `nexora_timeline_default_items()` (6 steps).  
Title field: `{{{ item_number }}} — {{{ item_title }}}`.

| Field | Type | Default | Notes |
| --- | --- | --- | --- |
| `item_number` | text, dynamic | `01` | Display text, also copied into `data-step-number` |
| `item_title` | text, dynamic | “Timeline Step” | |
| `item_description` | textarea, dynamic | `''` | Paragraph omitted when empty |
| `item_image` | media | first bundled buyers image for the field default; each default row has its own file | URL escaped with `esc_url` |
| `item_link` | url, dynamic | empty url | |
| `item_class` | text | `''` | Passed through `sanitize_html_class()` |
| `item_number_color` | color | empty | Per-item CSS variable |
| `item_number_color_active` | color | empty | |
| `item_title_color` | color | empty | |
| `item_title_color_active` | color | empty | |
| `item_desc_color` | color | empty | |

There is no per-item active description color. Global active description color is a style control only.

**Style — Layout** (`section_style_layout`, tab Style)

| Control | Type | Default | Notes |
| --- | --- | --- | --- |
| `section_background_color` | color | `#0a0a0a` | Selector sets `--nexora-tl-bg` and `background-color` |
| `section_max_width` | responsive slider | `1280px` | |
| `section_padding` | responsive dimensions | none | Selector uses `!important` |
| `column_gap` | responsive slider | none | Sets `--nexora-tl-gap` and `--nexora-tl-gap-lg` |
| `left_col_span` | select | `6` | `4`–`8`. **`render_type` = `template`** |
| `right_col_span` | select | `6` | Same |
| `sticky_top` | responsive slider | `0px` | CSS variable `--nexora-tl-visual-offset`. Label is “Visual Align Offset”. It is not `position: sticky`. |
| `steps_spacing` | responsive slider | none | `--nexora-tl-step-gap` |

**Style — Image Panel:** `image_panel_max_width`, `image_panel_bg`, `image_panel_radius`, border group, shadow group, `image_object_fit` (`cover` / `contain` / `fill` checked in `nexora_timeline_build_root_styles()`), `image_opacity`, `image_overlay_color`, `image_overlay_opacity`.

**Style — Large Active Number:** `show_large_active_number` (default `yes`, `render_type` template), fill, stroke, stroke width, opacity, margin, align. Typography group controls exist in this section.

**Style — Timeline Line:** `track_color`, `progress_color`, `line_width`, `line_left`.

**Style — Timeline Marker:** `marker_size`, `marker_bg`, `marker_bg_active`, `marker_border_color`, `marker_border_width`, `marker_glow`.

**Style — Item Number / Title / Description:** color, active color, typography groups, title margin, description max width and margin.

**Style — Animation**

| Control | Type | Default | `render_type` |
| --- | --- | --- | --- |
| `enable_interactions` | switcher | `yes` | `template` |
| `image_swap_delay` | number 0–1000 | `150` | `template` |
| `progress_duration` | slider seconds | `0.35s` | CSS variable only |
| `image_transition_duration` | slider seconds | `0.4s` | CSS variable only |

#### Processing

```text
settings[timeline_items] or nexora_timeline_default_items()
    ↓ drop non-arrays
    ↓ map to { id, number, title, description, image, link, class, colors }
    ↓ if items empty: return (no HTML)
    ↓ clamp column spans to 4|5|6|7|8
    ↓ clamp swap delay to 0–1000
    ↓ data-config { enabled, swapDelay }
    ↓ on the public front end only, inline style from nexora_timeline_build_root_styles()
    ↓ HTML
    ↓ initTimeline()
```

`id` is `index + 1`, not the Elementor repeater `_id`.

Inline CSS variables are **omitted** when `\Elementor\Plugin` reports edit mode or preview mode, so Elementor’s selector CSS can update live. The comment in `render.php` states that inline vars would block those live updates. On the public front end, the inline style is a fallback.

#### Internal item model

Built only inside `render.php`. It is not a shared type.

| Key | Source |
| --- | --- |
| `id` | 1-based loop index |
| `number` | `item_number`, or `sprintf( '%02d', index + 1 )` |
| `title` | `item_title` |
| `description` | `item_description` |
| `image` | `esc_url` of `item_image.url` |
| `link` | `item_link` array or `[]` |
| `class` | `sanitize_html_class` |
| `colors.num`, `num_active`, `title`, `title_active`, `desc` | `nexora_timeline_safe_color()` (local closure, same rules as `nexora_timeline_safe_color()`) |

#### UI

| Piece | Implementation |
| --- | --- |
| Root | `div#nexora-tl-….nexora-ele-timeline` |
| Desktop visual | `.nexora-ele-timeline__left` hidden below 1025px. Image in `.nexora-ele-timeline__desktop-image`. JS sets `translateY` so the panel sits beside the active step. It is not viewport-sticky. |
| Large number | `[data-active-num]`, `aria-hidden="true"` |
| Steps | `.timeline-step` with `role="button"`, `tabindex="0"`, `aria-label` = title |
| Track | `.timeline-track-line`, `.timeline-progress-line` (`aria-hidden`) |
| Mobile image | `.nexora-ele-timeline__mobile-image`. Inactive steps get `is-hidden`. Hidden with `display: none !important` from 1025px up. |
| First step | Rendered with class `active`. Mobile image visible. |
| Link | If `item_link.url` is set, the title is an `<a class="step-title-link">`. External adds `target="_blank"`. `nofollow` adds `rel="nofollow"`. External without nofollow adds `rel="noopener noreferrer"`. |
| Loading | None |
| Empty | **No markup at all** if every row is invalid or the list is empty after the default fallback |
| Error | No error UI |

#### Front-end behavior (`initTimeline`)

`data-config`:

```json
{ "enabled": true, "swapDelay": 150 }
```

- If `enabled` is false, the function returns immediately. Steps stay as server-rendered (first item active). Scroll and click do nothing.
- Abort controller: `root._nexoraTlAbort`.
- Desktop breakpoint in JS: `matchMedia('(min-width: 1025px)')`, fallback `innerWidth >= 1025`.
- Scroll: on each window `scroll` (passive), pick the visible step whose center is closest to `innerHeight * 0.4`, among steps that intersect the band `15%`–`80%` of the viewport.
- Progress height: `((id - 1) / (total - 1)) * 90` percent. One step stays at `0%`.
- Image swap: opacity 0 and `scale(0.97)`, then after `swapDelay` set `src` and alt from `.step-title`, then clear inline opacity/transform after 450ms so the Style opacity control applies again.
- Click and Enter/Space call `setActiveStep` and `scrollIntoView`. Clicks on `a.step-title-link` do not activate the step.
- `requestAnimationFrame` coalesces visual alignment. Resize listens passively.
- `prefers-reduced-motion` is handled in CSS (`transition: none`), not in the scroll logic.

### 6.4 Admin widgets screen

**CURRENT.** Not a front-end widget. It is the catalog UI.

- Page slug `nexora-for-elementor`.
- Menu position `58`, icon `dashicons-screenoptions`.
- Submenu “Widgets” points at the same slug.
- Save: nonce action `nexora_ele_save_widgets`, field `nexora_ele_settings_nonce`.
- POST `nexora_ele_widgets[id] = 1` for checked cards. Missing ids are saved as `'0'` because `save_status_from_request()` iterates `Nexora_Ele_Widget_Registry::ids()`.
- After save, Elementor `files_manager->clear_cache()` runs when Elementor is loaded, then a redirect with `nexora-ele-saved=1`.
- `widget_icon_svg()` returns a timeline glyph for `ele-timeline` and a funnel glyph for every other id. New widgets inherit the funnel glyph until this function is edited.
- Admin JS (`assets/js/nexora-ele-admin.js`) only toggles card classes, status text, the count pill, and enable-all / disable-all. It does not save.

---

## 7. Reusable Foundation

**CURRENT** pieces every new widget should use as-is.

| Piece | Path | Reuse how |
| --- | --- | --- |
| Plugin bootstrap | `nexora-for-elementor.php` | Do not fork. Add assets only through the registry so `register_assets()` picks them up. |
| Widget base | `includes/class-widget-base.php` `Nexora_Ele_Widget_Base` | `extends Nexora_Ele_Widget_Base` |
| Registry | `includes/class-widget-registry.php` | Add one array entry |
| Enablement | `includes/class-settings.php` | No widget code required if the registry id matches `get_name()` |
| Admin list | `includes/class-admin-settings.php` | Renders whatever the registry returns |
| Asset URL/version helpers | `Nexora_For_Elementor::asset_src()`, `asset_ver()` | Private. Feed them via registry `styles` / `scripts` arrays. |
| Soft-disable placeholder | `render_disabled_placeholder()` | Call it at the start of `render()` |
| Extension filter | `nexora_ele_widget_registry` | Optional. In-repo widgets are added to the array, not via the filter. |
| Text domain | `nexora-elementor` | All new strings |
| Path constants | `NEXORA_ELE_FILE`, `NEXORA_ELE_PATH`, `NEXORA_ELE_URL` | Asset and media URLs |
| Shared font handle | `nexora-plus-jakarta-font` | Reuse the handle if the widget uses Plus Jakarta Sans. `register_assets()` de-duplicates handles. |
| Security primitives | `ABSPATH` guard, nonces, `current_user_can`, `esc_html` / `esc_attr` / `esc_url`, `sanitize_key`, `sanitize_html_class`, `wp_json_encode` | Copy the habit, not a new wrapper, until a shared helper exists |
| Editor re-init pattern | AbortController + `frontend/element_ready/{name}.default` | Copy the structure into the new script. Do not share the GTM or timeline closure. |
| Breakpoint | `1025px` as the large layout | Both stylesheets use it so Elementor’s 1024px tablet breakpoint does not steal the desktop layout |

### Registry asset shape

**CURRENT.** Each style or script is an array:

| Key | Required | Meaning |
| --- | --- | --- |
| `handle` | yes | WordPress handle. Empty handles are skipped. |
| `src` | yes | Plugin-relative path, or an absolute URL |
| `deps` | no | Array of handles. Default `[]` |
| `ver` | no | Omitted or `true` → plugin version. `null` → WordPress treats version as null (used for the Google font). Any other value is passed through. |
| `external` | no | `true` forces the `src` to be used as-is. URLs matching `^(https?:)?//` are also treated as external. |
| `in_footer` | scripts only | `true` prints in the footer. Both widget scripts set this. |

---

## 8. Widget-Specific Logic

| Code / Component | Current Purpose | Reusable? | Future Recommendation |
| --- | --- | --- | --- |
| `Nexora_Ele_Widget_Registry` | Catalog | Yes | Add entries. Do not replace the class. |
| `Nexora_Ele_Widget_Base` | Soft disable | Yes | Extend it. Do not duplicate `show_in_panel()`. |
| `Nexora_Ele_Settings` | Enable option | Yes | Do not give widgets their own enable options. |
| `Nexora_Ele_Admin_Settings` | Settings UI | Yes | Leave it. Icon map is the exception below. |
| `Nexora_For_Elementor::register_assets()` | Conditional asset registration | Yes | Declare assets in the registry only. |
| `widget_icon_svg()` | Admin card icon | Partial | **RECOMMENDED:** move an `icon_svg` (or dashicon) onto the registry entry when the admin screen is next edited. Until then, new widgets show the funnel icon. |
| `Nexora_GTM_Funnel_Widget` | GTM Elementor class | No | Copy the method list, not the strings. |
| `nexora_gtm_funnel_register_*` | GTM controls | No | New widget gets its own functions. |
| `nexora_gtm_funnel_default_steps()` / `_tiers()` | GTM marketing copy | No | Do not reuse the copy or the 4-step model. |
| `nexora_gtm_default_icon()` | Packaged SVG as an Elementor ICONS value | No, unless the new widget uses `assets/icons/` the same way | **RECOMMENDED:** extract only when a third widget needs packaged SVG icons. |
| `nexora_gtm_resolve_icon_html()` | Inline plugin SVGs and fall back to Icons Manager | No | Same as above. |
| `Nexora_Funnel_Geometry` | 4-tier preset and parametric funnel paths | No | Leave under `widgets/lib/` until something else truly needs funnel geometry. |
| GTM `render.php` grouping, SVG, glow filters | Funnel markup | No | |
| `assets/css/nexora-ele-gtm-funnel.css` | Compiled Tailwind plus `--ngtm-*` | No | Do not paste this file into a new widget. There is no Tailwind source in the repo. |
| `initGtmFunnel()` | Hover/click/rotate | No | Copy only the init/abort/Elementor-hook shell. |
| `assets/icons/*.svg` | GTM icon set | Only if the design uses those metaphors | |
| `Nexora_Timeline_Widget` | Timeline Elementor class | No | Same as GTM: copy the skeleton. |
| `nexora_timeline_default_items()` | Six content-marketing steps and JPGs | No | |
| `nexora_timeline_image_url()` / `nexora_timeline_media_default()` | Bundled timeline images | No | A widget with bundled images may copy the two-function idea. |
| `nexora_timeline_slider_css()` | Slider array → CSS size | Not shared yet | **RECOMMENDED:** extract when a third widget builds inline CSS from sliders. |
| `nexora_timeline_safe_color()` and the closure in GTM `render.php` | Color allowlist | Duplicated, not shared | **RECOMMENDED:** one shared sanitizer when the next widget needs inline colors. Do not build it preemptively in a drive-by refactor. |
| `nexora_timeline_build_root_styles()` | Front-end CSS variable fallback | No | The *idea* (Elementor selectors for the editor, optional front-end fallback) is **STANDARD**. The variable map is timeline-only. |
| `initTimeline()` scroll math | Scroll-linked image | No | |
| Timeline photographs | Default art | No | |
| Admin CSS/JS | Settings screen | Yes for the screen | Do not enqueue admin assets on the front end. |
| Google Fonts URL | Plus Jakarta Sans | Shared handle already | Reuse the handle. Do not add a second font `<link>` with a new handle for the same family. |
| `_schema_version` | Reserved integer-as-string | Pattern yes, value per widget | Each widget owns its version. Do not invent migrations until a real schema change ships. |
| `data-config` JSON | PHP → JS contract | Pattern yes | Each widget defines its own keys. |
| Column spans `4`–`8` on a 12-column grid at 1025px | Layout choice used by both | Pattern yes, when the widget is a two-column section | Not mandatory for a single-column widget. |

---

## 9. Standard Widget Architecture

**STANDARD.** Keep the layout the repository already uses. Do not introduce a `Core/` and `Widgets/` tree, a service container, or a JavaScript framework.

```text
nexora-for-elementor.php          plugin entry, asset registration, Elementor hook
includes/                         shared plugin infrastructure only
    class-widget-registry.php
    class-widget-base.php
    class-settings.php
    class-admin-settings.php
widgets/
    class-ele-{slug}.php          Elementor subclass
    {slug}/
        defaults.php              default repeater rows and tiny helpers
        controls-content.php      Content tab
        controls-style.php        Style tab
        render.php                HTML
assets/css/nexora-ele-{slug}.css
assets/js/nexora-ele-{slug}.js    omit if there is no behavior
```

### Why this shape

**CURRENT** evidence:

- The registry comment says: “Add new widgets here only — bootstrap + settings read from this registry.”
- Both widgets already split class / content controls / style controls / defaults / render.
- Shared code is loaded from `includes/` by the bootstrap. Widget files `require_once` their own partials.
- There is no autoloader, so a new namespace or PSR-4 tree would be a new architecture. Do not add one in a widget task.

### What does not belong in `includes/`

**STANDARD.**

- Control definitions for one widget.
- Default marketing content.
- SVG path math for one graphic.
- Widget DOM templates.

Put those under `widgets/{slug}/`.

---

## 10. Widget Contract

**STANDARD.** Every new widget has the following. Names map to this codebase, not to a generic “handler/service/API” stack.

```text
Widget
├── Identity            get_name, get_title, registry entry
├── Input               Elementor controls
├── Defaults            defaults.php
├── Normalization       top of render.php
├── Markup              rest of render.php
├── Behavior            assets/js, only if needed
├── Style               assets/css + Style controls
├── Disabled state      base class placeholder
├── Empty / fallback    explicit, per widget
└── Manual verification no automated suite exists yet
```

### Identity

**STANDARD.**

- Registry key, `get_name()`, style/script handle suffix, and folder slug align.
- Pattern used today: id `ele-{slug}`, class file `widgets/class-ele-{slug}.php`.
- GTM class is `Nexora_GTM_Funnel_Widget`. Timeline class is `Nexora_Timeline_Widget`.
- **STANDARD for new classes:** `Nexora_{StudlySlug}_Widget` in the global namespace (there is no PHP namespace). Example: slug `pricing-table` → `Nexora_Pricing_Table_Widget`, id `ele-pricing-table`.
- `get_title()` is wrapped in `esc_html__( …, 'nexora-elementor' )`.
- `get_icon()` is an Elementor `eicon-*` slug.
- `get_categories()` returns `[ 'nexora' ]`. The plugin registers that category as **Nexora** in the Elementor panel.
- `get_keywords()` includes `'nexora'` plus widget words.
- `has_widget_inner_wrapper()` returns `false`.

### Input

**STANDARD.**

- Content controls in `controls-content.php`, function `nexora_{slug_underscores}_register_content_controls( $widget )`.
- Style controls in `controls-style.php`, function `nexora_{slug_underscores}_register_style_controls( $widget )`.
- Both functions bail out unless `$widget instanceof \Elementor\Widget_Base`.
- Guard with `function_exists` the way both widgets do.
- Repeaters for lists. Elementor cannot nest repeaters; GTM uses an empty-versus-filled `group_heading` instead. Copy that only if you need groups inside one repeater.
- Dynamic tags (`'dynamic' => [ 'active' => true ]`) on text, textarea, and URL fields that should accept Elementor dynamic content. Both widgets do this on user-facing copy.
- A hidden `_schema_version` control defaulting to `'1'` on new widgets.

### Validation

**CURRENT / STANDARD.** There is no schema library. Validation is:

1. Elementor control `type`, `options`, `min`, `max`, and `condition`.
2. Defensive checks in `render.php`: `is_array`, allowlists, numeric clamps, `sanitize_html_class`, color allowlist, `esc_*` on output.
3. JS `parseConfig()` merging JSON over hard-coded defaults and ignoring parse errors.

Do not trust control defaults alone. Render output that becomes an attribute, class, or style must be allowlisted or escaped.

Controls that change PHP markup (column span, show/hide blocks, anything read in `render.php` rather than a CSS selector) set `'render_type' => 'template'`. Timeline does this for `left_col_span`, `right_col_span`, `show_large_active_number`, `enable_interactions`, and `image_swap_delay`. GTM style-tab geometry sliders do **not**. That is a GTM gap, not the pattern to copy. See section 35.

### Data

**STANDARD.** Read `$settings` inside `render.php`. Do not call `get_settings_for_display()` again if the class already passed `$settings`. Do not add `wp_remote_get` unless the widget’s requirements say so. If a future widget does need HTTP, that is a new architecture decision (section 32) and must stay server-side in PHP.

### Logic

**STANDARD.**

| Logic | Where |
| --- | --- |
| Control registration | `controls-*.php` |
| Default content | `defaults.php` |
| Markup branching, allowlists, grouping | `render.php` |
| Non-trivial calculation | A class next to the widget, following `Nexora_Funnel_Geometry`, only when `render.php` would become opaque |
| DOM behavior | The widget JS file |
| Colors, spacing, type | CSS variables set by Elementor selectors |

### Output

**STANDARD.** Echo escaped HTML. Pass behavior flags as `data-config` JSON from `wp_json_encode`. Namespace SVG ids and DOM ids with `$uid`.

### UI

**STANDARD.** One root element per instance:

- Class `nexora-ele-{slug}` (and BEM children `nexora-ele-{slug}__element`).
- Data attribute `data-nexora-{slug}` so the script can query roots.
- `id="<?php echo esc_attr( $uid ); ?>"` when the widget has generated ids.

GTM uses `nexora-gtm__*` as a second prefix. Timeline uses `nexora-ele-timeline__*` plus a few legacy names (`timeline-step`, `step-title`). **STANDARD for new widgets:** one BEM prefix, `nexora-ele-{slug}`. Do not add a second legacy prefix.

### Errors

**STANDARD.** Follow current behavior:

- Disabled: editor placeholder, empty string on the front end (`render_disabled_placeholder()` returns before any front-end HTML).
- Missing partial: widget class returns without output if `render.php` is not readable.
- Bad JSON: JS keeps defaults.
- Do not `die()`, do not `wp_die()` inside `render()`, and do not print PHP warnings into the widget.

There is no error component. A user-facing failure message is widget-specific markup, not a shared toast.

### Testing

**CURRENT.** No automated tests. **STANDARD** until a harness exists: the manual list in section 24. **RECOMMENDED:** add PHPUnit later for registry id parity and pure functions such as geometry. Do not block a widget on a test framework that is not in the repo.

---

## 11. Widget Registration

### CURRENT mechanism

1. `Nexora_Ele_Widget_Registry::all()` returns the catalog (cached in a static property for the request).
2. `apply_filters( 'nexora_ele_widget_registry', $definitions )` can alter it.
3. On `elementor/widgets/register`, the plugin requires `class-widget-base.php`, then each `file`, then `new $class()`.
4. On `wp_enqueue_scripts`, styles and scripts of **enabled** widgets are registered.
5. Elementor enqueues a widget’s dependencies when the document uses that widget, via `get_style_depends()` / `get_script_depends()`.
6. Elementor preview enqueues every enabled widget’s assets even if the current document does not use them (`enqueue_preview_styles` / `enqueue_preview_scripts`).

Widget id rules that are visible in the code:

- Keys are `ele-gtm-funnel` and `ele-timeline` (lowercase, hyphenated, `ele-` prefix).
- `Nexora_Ele_Settings` runs ids through `sanitize_key()` (lowercase, hyphens become nothing **only for characters outside** `[a-z0-9_-]`). Hyphens survive `sanitize_key`. Underscores would too. The existing ids use hyphens. **STANDARD:** `ele-` + lowercase hyphenated slug. No spaces.

Discovery is not filesystem scanning. A widget file that is not in the registry is never loaded.

There is no `elementor/elements/categories_registered` callback, no manifest JSON, and no build step that registers widgets.

### How to Register a New Widget

**STANDARD.** Touch these files.

1. Create the widget class and partials (section 30).
2. Add one entry to the `$definitions` array in `includes/class-widget-registry.php`.
3. If the widget should not appear in the admin icon set as a funnel, edit `Nexora_Ele_Admin_Settings::widget_icon_svg()` **or** leave the default funnel icon. There is no registry field for the admin icon today.
4. Do not edit `nexora-for-elementor.php` unless the widget needs a plugin-level hook that the registry cannot express. Assets do not belong there.
5. Do not edit the other widget’s class, controls, CSS, or JS.

Registry entry to copy and edit:

```php
'ele-example' => [
    'id'              => 'ele-example',
    'title'           => __( 'ELE Example', 'nexora-elementor' ),
    'description'     => __( 'One sentence for the Nexora widgets screen.', 'nexora-elementor' ),
    'file'            => 'widgets/class-ele-example.php',
    'class'           => 'Nexora_Example_Widget',
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
            'handle' => 'nexora-ele-example',
            'src'    => 'assets/css/nexora-ele-example.css',
            'deps'   => [ 'nexora-plus-jakarta-font' ],
            'ver'    => true,
        ],
    ],
    'scripts'         => [
        [
            'handle'    => 'nexora-ele-example',
            'src'       => 'assets/js/nexora-ele-example.js',
            'deps'      => [ 'jquery' ],
            'ver'       => true,
            'in_footer' => true,
        ],
    ],
],
```

Omit the `scripts` key, or use an empty array, when there is no JavaScript. Omit the font entry when the widget does not use Plus Jakarta Sans.

`default_enabled => true` matches both current widgets. `Nexora_Ele_Settings::is_widget_enabled()` uses that default when the option was never saved, and when the id is missing from a previously saved map (“Newly added widget after a plugin update”). A new widget therefore turns on after an update without a settings migration.

`get_name()`, `get_style_depends()`, and `get_script_depends()` in the class must use the same id and handles.

---

## 12. Input Schema

### CURRENT pattern

Inputs are Elementor controls, not a separate schema file. Defaults live in the control array and, for repeaters, in `defaults.php` so the default rows stay in one place.

Switcher values are the string `'yes'` or empty, because `'return_value' => 'yes'` is what both widgets use. Render checks `=== 'yes'`.

### Where the schema lives

**STANDARD.**

| Concern | File |
| --- | --- |
| Control types, conditions, panel labels | `widgets/{slug}/controls-content.php` and `controls-style.php` |
| Repeater default rows | `widgets/{slug}/defaults.php` |
| Runtime clamps and allowlists | `widgets/{slug}/render.php` |
| JS fallback defaults | `parseConfig()` in the widget script |

Do not add `*.schema.json` unless a future task introduces a consumer for it. Nothing reads JSON Schema today.

### Real example (CURRENT) — Timeline item

The smallest complete input in the repo is one `timeline_items` row from `nexora_timeline_default_items()`:

```php
[
    'item_number'      => '01',
    'item_title'       => 'Understand Your Business & Buyers',
    'item_description' => 'We uncover what your audience is searching for before creating a single piece of content.',
    'item_image'       => [
        'url' => '.../assets/images/timeline/step1_buyers_1787294348844.jpg',
        'id'  => '',
    ],
    'item_link'        => [ 'url' => '', 'is_external' => '', 'nofollow' => '' ],
    'item_class'       => '',
]
```

GTM’s real list input is the pair of repeaters `process_steps` and `funnel_tiers` documented in section 6.2, including `linked_tier` as a 1-based number rather than a foreign key.

### Field conventions

**STANDARD**, taken from the controls that already exist.

| Need | Control |
| --- | --- |
| Short copy | `Controls_Manager::TEXT`, `label_block` true, dynamic on |
| Long copy | `TEXTAREA`, dynamic on |
| On/off | `SWITCHER`, `return_value` `yes` |
| Small enum | `SELECT` with an explicit options array |
| Integer | `NUMBER` with `min` (and `max` when the render clamp has one) |
| Color | `COLOR` |
| Icon | `ICONS` |
| Image | `MEDIA` |
| Link | `URL` with `is_external` and `nofollow` |
| List | `REPEATER` |
| CSS length | `SLIDER` or responsive slider, with `size_units` |
| Spacing box | `DIMENSIONS` |
| Typography | Elementor group control (both widgets use group controls in style files) |
| Panel note | `RAW_HTML` (GTM geometry note) |
| Reserved migration flag | `HIDDEN` `_schema_version` |

**Not present, so do not invent a shared control for them:**

| Need | Status |
| --- | --- |
| Pagination | **UNKNOWN** as a Nexora pattern. Neither widget pages data. |
| Sorting | **UNKNOWN.** Repeater order is the order. |
| Search | **UNKNOWN.** No search input. |
| Date fields | **UNKNOWN.** No date control. |
| Nested objects beyond repeater rows | Not supported by Elementor repeaters. GTM flattens groups with `group_heading`. |

### Recommended Future Pattern

Label: **RECOMMENDED.** This is not a third widget in the repo.

```php
$widget->add_control(
    '_schema_version',
    [
        'type'    => Controls_Manager::HIDDEN,
        'default' => '1',
    ]
);

$widget->add_control(
    'items',
    [
        'label'       => esc_html__( 'Items', 'nexora-elementor' ),
        'type'        => Controls_Manager::REPEATER,
        'fields'      => $repeater->get_controls(),
        'default'     => nexora_example_default_items(),
        'title_field' => '{{{ item_title }}}',
    ]
);
```

Use a prefix on repeater subfields (`item_title`, not `title`) so they do not collide with widget-level controls. Both existing widgets do this (`step_*`, `tier_*`, `item_*`).

---

## 13. Output Schema

### CURRENT

Widgets output HTML, not a result envelope. There is no `{ success, data, error }` type.

What “success” means:

| Situation | GTM | Timeline |
| --- | --- | --- |
| Enabled, has rows | Full HTML | Full HTML |
| Enabled, empty repeater | Substitutes PHP defaults and still renders | Substitutes defaults; if those are also empty, prints nothing |
| Disabled, editor | Placeholder with link to `admin.php?page=nexora-for-elementor` for `manage_options` users | Same, via the base class |
| Disabled, front end | No output | No output |
| Bad config JSON | JS defaults | JS defaults |

Metadata that does exist:

| Metadata | Where |
| --- | --- |
| Elementor element id | `get_id()`, folded into `$uid` |
| Registry id | `get_name()` |
| Schema version | Hidden setting `_schema_version` (stored by Elementor, unread by PHP) |
| Enable flag | Option map, not on the widget HTML |
| Behavior flags | `data-config` |
| Row identity | GTM: Elementor `_id` when present. Timeline: 1-based index. |

Pagination, status codes, and error codes are **not** part of widget output.

### STANDARD

- Keep HTML as the output.
- Put the JS contract in `data-config` and document the keys in the widget’s render file next to `wp_json_encode`.
- Escape every interpolated value.
- Do not add a JSON response wrapper for a visual widget.
- Empty-state policy is per widget, but it must be deliberate:
  - **GTM policy:** never look empty; fall back to defaults.
  - **Timeline policy:** render nothing if there is no item.
  - **STANDARD for new widgets:** if a repeater can be cleared by the user, do not surprise them with hidden defaults unless the design requires a populated demo. Prefer rendering nothing, or a single editor-only note, over silently restoring marketing copy. GTM’s fallback is **WIDGET-SPECIFIC**.

### Recommended Future Pattern

**RECOMMENDED** `data-config` shape for a widget that has behavior:

```json
{
  "enabled": true
}
```

Add keys only for values the script branches on. Timeline’s two-key object is the smaller contract. GTM’s seven-key object is justified because interaction is configurable. Do not copy GTM keys onto a widget that does not rotate or dim.

---

## 14. Data / API Architecture

### CURRENT

```text
Widget UI (render.php HTML)
    ↓
Widget script reads data-config and data-* attributes
    ↓
No service
    ↓
No shared API client
    ↓
No external application API
```

The only remote URL in widget registration is the Google Fonts stylesheet. Admin settings also enqueue that stylesheet. Widget images and icons are plugin files or Elementor media library URLs chosen by the user.

`Nexora_Ele_Settings` is the data access layer for enablement:

| Method | Role |
| --- | --- |
| `get_raw_status()` | Option or `null` if never saved. Non-arrays become `null`. Values normalize to `'1'` or `'0'`. |
| `is_widget_enabled( $id )` | Saved value, else `default_enabled`, else `false` if the id is not in the registry. |
| `get_enabled_widgets()` | Registry subset. |
| `save_status_from_request()` | Writes every current registry id. `update_option( …, $status, false )` (not autoloaded). |

### Future Widget Data Access Pattern

**STANDARD.**

```text
Elementor controls
    ↓
get_settings_for_display()
    ↓
render.php normalization
    ↓
HTML
    ↓
optional widget JS
```

**STANDARD.** Do not add a service class that only forwards `$settings` into the template.

**RECOMMENDED** if a later widget must call an HTTP API:

```text
render.php or a widget-local PHP class
    ↓
wp_remote_get / wp_remote_post
    ↓
map the response into the HTML model
```

Keep the request in PHP. Do not put credentials in JavaScript. This path does not exist yet; do not build a shared client for it in advance.

---

## 15. State Management

### CURRENT

| Kind | GTM | Timeline | Admin |
| --- | --- | --- | --- |
| Local UI state | `activeKey`, `locked`, `rotateIndex`, `rotateTimer` inside `initGtmFunnel` | `activeStepId`, `swapTimer`, `positionRaf` inside `initTimeline` | Checkbox DOM state until submit |
| Server state | Elementor settings | Elementor settings | `nexora_ele_widget_status` |
| Loading | None | None | None |
| Error | Swallowed in JS | Swallowed in JS | Save rejects silently when capability or nonce fails (`handle_save` returns) |
| Empty | Defaults | No HTML | “No widgets registered in the catalog yet.” |
| Refresh | Re-init on Elementor `element_ready`; abort previous listeners | Same | Full form POST, redirect |
| Mutation | Class toggles only | Class toggles, `img.src`, inline transform | `update_option` |
| Cache | Elementor file cache cleared on settings save | Same | Same |
| Persistence of interaction | None. Reload restores `default_active` / first step. | None. Reload shows item 1 as active until scroll runs. | Option persists |

Global JavaScript state is limited to `hookRegistered` in each file so the Elementor hook is added once. Widget instances do not share an active index.

No Redux, Zustand, React context, or `wp.data` store exists. **STANDARD:** do not add one.

### Recommended State Pattern for Future Widgets

**STANDARD.**

| Layer | Holds | Must not hold |
| --- | --- | --- |
| Elementor settings | Content and style the author saved | Ephemeral hover/scroll index |
| `data-config` | Flags the script needs at init | Secrets, user capabilities |
| Closure inside `init*(root)` | Active index, timers, abort controller | Other instances’ state |
| `window` | Nothing new | Widget caches, globals other than the IIFE |
| WordPress options | Plugin settings such as enablement | Per-page widget content |

Prefix abort controllers so they do not collide: GTM uses `_nexoraGtmAbort`, timeline uses `_nexoraTlAbort`. **STANDARD:** `root._nexora{Short}Abort` with a widget-specific short name.

Clean timers and animation frames on abort. Timeline does. GTM stops the rotate interval on abort. Match that.

---

## 16. Error Handling

There is no error type hierarchy. This table is the behavior that exists, plus the rule for widgets that do not have that case.

| Case | Where detected today | Representation | How it reaches the UI | Display | Retry |
| --- | --- | --- | --- | --- | --- |
| Validation error | Render clamps and allowlists; JS `parseConfig` | Fallback value | Silent | Author sees Elementor’s control UI, not a Nexora error | Edit the control |
| API error | **Not implemented.** No HTTP client. | — | — | — | — |
| Authentication error | **Not implemented** for widgets | — | — | — | — |
| Authorization error | `current_user_can` in admin save and in the placeholder link | Save ignored; settings link omitted | No message on failed save | **CURRENT** gap: failed nonce/capability does not print an admin notice | Reload and save again as an administrator |
| Not found | Widget file not readable; class missing; render file missing | `continue` or `return` | Nothing on the page | Blank widget area | Fix the file path |
| Rate limit | **Not implemented** | — | — | — | — |
| Network error | **Not implemented** for widget data. A failed Google Fonts request is the browser’s problem; CSS falls back to the font stack. | — | — | System fonts | Browser retry |
| Unknown error | JS `try/catch` with empty catch; geometry `catch ( \Throwable )` | GTM geometry falls back to `preset_four()` | Funnel still draws the preset | No message | **WIDGET-SPECIFIC** to geometry |
| Empty data | Render | GTM substitutes defaults. Timeline returns. | See section 13 | See section 17 | Add repeater rows |
| Elementor missing | `did_action( 'elementor/loaded' )` | Admin notice | `admin_notices` | Warning, dismissible, only if `activate_plugins` | Activate Elementor |
| Widget disabled | `is_nexora_widget_enabled()` | Placeholder or empty | Editor vs front end | Dashed box in the editor | Re-enable in Nexora settings |

**STANDARD.**

- Do not introduce exceptions as the widget API.
- Do not log settings or icon file contents.
- Keep JS failures inside the widget IIFE.
- If you add a visible error, use a class on the widget root such as `nexora-ele-{slug}__notice`, with normal escaped text. There is no shared notice component on the front end.

---

## 17. Loading / Empty / Success States

**CURRENT.** Widgets are synchronous PHP. There is no loading skeleton and no spinner.

| State | GTM | Timeline | Required for a new widget |
| --- | --- | --- | --- |
| Loading | Not applicable | Not applicable | Only if the widget waits on something. None do. |
| Success | The designed section | The designed section | Yes |
| Empty | Unreachable while default functions exist | Blank output | Choose explicitly in `render.php` |
| Disabled | Base placeholder in the editor | Same | Yes, by extending the base and returning after `render_disabled_placeholder()` |
| Reduced motion | CSS disables transitions. JS skips auto-rotate. | CSS disables transitions. Scroll still changes the active step. | Honor `prefers-reduced-motion` in CSS if you animate |

**STANDARD.** Call `render_disabled_placeholder()` before any success markup. Do not invent a front-end loading state for Elementor-driven content.

---

## 18. Shared UI

### CURRENT inventory

Nexora does not have a shared front-end component library. The admin screen and the two widgets each own their markup.

| UI idea | Where it actually exists | Shared? |
| --- | --- | --- |
| Buttons | Admin: `.nexora-ele-admin__bulk-btn`, `.nexora-ele-admin__save`. Front end: `role="button"` divs/articles, not `<button>`. | No |
| Cards | GTM `.nexora-gtm__card`. Admin `.nexora-ele-admin__card`. Different DOM. | No |
| Inputs | Elementor panel (Elementor’s UI). Admin checkboxes. | No Nexora input component |
| Tables | None | — |
| Lists | GTM groups, timeline steps | Widget markup |
| Modals | None | — |
| Dropdowns | Elementor SELECT controls only | — |
| Tabs | Elementor Content/Style tabs only | — |
| Loading / skeletons | None | — |
| Error UI | Admin notice `.nexora-ele-admin__notice`. Editor placeholder inline styles in the base class. | Placeholder is shared. Front-end error UI is not. |
| Empty state | Admin `.nexora-ele-admin__empty`. Timeline renders nothing. | No |
| Icons | Elementor eicons in the panel. Plugin SVGs and Icons Manager on the front end. Admin inline SVGs. | No icon component |
| Typography | Plus Jakarta Sans plus widget CSS variables. Elementor typography group controls. | Font handle is shared |
| Layout | 12 columns at 1025px, spans 4–8, gap variables | Convention, not a layout component |
| Spacing | CSS variables and Elementor sliders/dimensions | Per widget |

### Nexora Shared UI Rules

**STANDARD.**

1. Scope every front-end selector under the widget root (`.nexora-ele-{slug}`). GTM’s compiled Tailwind utilities are prefixed `.nexora-ele-gtm-funnel .…`. Timeline writes `.nexora-ele-timeline .timeline-step`. Unscoped selectors will restyle the theme and Elementor.
2. Drive author-facing color, size, and spacing through CSS custom properties on the root, set by Elementor selectors (`{{WRAPPER}} .nexora-ele-{slug}`). Both widgets do this (`--ngtm-*`, `--nexora-tl-*`).
3. Use the large breakpoint at **1025px**, not 1024px. The GTM readme and the timeline CSS comment both say this avoids Elementor’s default tablet max-width of 1024px.
4. Use Plus Jakarta Sans by depending on handle `nexora-plus-jakarta-font`, with a system-font fallback in CSS. GTM’s stack includes Segoe UI, Roboto, Oxygen, Ubuntu, Cantarell. Timeline uses `system-ui, sans-serif`. Admin uses `"Segoe UI", sans-serif`.
5. Accent orange used in admin and GTM defaults is `#fa5a18`. Timeline’s progress default is `#f97316`. These are widget/admin defaults, not a token file. There is no `tokens.css`.
6. Do not build a React/Tailwind component kit inside this plugin for a new widget.
7. **STANDARD for new CSS:** write scoped CSS by hand, as `nexora-ele-timeline.css` does. The GTM file is compiled Tailwind v4.3.3 output checked in as a finished asset, and the Tailwind source is not in the repository. A new widget cannot run `npm run build` from this repo.
8. Keep the disabled placeholder in the base class. Do not restyle it per widget unless the task asks.
9. Text that is content uses `esc_html`. Do not render repeater text as HTML. Neither widget runs `wp_kses_post` on titles or descriptions.

---

## 19. Accessibility

### CURRENT

| Behavior | GTM | Timeline |
| --- | --- | --- |
| Keyboard | Optional (`enable_keyboard`, default on). Enter/Space activate. Arrows move. Escape clears. | Always. Enter/Space activate. No arrow navigation between steps. |
| Focus | `tabindex="0"` only when keyboard mode is on | `tabindex="0"` on every step. `:focus-visible` outline in CSS. GTM CSS does not define a focus ring for cards. |
| Roles | `role="button"` and `aria-pressed` when keyboard mode is on. Sections have `aria-label`. | `role="button"` and `aria-label` (the title) on each step. |
| Semantics | `article`, `h2`, `h3`, `section`, `main` | `h3` for titles. Steps are `div`s. Root is a `div`. |
| Screen readers | Decorative funnel nodes are inside SVG. Badge text is the step number. | Track, progress, overlay, and large number are `aria-hidden="true"`. |
| Disabled widget | Placeholder text, link only for `manage_options` | Same |
| Reduced motion | CSS plus JS auto-rotate guard | CSS only |
| Links | Cards are not links | Title can be a link. Activating the link does not also toggle the step. |

Admin: the save notice has `role="status"`. The switch graphic is `aria-hidden` because the real checkbox sits in the `<label>`. Bulk buttons are `<button type="button">`.

### STANDARD requirements for a new widget

- Interactive non-links are reachable by keyboard and have an accessible name.
- Prefer a real `<button>` when the control does not need to be an SVG `<g>` or a large card. The existing widgets use `role="button"` on non-buttons; that is **CURRENT**, not a reason to avoid `<button>` in new markup.
- Decorative graphics get `aria-hidden="true"`.
- Visible focus for anything with `tabindex`.
- `prefers-reduced-motion: reduce` disables non-essential transitions.
- Do not put `outline: none` without a `:focus-visible` replacement (timeline’s step rule sets `outline: none` and then restores it on `:focus-visible`).
- External links follow the timeline pattern: `target="_blank"` with `rel="noopener noreferrer"`, plus `nofollow` when that flag is set.
- Color contrast is the author’s problem once colors are controls. Default CSS should remain readable on the default background.

**WIDGET-SPECIFIC.** GTM allows the author to turn keyboard access off. Do not copy that switch unless the design has a non-interactive mode. Timeline does not offer it.

---

## 20. Responsive Design

### CURRENT breakpoints

| Width | GTM | Timeline |
| --- | --- | --- |
| Default | Single column, funnel under process (or first if `funnel_first`) | Steps plus mobile image. Left column `display: none`. |
| `min-width: 640px` | Larger card padding, type, gaps | Larger step gap, type, step padding |
| `min-width: 1025px` | 12-column grid, column spans, funnel order, funnel shown even if hidden on mobile | 12-column grid, desktop image column, mobile images forced off |

Both layouts can overflow if spans add up past 12. The code allows 8+8. **STANDARD:** document the “total 12” expectation in the control description, as GTM does, and still clamp each span to the allowlist. Do not assume the sum is valid.

### STANDARD

- Mobile layout is the base. Desktop starts at 1025px.
- Long titles wrap. Do not set `white-space: nowrap` on card titles.
- Images use `object-fit` (timeline defaults to `cover`) and a bounded frame (`aspect-ratio` on timeline).
- Hide a heavy column with a class, as `nexora-gtm--hide-funnel-mobile` does, rather than a separate template, unless the markup itself must differ.
- Timeline’s desktop image is `position: absolute` inside the left column and moved with `translateY`. That is **WIDGET-SPECIFIC**. Do not make every two-column widget absolutely positioned.
- Loading states are not part of the responsive system.

---

## 21. Authentication & Security

### CURRENT model

| Topic | Implementation |
| --- | --- |
| Authentication | WordPress login. Widgets do not authenticate users. |
| Authorization | Settings screen and save handler require `manage_options`. Placeholder settings link uses the same capability. Elementor editing is Elementor’s capability model. |
| Nonce | `check_admin_referer( 'nexora_ele_save_widgets', 'nexora_ele_settings_nonce' )` |
| Credentials / tokens / API keys | None in the repository |
| Environment secrets | None |
| User context inside widgets | Not read. Render does not branch on the current user except the placeholder link. |
| Server/client boundary | All settings are rendered to HTML the public page can see. `data-config` is public. |
| Option autoload | Disabled (`update_option` third argument `false`) |
| Output escaping | `esc_html`, `esc_attr`, `esc_url` on text and URLs |
| SVG | Allowlist before echo. Icons Manager output is echoed with a phpcs ignore. |
| Color CSS | Hex via `sanitize_hex_color`, else a regex that allows `rgb()`, `rgba()`, `hsl()`, `hsla()`, and named colors without `;{}<>` |
| File reads | Icon SVGs only from the attachment path or `assets/icons/` after `basename` + `sanitize_file_name` |

### Rules for future widgets

**STANDARD.**

- Escape on the way out.
- Allowlist enums before they become CSS classes.
- Keep using the settings nonce and capability. Do not add a second settings page that forgets either.
- Treat repeater text as text.
- If you accept SVG from the user, sanitize it. Do not echo raw `ICONS` SVG URLs into a `<use>` without the existing resolver’s checks.
- `foreignObject` in the GTM node is plugin-authored. The sanitizer removes `foreignObject` from user SVG first. Do not weaken that sanitizer.

### NEVER

**STANDARD**, and already true of this codebase:

- Do not put secrets in PHP that is printed, in JS, or in this guide.
- Do not add API keys to widget controls.
- Do not trust a client-side “is admin” flag. The settings form is checked with `current_user_can` on the server.
- Do not `error_log` widget settings, media URLs that may be private, or full `$_POST`.
- Do not grant `manage_options` to anything a widget does.

Widget HTML is public even when Elementor is used on a public page. Assume every control value is world-readable.

---

## 22. Data Transformation

### GTM (WIDGET-SPECIFIC path, CURRENT)

```text
Elementor settings array
    ↓
raw repeater arrays, fallback to default functions
    ↓
list of step arrays + list of tier arrays
    ↓
geometry array { tiers[].path, valueY, labelY, subY, nodeX, nodeY, transformOrigin, nodeRadius, viewBoxHeight, viewWidth }
    ↓
groups { heading, items[] } when group_heading is non-empty
    ↓
tier key string from linked_tier → _id
    ↓
HTML / SVG / data-config
    ↓
JS class names active|dimmed  (no second data model)
```

Formatting: step numbers and tier values are author strings (`15,000+`, `10-30+`). The plugin does not format numbers or dates. Null descriptions are omitted. Colors fall back to hex literals.

### Timeline (WIDGET-SPECIFIC path, CURRENT)

```text
timeline_items or defaults
    ↓
internal item model (section 6.3)
    ↓
per-item CSS variables when colors are set
    ↓
root CSS variables on the public front end
    ↓
HTML data-step-id, data-step-image, data-step-number
    ↓
JS reads those attributes; it does not remap the model
```

### Where transformations belong

**STANDARD.**

| Transformation | Place |
| --- | --- |
| Repeater row → render model | `render.php` |
| Default content | `defaults.php` |
| CSS size from a slider array | A widget helper if needed. Timeline: `nexora_timeline_slider_css()`. |
| Color for inline CSS | Color allowlist before concatenation |
| Pure geometry / heavy math | Widget-local class |
| DOM class toggles | JS |
| Date/number formatting | **UNKNOWN.** Not implemented. If you add it, do it in PHP with `wp_date` / explicit formatting, not in ad-hoc JS, so editor and front end match. |

Do not create a global “UI model” layer.

---

## 23. Performance

### CURRENT

| Technique | Present? |
| --- | --- |
| Asset registration only for enabled widgets | Yes |
| De-duplicated handles | Yes (`nexora-plus-jakarta-font` once) |
| Scripts in footer | Yes |
| Elementor dependency enqueue (CSS/JS only if the widget is on the page) | Yes, via `get_*_depends`, after handles are registered |
| Preview loads all enabled widget assets | Yes |
| HTTP cache headers / transients for widget data | No |
| JS memoization library | No |
| Request deduplication | Not applicable |
| Debounce | Timeline coalesces alignment with `requestAnimationFrame`. Scroll handling itself runs on every scroll event. |
| Pagination | No |
| Lazy loading | Timeline images use `decoding="async"` and do not set `loading="lazy"`. GTM icons are inline. |
| `will-change` | Timeline visual uses `will-change: transform` |
| Autoloaded options | Enable map is not autoloaded |

GTM CSS is a large scoped Tailwind build, including a preflight copied under `.nexora-ele-gtm-funnel`. That cost is **WIDGET-SPECIFIC**. New widgets should not import Tailwind into PHP by copying that file.

### STANDARD guidelines

- Register assets through the registry so disabled widgets do not register files.
- Ship plain scoped CSS.
- One IIFE per widget. Abort previous listeners before binding again.
- Do not query `document` globally for steps; query from the widget root. Both scripts do this.
- Do not add a cache layer for Elementor settings.
- Optimize further only when a specific widget is measurably expensive. Scroll listeners and SVG tiers are not a template for every widget.

---

## 24. Testing

### CURRENT

No tests, no `phpunit.xml`, no npm test script, no CI config in the plugin repository.

`widgets/gtm-funnel/README.md` is a manual usage note, not a test plan.

### STANDARD until a runner exists

Verify by hand in a WordPress site that has Elementor active:

1. **Registration.** After the plugin loads, the widget appears in Elementor’s panel search by its title and by the keyword `nexora`. The Nexora → Widgets screen lists the id, title, and description.
2. **Identity parity.** Panel name, registry key, and `get_name()` match. Disabling the widget in Nexora hides it from the panel.
3. **Disabled instance.** A page that already contains the widget still opens in Elementor, shows the placeholder, and prints nothing on the front end. Re-enabling restores it. Saving settings clears Elementor’s file cache (already implemented in `handle_save`).
4. **Defaults.** Dragging the widget on once shows the default content from `defaults.php`.
5. **Input clamps.** Out-of-range selects and empty repeaters follow the render rules you wrote.
6. **Success markup.** Front end matches the editor for content controls (`render_type` template where PHP branches).
7. **Empty.** The chosen empty policy is visible.
8. **Multiple instances.** Two copies on one page do not share SVG ids, abort controllers, or active state.
9. **Editor re-render.** Change a repeater row and confirm the script still works (Elementor `element_ready` path).
10. **Keyboard and reduced motion.** If the widget is interactive.
11. **1025px and a narrow viewport.**
12. **Missing Elementor.** With Elementor off, the admin warning appears and the site does not fatally error. This is plugin-level; re-check only if you edited `nexora-for-elementor.php`.

### RECOMMENDED automated coverage later

When a test harness is added on purpose, the first useful tests are pure PHP:

- Registry id list equals each class’s `get_name()` (would require loading Elementor’s `Widget_Base`, so this may be an integration test).
- `Nexora_Funnel_Geometry::is_default_geometry()` and `generate()` / `preset_four()`.
- `nexora_timeline_safe_color()` / `nexora_timeline_slider_css()` if they move to shared code.
- `Nexora_Ele_Settings` normalization: missing option, `'1'` / `'0'`, unknown id, new id after a saved map.

Do not require those tests for the next widget while the harness is absent.

### Example structure (RECOMMENDED, not in the repo)

```text
tests/
└── php/
    ├── SettingsNormalizationTest.php
    └── FunnelGeometryTest.php
```

There is no example test file to copy. Do not add PHPUnit as a side effect of building a widget.

---

## 25. Logging & Debugging

### CURRENT

- No `error_log` calls.
- No `WP_DEBUG` branches.
- JS catches errors and continues.
- PHP skips missing files silently.
- Admin save failure (bad nonce or capability) returns with no notice.
- Useful signal: Nexora → Widgets count, the `<code>` widget id on each card, and Elementor’s control panel.

### Useful checks

**CURRENT** tools that exist without extra packages:

- WordPress admin → Plugins, confirm Nexora for Elementor and Elementor are active.
- Nexora → Widgets (`/wp-admin/admin.php?page=nexora-for-elementor`).
- Browser devtools: look for `nexora-ele-{slug}.css` and `.js` in the network panel. If the handle was not registered, Elementor cannot enqueue it.
- Elements panel: root `data-config`, `data-nexora-*`.
- View source of the registry entry versus `get_style_depends()` handles. A typo means a 404 or a missing stylesheet.

There is no plugin CLI script.

### New Widget Debugging Guide

| Symptom | What to check |
| --- | --- |
| Does not register | Entry missing from `Nexora_Ele_Widget_Registry::all()`. `file` path wrong or not readable. `class` string does not match the class declaration. Elementor not active (`elementor_missing_notice`). PHP fatal inside the class file (check `wp-content/debug.log` only if the site already has `WP_DEBUG_LOG`; this plugin does not enable it). |
| Does not appear in the panel | `show_in_panel()` is false because `is_widget_enabled( get_name() )` is false. `get_name()` does not match the registry id. Look under the Nexora category, not General. |
| Does not render | `render()` returned early: disabled, or `render.php` path not readable. Timeline-style empty return. PHP notice inside the include. |
| Invalid input | Control `condition` hiding the field. Render allowlist rewriting the value (badge style, column span, interaction mode). Style-tab control with no `selectors` and no `render_type` `template`, so the editor did not re-render PHP. |
| Does not receive data | Settings key typo between `add_control` and `$settings['…']`. Repeater stored under a different name. Default function not loaded (`require_once` missing). |
| API errors | Not a current subsystem. If you added HTTP, inspect `wp_remote_*` return with `is_wp_error` in that widget only. |
| Wrong UI state | JS not enqueued (disabled widget, handle mismatch, script not in footer deps). `data-config` invalid. Previous abort controller not cleared. Selector looks for a class the markup does not have. |
| Works in the editor, fails on the front | Front end is where timeline applies inline CSS variables. Compare edit/preview detection in `render.php`. Confirm `get_script_depends()` is non-empty when the widget is enabled. Hard-refresh. Elementor CSS file cache: re-save Nexora settings (clears Elementor file cache) or regenerate Elementor CSS from Elementor’s tools. |
| Works locally, fails in production | Asset `ver` is the plugin version; a cache may hold an old file if the version did not change. Path case sensitivity: registry paths use forward slashes (`widgets/class-ele-….php`). Missing bundled image on deploy. Google Fonts blocked by the production network. |

---

## 26. Environment Configuration

### CURRENT

| Config | Location | When it applies |
| --- | --- | --- |
| Plugin version | Header and `Nexora_For_Elementor::VERSION` | Runtime, also the default asset version |
| WordPress / PHP / Elementor requirement | Plugin header | WordPress.org / site health. Runtime check exists only for “Elementor loaded”. |
| Enable map | Option `nexora_ele_widget_status` | Runtime, per site, not autoloaded |
| Widget copy and style | Elementor document | Runtime, per page |
| Font URL | Hard-coded in the registry and again in `Nexora_Ele_Admin_Settings::enqueue_assets()` | Runtime |
| Feature flags | None | — |
| `.env` | None | — |
| Build-time env | None in repo | — |

**CURRENT inconsistency:** `Nexora_Ele_Admin_Settings::enqueue_assets()` falls back to version string `'1.2.1'` if class `Nexora_For_Elementor` is missing. The plugin version is `1.1.1`. In normal loads the class exists, so the fallback does not run. Do not treat `1.2.1` as the plugin version.

### Where configuration belongs

**STANDARD.**

| Data | Layer |
| --- | --- |
| Widget on/off | Server runtime option, already implemented |
| Author content | Elementor, server-stored, rendered to the client |
| CSS/JS behavior flags | Server render into `data-config`, then client |
| Secrets | Do not add. If a future server integration needs a key, store it in a WordPress option or constant that is never printed. Not in JS, not in the registry `src`. |
| Build time | Not used. CSS and JS in `assets/` are the files WordPress serves. |

Client-side code must not be the source of truth for enablement. `get_style_depends()` already re-checks the server setting.

---

## 27. Development Commands

**CURRENT.** This repository does not define install, build, test, lint, typecheck, format, or production scripts.

| Task | Command in this repo |
| --- | --- |
| Install | **None.** No `package.json`, no `composer.json`. |
| Development | **None.** Run the plugin by activating it inside WordPress with Elementor active. |
| Build | **None.** `widgets/gtm-funnel/README.md` says `npm install` and `npm run build` from the plugin directory. Those commands are **not backed by files in the repository.** Do not invent a package.json as part of a widget task. |
| Test | **None.** |
| Lint | **None.** PHPCS is referenced only as inline `phpcs:ignore` comments. No ruleset file. |
| Typecheck | **None.** PHP does not use a committed `phpstan` config. JS has no TypeScript. |
| Format | **None.** |
| Production | **None.** The plugin is plain PHP/CSS/JS. Deploy the plugin directory. |

**STANDARD.** Edit `assets/css/nexora-ele-{slug}.css` and `assets/js/nexora-ele-{slug}.js` directly and let WordPress enqueue them. Bump `Nexora_For_Elementor::VERSION` and the plugin header together when a release should cache-bust assets (`'ver' => true` uses that constant).

**UNKNOWN.** The site-level process that starts this Local WordPress environment is outside the plugin repository.

---

## 28. Reference Widget

Both widgets are valid references. They share one skeleton and diverge in content. Use them as follows.

### What to copy

- Class shape in `widgets/class-ele-timeline.php` or `widgets/class-ele-gtm-funnel.php`: `get_name`, `get_title`, `get_icon`, `get_categories`, `get_keywords`, conditional `get_style_depends` / `get_script_depends`, `has_widget_inner_wrapper`, `register_controls`, `render` with the disabled check and `include`.
- File split: `defaults.php`, `controls-content.php`, `controls-style.php`, `render.php`.
- Registry entry shape, including `default_enabled`, style deps, footer script.
- Hidden `_schema_version`.
- `data-nexora-*` root and `data-config` via `wp_json_encode`.
- `$uid` derived from `get_id()`.
- JS shell: strict IIFE, `parseConfig`, `AbortController`, `DOMContentLoaded`, `elementor/frontend/init` → `frontend/element_ready/{get_name()}.default`.
- CSS variables set from Style controls.
- Breakpoint 1025px.
- Escaping and color allowlisting.
- `render_type => template` on controls that change PHP structure (timeline is the reference; GTM geometry is not).
- Editor-versus-front inline style split, if you also set the same properties in Elementor selectors (timeline `render.php`).

### What to adapt

- Control ids, repeater fields, and default copy.
- `data-config` keys.
- CSS variable prefix (`--nexora-{short}-*`).
- Column spans only if the layout is a 12-column pair.
- Bundled media helpers only if you ship images.
- Icon helpers only if you ship SVGs in `assets/icons/`.

### What NOT to copy

- GTM marketing numbers, step titles, tier gradients, and the 4-tier SVG preset.
- Timeline’s six photographs and content-marketing headlines.
- `Nexora_Funnel_Geometry` and the “fall back to the 4-tier drawing on any exception” behavior.
- GTM’s compiled Tailwind file as a starting CSS file.
- Legacy dual class names (`process-card` and `nexora-gtm__card`) unless you are editing GTM and must keep the JS selectors working.
- GTM’s silent substitution of default steps when the author clears the repeater, unless you explicitly want that.
- Timeline’s silent empty output, unless you explicitly want that.
- `enable_keyboard` as a way to remove semantics.
- Hard-coded admin icon `if ( 'ele-timeline' === $id )`.
- The Google Fonts URL copied into a third handle.
- `get_custom_help_url()` returning an empty string (GTM only). Omit it.
- Any `npm run build` instruction from `widgets/gtm-funnel/README.md` until a package file exists.

---

## 29. Building Widget #2

**CURRENT fact:** ELE Timeline is already the second widget. The steps below are the process that timeline followed and the process for the **next** widget. They use real paths.

1. **Decide purpose.** One Elementor widget, one registry id, one visual job. Do not combine unrelated sections to share a CSS file.
2. **Identify the data source.** For a widget like the current two, the source is Elementor settings only. Write down the repeater fields before coding. If the source is an HTTP API, stop and treat that as an architecture change (section 32); do not hide requests in JavaScript.
3. **Define the widget id.** `ele-{slug}`, hyphenated, matching `get_name()`. Example used only as a pattern: `ele-example`.
4. **Define input.** List Content controls and Style controls. Mark which Style controls are read in PHP. Those need `'render_type' => 'template'`.
5. **Define defaults.** `widgets/{slug}/defaults.php` returning repeater rows. No remote calls.
6. **Define output.** The HTML root, the BEM class prefix, and the `data-config` keys. Keep the key list short.
7. **Implement data access.** `$this->get_settings_for_display()` in the class `render()` method, then normalization at the top of `render.php`. Skip a service class.
8. **Implement logic.** Branching in `render.php`. Extract a class beside the widget only for math that is awkward inline.
9. **Register.** Add the array entry in `includes/class-widget-registry.php`. Point `file` at `widgets/class-ele-{slug}.php` and `class` at the PHP class. Set `styles` and `scripts`.
10. **Build the class.** Copy the method list from `Nexora_Timeline_Widget`. `require_once` the base and the three partials. Extend `Nexora_Ele_Widget_Base`.
11. **Build UI.** `render.php` prints the success state. Root class `nexora-ele-{slug}`, attribute `data-nexora-{slug}`.
12. **Loading.** Do not add a loader unless this widget actually fetches.
13. **Empty state.** After normalization, either `return` or print an explicit empty container. Do not copy GTM’s default-content fallback unless the product requirement is “always show the demo.”
14. **Disabled state.** First lines of `render()` match the existing widgets.
15. **Error state.** Allowlists and escaping. No new error framework.
16. **Interactions.** If needed, `assets/js/nexora-ele-{slug}.js` with the abort and `element_ready` hook. If not needed, omit the script and the registry `scripts` entry, and return `[]` from `get_script_depends()`.
17. **Style.** `assets/css/nexora-ele-{slug}.css`, scoped, 1025px desktop breakpoint, CSS variables for anything the Style tab controls.
18. **Accessibility.** Keyboard name, focus, reduced motion, escaped text.
19. **Admin icon.** Accept the default funnel SVG or extend `widget_icon_svg()`.
20. **Verify.** Section 24 manual list, on a page with Elementor. Check one narrow viewport and one viewport at or above 1025px. Toggle the widget off and on in Nexora → Widgets.
21. **Build command.** There is nothing to run. Reload the editor. Hard-refresh the front end. Bump `VERSION` when you need cache-busting.
22. **Do not** modify `widgets/class-ele-gtm-funnel.php`, `widgets/gtm-funnel/*`, `widgets/class-ele-timeline.php`, or `widgets/timeline/*` unless the task is about those widgets.

---

## 30. New Widget Template

### Planning sheet

```text
NEW WIDGET

Name:
ID:                      ele-
Purpose:

Registry title:
Registry description:
PHP class:
Admin icon:              default funnel SVG, or new branch in widget_icon_svg()

Input (Content):
- _schema_version = 1
- …

Input (Style):
- …

Controls that change PHP markup (must use render_type template):
- …

Output:
- Root class:
- data attribute:
- data-config keys:

Data source:
- Elementor settings only

Defaults file:
- widgets/{slug}/defaults.php

Service:
- none

UI states:
- Disabled: base placeholder
- Success: render.php
- Empty: return or explicit markup
- Error: allowlist / escape, no extra UI
- Loading: none

Interactions:
- none, or JS file path

Assets:
- css handle nexora-ele-
- js handle or none

Tests:
- manual checklist in section 24
```

### Code skeleton

**RECOMMENDED** skeleton matching **CURRENT** structure. Replace `example` / `Example`. This skeleton is not an existing widget.

`widgets/class-ele-example.php`

```php
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __DIR__ ) . '/includes/class-widget-base.php';
require_once __DIR__ . '/example/defaults.php';
require_once __DIR__ . '/example/controls-content.php';
require_once __DIR__ . '/example/controls-style.php';

if ( ! class_exists( 'Nexora_Example_Widget', false ) ) {

	class Nexora_Example_Widget extends Nexora_Ele_Widget_Base {

		public function get_name(): string {
			return 'ele-example';
		}

		public function get_title(): string {
			return esc_html__( 'ELE Example', 'nexora-elementor' );
		}

		public function get_icon(): string {
			return 'eicon-apps';
		}

		public function get_categories(): array {
			return [ 'nexora' ];
		}

		public function get_keywords(): array {
			return [ 'example', 'nexora' ];
		}

		public function get_style_depends(): array {
			if ( ! $this->is_nexora_widget_enabled() ) {
				return [];
			}
			return [ 'nexora-ele-example' ];
		}

		public function get_script_depends(): array {
			if ( ! $this->is_nexora_widget_enabled() ) {
				return [];
			}
			return [ 'nexora-ele-example' ];
		}

		public function has_widget_inner_wrapper(): bool {
			return false;
		}

		protected function register_controls(): void {
			if ( function_exists( 'nexora_example_register_content_controls' ) ) {
				nexora_example_register_content_controls( $this );
			}
			if ( function_exists( 'nexora_example_register_style_controls' ) ) {
				nexora_example_register_style_controls( $this );
			}
		}

		protected function render(): void {
			if ( ! $this->is_nexora_widget_enabled() ) {
				$this->render_disabled_placeholder();
				return;
			}

			$settings = $this->get_settings_for_display();
			if ( ! is_array( $settings ) ) {
				$settings = [];
			}

			$uid         = 'nexora-ex-' . sanitize_key( (string) $this->get_id() );
			$render_file = __DIR__ . '/example/render.php';

			if ( ! is_readable( $render_file ) ) {
				return;
			}

			include $render_file;
		}
	}
}
```

`widgets/example/render.php` expects `$settings` and `$uid` in scope, same as both current render files. Start the markup with:

```php
<div
	id="<?php echo esc_attr( $uid ); ?>"
	class="nexora-ele-example"
	data-nexora-example
	data-config="<?php echo esc_attr( $config_json ); ?>"
>
```

Omit `data-config` when there is no script.

JS init must listen for `frontend/element_ready/ele-example.default`.

Files that are **not** required:

| File | Skip when |
| --- | --- |
| `assets/js/…` | No front-end behavior |
| `widgets/lib/*.php` | No heavy calculation |
| A service class | Data is just `$settings` |
| A schema JSON file | Never, with the current architecture |
| `content_template()` | Both widgets omit it and use PHP render |
| README per widget | Optional. GTM has one. Timeline does not. Prefer updating this guide’s catalog table if the contract changes. |

---

## 31. Future Widget Checklist

```text
## New Widget Checklist

Architecture
[ ] Class lives in widgets/class-ele-{slug}.php
[ ] Partials live in widgets/{slug}/
[ ] Class extends Nexora_Ele_Widget_Base
[ ] get_name() equals the registry id (ele-{slug})
[ ] Registry entry added in includes/class-widget-registry.php
[ ] file, class, styles, and scripts in that entry match the class
[ ] default_enabled set
[ ] No edits to the other widgets
[ ] No new framework, autoloader, or API client

Input
[ ] Content controls and style controls split into the two partials
[ ] Hidden _schema_version default 1
[ ] Repeater defaults come from defaults.php
[ ] Dynamic enabled on author-facing text and URL controls
[ ] Enums allowlisted again in render.php
[ ] PHP-affecting controls use render_type template
[ ] Switchers use return_value yes and are compared to 'yes'

Data
[ ] Settings read from get_settings_for_display()
[ ] No secrets in settings, HTML, or JS
[ ] Colors sanitized before inline CSS
[ ] URLs escaped with esc_url
[ ] Text escaped with esc_html
[ ] Classes passed through sanitize_html_class or an allowlist

UI
[ ] Root class nexora-ele-{slug} and data-nexora-{slug}
[ ] Instance uid prefixes generated ids
[ ] CSS scoped under the root
[ ] Desktop breakpoint 1025px
[ ] CSS variables for style controls
[ ] Disabled placeholder via the base class
[ ] Empty behavior implemented on purpose
[ ] Success markup present
[ ] Reduced motion considered if anything animates
[ ] Keyboard access and a visible focus state if anything is interactive

Assets
[ ] CSS handle nexora-ele-{slug}
[ ] JS omitted entirely when unused
[ ] Shared font reuses handle nexora-plus-jakarta-font
[ ] get_style_depends and get_script_depends return [] when disabled

Quality
[ ] Widget appears in Elementor and on Nexora → Widgets
[ ] Disable / enable preserves an existing placement
[ ] Two instances on one page stay isolated
[ ] Narrow viewport and >= 1025px checked
[ ] Plugin header version and Nexora_For_Elementor::VERSION updated together if assets must cache-bust
[ ] No npm/composer commands assumed
```

Automated unit tests, integration tests, lint, and typecheck are **not** checklist items until those tools exist in the repository.

---

## 32. Architectural Gaps

Do not refactor these as part of adding a widget. Extract only when the condition in the last column is met.

| Area | Current State | Future Concern | Recommendation |
| --- | --- | --- | --- |
| Color allowlist | Duplicated in GTM `render.php` and `nexora_timeline_safe_color()` | A third copy will drift and can become an XSS hole in inline CSS | When the next widget needs inline colors, move one function to `includes/` and call it from all three. |
| Slider-to-CSS helper | Only `nexora_timeline_slider_css()` | GTM reads slider `size` inline in several places | Extract when a third caller needs it. |
| Packaged SVG icons | `nexora_gtm_default_icon()` and `nexora_gtm_resolve_icon_html()` | Timeline does not use them | Leave in GTM until another widget ships SVGs from `assets/icons/`. |
| Admin card icon | `widget_icon_svg()` switches on `ele-timeline` only | Every new widget looks like a funnel | Add an optional registry key the admin screen reads, the next time the admin screen is edited. |
| Class naming | `Nexora_GTM_Funnel_Widget` vs `Nexora_Timeline_Widget` | Inconsistent search | New classes use `Nexora_{Name}_Widget`. Do not rename existing classes; Elementor stores the widget by `get_name()`, but the PHP class name is still the registry `class` string. Renaming without a registry edit fatals. |
| CSS pipeline | GTM is compiled Tailwind. Timeline is hand-written. Source and `package.json` are absent. | Agents will follow the GTM readme and fail to build | Treat timeline CSS as the method for new widgets. Restore a build only as its own task. |
| `_schema_version` | Stored, never read | False sense that migrations exist | Write a migration only in the widget that changes its saved shape, and only then branch on the stored version. |
| Empty data | Opposite policies | Authors cannot predict a new widget | Each widget states its policy in `render.php` with a one-line comment. |
| `render_type` | Timeline sets it. GTM geometry sliders do not. | Geometry edits may not rebuild the SVG until a full re-render | New PHP-affecting controls set `render_type` `template`. Fixing GTM is a separate bugfix. |
| Custom Elementor category | Every widget returns `[ 'nexora' ]` | One panel group named Nexora | New widgets return `[ 'nexora' ]`. Do not put them in `general`. |
| Filesystem autoload | Manual `require_once` | Easy to forget a partial | Keep `require_once` in the widget class. A PSR-4 autoloader is a plugin-wide change, not a widget change. |
| Tests | None | Regressions in the registry and geometry | Add a harness as its own task. |
| Logging | Silent skips | Missing widgets are invisible | **RECOMMENDED:** a `WP_DEBUG` log line when a registry file or class is missing. Do not log settings. |
| Save errors | Capability/nonce failure returns quietly | Admins think save worked | **RECOMMENDED** admin notice. Not widget work. |
| Version fallback | Admin assets mention `1.2.1` | Confuses release numbering | Delete the fallback or set it to `Nexora_For_Elementor::VERSION` when editing that file. |
| HTTP APIs | Absent | A “data widget” might bypass PHP | Any remote data is server-side, widget-local, and specified before coding. No shared client until two widgets share an API. |
| i18n files | Strings are wrapped. No `.pot` | Translations cannot be shipped | Generate language files as a release task, not inside a widget. |
| `content_template` | Omitted | Editor depends on PHP round-trips | Keep omitting it so the editor and front end share one template. |
| Global JS utilities | `parseConfig` and `listen` copied into both scripts | Drift | Leave duplicated. Two copies do not justify a shared front-end bundle that both widgets would always download. |
| Row identity | GTM uses Elementor `_id`. Timeline uses index. | Linking between repeaters | If a new widget links rows, use Elementor `_id` (GTM) so reordering does not retarget by position. Document the choice. |

---

## 33. AI Coding Agent Rules

Instructions for an agent adding or changing a Nexora widget:

1. Read `NEXORA_WIDGET_DEVELOPMENT_GUIDE.md` before editing.
2. Read `includes/class-widget-registry.php`, `includes/class-widget-base.php`, and both `widgets/class-ele-*.php` files. The live code outranks this guide.
3. The plugin currently has two widgets, `ele-gtm-funnel` and `ele-timeline`. Inspect the one whose structure is closer to the task. Copy the skeleton, not the content.
4. Reuse the registry, the base class, settings, and asset registration. Do not create a parallel registration path.
5. Do not duplicate `is_nexora_widget_enabled()` or the admin screen.
6. Do not add a shared abstraction for a single call site. Section 32 lists the only planned extractions, and they wait until a third consumer exists.
7. Keep new business logic, controls, defaults, CSS, and JS inside that widget’s files.
8. Name the id `ele-{slug}`. Match `get_name()`, the registry key, the asset handles, and the root CSS class.
9. Follow the existing state pattern: Elementor settings plus a per-root JS closure. Do not add a state library.
10. Follow the existing error pattern: allowlist, escape, return early, swallow JS errors inside the widget. Do not add a result object.
11. Follow the UI rules in section 18: scoped CSS, CSS variables, 1025px breakpoint, hand-written CSS.
12. There is no test script. Run the manual checklist in section 24 and say what you could not open.
13. Do not add secrets, API keys, or environment files.
14. Do not modify the other widget’s PHP, CSS, or JS.
15. Do not rename existing widget ids. Elementor stores `get_name()` in page data.
16. Do not redesign the plugin, add Composer, add npm, or add an Elementor category unless the task explicitly says so.
17. If a change is architectural (new shared include, new option, new remote API, new category), describe it and wait. Do not implement it inside a widget task.
18. If the GTM readme mentions `npm run build`, ignore it until `package.json` exists.
19. Do not print this guide’s private reasoning or WordPress debug output that contains settings.
20. When you finish, name the registry id, the files added, and the empty-state policy you chose.

---

## 34. Architecture Decision Log

Reasons are included only when the code or an in-repo comment states them. Otherwise the reason is marked unknown.

| Decision | Current Approach | Reason | Future Rule |
| --- | --- | --- | --- |
| Widget catalog | One array in `Nexora_Ele_Widget_Registry::all()` | File comment: “Add new widgets here only — bootstrap + settings read from this registry.” | Add widgets only there (or via the documented filter). |
| Soft disable | Widgets stay registered; `show_in_panel()` hides them; render and asset deps no-op | Comments in `class-widget-base.php` and `register_widgets()`: Elementor must keep saved instances | Keep this. Do not unregister disabled widgets. |
| Enablement storage | Option `nexora_ele_widget_status` map of `'1'`/`'0'`, not autoloaded | `class-settings.php` comments. Missing keys use `default_enabled` so new widgets stay on after updates. | One map. Do not add per-widget options. |
| New widget default | `default_enabled` true for both | Same comment as above | Set `default_enabled` on every entry. |
| Asset registration | From the registry, enabled widgets only, de-duplicated handles | `register_assets()` implementation | Declare assets in the registry. |
| Preview assets | All enabled widgets enqueued in the Elementor preview | `enqueue_preview_styles()` / `enqueue_preview_scripts()` | Leave it. |
| Base class load timing | `class-widget-base.php` is required inside `register_widgets()`, not at plugin boot | Comment: “Elementor classes are available on this hook.” The file returns immediately if `Widget_Base` is missing. | Do not `require` the base before Elementor has loaded, except from a widget class that itself only loads on that hook. |
| External extension | Filter `nexora_ele_widget_registry` | The filter is applied in `all()` | In-repo widgets are hard-coded entries. Use the filter for outside code. |
| Categories | `nexora`, titled Nexora | `register_category()` on `elementor/elements/categories_registered` | New widgets return `[ 'nexora' ]`. |
| Inner wrapper | `has_widget_inner_wrapper(): false` | Method comment: “Elementor compatibility.” | Return false on new widgets. |
| Editor template | PHP `render()` only | `content_template` is absent | Do not add a second JS template. |
| PHP/JS bridge | `data-config` JSON | Both render files and both scripts | Use it for behavior flags. |
| Multi-instance JS | AbortController on the root; Elementor `element_ready` | GTM readme: “JS uses AbortController per root so editor re-renders and multiple instances stay isolated.” Timeline implements the same pattern. | Required for any widget script. |
| SVG id collisions | Ids include `$uid` | GTM readme: “SVG gradient / filter IDs are namespaced per Elementor widget ID.” | Namespace generated ids. |
| Desktop breakpoint | 1025px | GTM readme and timeline CSS comment: avoid Elementor’s tablet max-width of 1024px | Use 1025px for the desktop layout. |
| GTM groups | Flat repeater plus `group_heading` | Control description: “Leave empty to continue the previous group.” GTM readme: Elementor has no nested repeaters. | Use a flat repeater for similar groups. |
| GTM geometry | Preset paths when 4 tiers and default sliders; otherwise parametric; catch-all returns the preset | `Nexora_Funnel_Geometry` class comment | Do not reuse for other widgets. |
| Timeline inline CSS | Skipped in Elementor edit and preview | Comment in `timeline/render.php`: inline vars block live Style updates | If you set inline vars and Elementor selectors for the same property, skip the inline vars in the editor. |
| Timeline visual position | JS `translateY` beside the active step | CSS comment: “Tracks the active step vertically (not viewport-sticky).” | Do not describe it as sticky. |
| Font | Plus Jakarta Sans from Google Fonts, shared handle | Registry entries and admin enqueue | Reuse the handle. |
| Admin capability | `manage_options` | `Nexora_Ele_Settings::capability()` | Do not lower it. |
| Cache flush | Elementor file manager `clear_cache()` after settings save | Comment in `handle_save()` | Keep it if you change the save handler. |
| No autoloader | `require_once` | The bootstrap and widget classes do this. | Reason not established from current implementation beyond the code as written. Do not add an autoloader in a widget task. |
| No test suite | No test files | Reason not established from current implementation. | Do not pretend tests passed. |
| No remote API | No `wp_remote_*` | Reason not established from current implementation. | Do not add one without an explicit requirement. |
| Tailwind on GTM only | Compiled CSS header `tailwindcss v4.3.3` | Reason not established from current implementation. The build inputs are not in the repo. | New widgets use hand-written scoped CSS. |
| Text domain `nexora-elementor` | Plugin header | WordPress plugin convention present in the header | Use that domain, not `nexora-for-elementor`. |
| License | GPL v2 or later | Plugin header | New files stay compatible with that license. |

---

## 35. Known Gotchas

1. **Two widgets, not one.** Agents that only copy GTM will miss timeline’s `render_type` and hand-written CSS, which are the better patterns for new work.
2. **`get_name()` must equal the registry id.** Enablement, panel visibility, and the Elementor hook name all use it. The hook is `frontend/element_ready/{get_name()}.default`.
3. **Disabled widgets still register.** Unregistering them drops Elementor data. The base class exists to avoid that.
4. **Assets of disabled widgets are not registered.** `get_style_depends()` also returns `[]`. Both sides are required.
5. **A new registry id is enabled by default** when it is absent from a previously saved option, but only if `default_enabled` is truthy. If someone saves the settings form, every current id is written, including ones that were unchecked.
6. **Admin icons.** Unknown ids use the funnel SVG.
7. **GTM geometry controls** have no `selectors` and no `render_type`. They affect PHP. Timeline marks similar controls as `template`. Copy timeline.
8. **GTM empty repeaters resurrect the marketing defaults.** Clearing all steps does not produce an empty section.
9. **Timeline empty items produce an empty string**, including no wrapper. That can look like a broken shortcode.
10. **Timeline inline styles are front-end only.** Debugging “styles differ in the editor” starts from the `is_edit_mode` / `is_preview_mode` block in `timeline/render.php`.
11. **Column spans are not validated as a pair.** 8 and 8 are both allowed.
12. **`linked_tier` is 1-based** and falls back to the step index. If the tier row has no `_id`, the key is the zero-based index string. Cards and tiers must share that key or hover will not match.
13. **Legacy `step_icon_svg` / `node_icon_svg`** are still read. They are not controls. Do not add new raw SVG textareas.
14. **User SVG is sanitized, then GTM places icons inside `foreignObject`.** Do not remove the sanitizer because the widget itself uses `foreignObject`.
15. **JS errors are invisible** by design (`catch` empty). A blank funnel interaction is often a selector mismatch, not a thrown error visible in the page.
16. **jQuery is a script dependency** even though the widget logic does not use `$` except to wait for `elementor/frontend/init`. Keep the dependency if you keep that hook pattern. Elementor provides jQuery.
17. **Google Fonts are render-blocking external CSS** registered as a style. Offline environments will not get Plus Jakarta Sans. The font stacks include fallbacks.
18. **`widgets/gtm-funnel/README.md` build steps are stale** relative to the repository (no `package.json`, no `assets/src/`).
19. **Version strings disagree.** Plugin `1.1.1`. Admin fallback literal `1.2.1` (unused on the normal path).
20. **`sanitize_key` on widget ids** strips characters other than lowercase letters, digits, `_`, and `-`. Choose ids that survive it.
21. **Filter `nexora_ele_widget_registry` runs once per request** and is cached in `Nexora_Ele_Widget_Registry::$widgets`. Late filter registration misses the catalog.
22. **Widget base is not loaded on `plugins_loaded`.** Code that references `Nexora_Ele_Widget_Base` before `elementor/widgets/register` will not find the class.
23. **Progress math** on the timeline uses `* 90`, so the bar never reaches 100%. That is the current formula, not a bug to “fix” while copying the widget.
24. **First timeline step is active in HTML**, and `initTimeline` does not call `setActiveStep` on init. Until the first scroll, progress height stays `0%` from the inline style.
25. **No `loading="lazy"`** on timeline images. The desktop image `src` changes in JS; a lazy attribute could interfere. Do not add it without checking the swap.
26. **PHPCS is not configured.** Ignore comments are local exceptions, not a license to skip escaping elsewhere.

---

## 36. Future Recommendations

These are **RECOMMENDED**. They are not part of the widget contract until someone schedules them.

1. Add an optional `admin_icon` field on registry entries and teach `widget_icon_svg()` to use it, so new widgets do not require a PHP switch.
2. Extract the color allowlist into `includes/` on the next widget that inlines colors.
3. Register an Elementor category named Nexora once the panel list is hard to scan. Update every `get_categories()` in the same change so old and new widgets sit together.
4. Add a `WP_DEBUG`-only log when registry `file` or `class` fails to load.
5. Show an admin error when settings save fails the nonce or capability check.
6. Align the admin asset version fallback with `Nexora_For_Elementor::VERSION`.
7. Either restore the Tailwind source and `package.json` for GTM, or delete the npm instructions from `widgets/gtm-funnel/README.md` so agents stop following them. Do not do this as a drive-by during an unrelated widget.
8. Add PHPUnit only for pure functions and settings normalization, with a config file committed in the plugin.
9. Generate a `nexora-elementor.pot` at release time.
10. If two widgets ever share a remote API, introduce one PHP client then. Not before.
11. Set `render_type` to `template` on GTM geometry sliders if authors report that geometry edits do not refresh the SVG. Verify in the editor before changing it; this guide only confirms the controls lack `render_type` and `selectors`.

---

## 37. Prospects Hive content controls

This is the content-customization standard for the 24 Prospects Hive widgets (`ele-ph-*`). GTM Funnel and Timeline already have their own content controls. Do not rebuild them to match this section.

The visual design stays where it is. Content controls only change the strings, URLs, and image URLs that the widget prints. They do not change HTML structure, CSS, JavaScript, classes, or asset loading.

### Rules

- Every user-visible text node, link `href`, image `src` (including an SVG `<image href>` that points at a design file), `alt`, `placeholder`, `aria-label`, and `title` gets a control.
- The control default is the original design copy. Do not rewrite, shorten, or re-punctuate it.
- If the saved value equals that default, the renderer leaves the original markup bytes in place. `esc_html()` is not applied on the identity path, so apostrophes and `&amp;` stay as authored.
- If the user clears a value, it stays empty. Do not write `!empty( $settings['heading'] ) ? $settings['heading'] : 'Default'`.
- A missing settings key (widget not saved yet, or an older element) uses the default. `array_key_exists` distinguishes “cleared” from “not saved”.
- Plain text uses `Controls_Manager::TEXT`. Copy longer than a short label uses `TEXTAREA`. Links use `URL`. Images use `MEDIA`.
- Do not add color, typography, spacing, border, shadow, animation, or layout controls in this pass.
- Do not edit widget CSS or widget JavaScript to make a control work. Elementor re-renders the widget PHP, and dynamic widgets remount from the updated `<template>`.
- Each widget instance reads `$this->get_settings_for_display()`. There is no shared content variable between instances.
- Admin CSS and admin JS are unchanged. This work does not enqueue them.

### Where the controls live

| File | Role |
| --- | --- |
| `widgets/ph-{slug}/markup.html` | Original markup. Still the byte source. Not tokenized. |
| `widgets/ph-{slug}/content-map.json` | Byte offsets, defaults, and repeater shells for that widget. |
| `widgets/ph-{slug}/controls-content.php` | Hidden `_schema_version`, then `nexora_ph_register_mapped_controls()`. |
| `includes/ph-content.php` | Registers the mapped controls and applies settings. |
| `includes/ph-markup.php` | Calls `nexora_ph_apply_content()` before the `assets/` rewrite and id suffix. |
| `PROSPECTS_HIVE_CONTENT_INVENTORY.md` | Element-by-element inventory. |

`render.php` passes `$this->get_settings_for_display()` into `nexora_ph_render_section()`.

### Control ids

- Singleton: `{slug}_{fN}`, with hyphens in the slug turned into underscores. Example: `hero_f3`.
- Repeater: `{slug}_r{N}`.
- Repeater fields: `text`, `text_2`, `url`, `image`, `alt`, `placeholder`, `aria`, `tooltip`. A heading tag is `{slug}_tagN` on a singleton, or `html_tag` inside a repeater. Process step fields use `number`, `label`, `title`, `html_tag`, `description`, `image`, and `alt`.
- Text domain: `nexora-elementor`.

The hidden Schema section stays. Content sections are added after it.

### Repeaters

Repeated siblings that share a tag, class list, and field order become one Elementor repeater. Each default item keeps its own HTML shell, so inline SVG and inline styles stay with that item. Extra items clone the last shell. Removing every item is allowed (`prevent_empty` is false). An empty saved repeater prints nothing. A missing repeater key prints the original items.

Elementor has no nested repeaters. The deepest repeated group is the repeater. Text on a parent that contains that group stays as its own control. Process rows that alternate image-left and image-right share one repeater. Each default item keeps the HTML shell of its position, so the layout stays with the slot and the copy moves with the item.

A later item may omit a trailing field. The logo marquee does this: the first chips have alt text and the aria-hidden copies do not. Those copies stay in the repeater so the marquee count does not change.

### URLs and images

`URL` controls replace the `href` value only. `target` and `rel` stay in the markup, including `target="_blank"` on the original buttons.

`MEDIA` controls replace the image URL only. The default URL points at `assets/images/prospects/{file}`. While the chosen URL is still that default, the markup keeps `assets/{file}` so the existing `assets/` rewrite can run. A different URL is escaped with `esc_url()` and written into the existing `<img>` or SVG `<image>`. Do not switch those tags to `wp_get_attachment_image()`.

Decorative inline SVG icons are not icon controls. The design does not use an icon font or Elementor’s icon library. Symbol-only glyphs (`→`, `✓`, `★`, `▶`) stay in the markup.

### Script-owned copy

Some visible strings are not in the HTML. They are printed by the existing widget script through `{{ }}` holes. The Cases, Impact, and Framework scripts were not modified, so that copy is not a control:

| Widget | Data | Source |
| --- | --- | --- |
| Cases | Slide title, summary, client, industry, services, stats, image, alt | `CASES` in `nexora-ph-cases.js` |
| Impact | Tab label, title, body, points | `IMPACT` in `nexora-ph-impact.js` |
| Framework | Tab label and subtitle | `FW` in `nexora-ph-framework.js` |

Pricing is the exception. Plan names, prices, lists, and the custom card come from the Plans repeater. `nexora-ph-pricing.js` reads that saved list and only switches the active tab.

The static copy around the remaining holes (headings, framework pane body, industry names) is editable. Template class names and click handlers (`{{ ind.c0 }}`, `onClick="{{ }}"`) are behavior, not copy.

### Empty values and escaping

| Situation | Output |
| --- | --- |
| Value equals the default | Original bytes |
| User cleared the value | Empty string, escaped for its context |
| User edited text | `esc_html()` or `esc_attr()` |
| User edited a URL or image | `esc_url()` |

### What a future widget should do

Put content controls on that widget’s `controls-content.php`. Use the original design string as `default`. Print the setting into the existing tags and classes. Use a repeater for a real repeated group, with one default item per design item. Do not load another widget’s CSS or JS. Do not load admin assets on the public site.

---

## 38. Elementor control organization

This is the sidebar standard for every Nexora widget. It covers how controls are grouped, when a repeater is required, and how a heading’s HTML tag is chosen. It does not change the frontend. CSS, JavaScript, classes, animations, and the default markup stay as they are.

### Control organization

- Group controls in the order a reader meets the content. A typical widget is Header, then the repeated items, then the call to action.
- Give a group its own Elementor section when it has a heading of its own or it is a repeater. Do not open a section for a single stray label.
- Labels are the words an editor uses: Heading, Description, Button text, Button link, Image, Image alt, HTML Tag. The control id can stay technical. The sidebar label cannot.
- One control per content property. If a repeater already edits an item’s title, do not add another control outside the repeater for that same title.
- Changing a label does not change the control id. Saved pages keep matching.
- Do not fold an eyebrow, a heading, and a description into one control just to shorten the sidebar. They are three properties.
- Do not add color, typography, spacing, border, shadow, animation, or responsive controls as part of this organization. Controls that already exist for layout, such as the GTM Funnel layout section, stay where they are.

### Repeaters

Use a repeater when the design repeats one content shape: cards, list rows, steps, logos, navigation links, questions, statistics, form fields.

- The default items are the design items. Same count, same order, same text, images, and URLs. Do not invent a row and do not drop one.
- Fields that belong to one item (title, description, image, link, HTML tag) live inside the repeater.
- Fields that belong to the whole collection (section eyebrow, section heading, one shared button) stay outside it.
- Set the repeater `title_field` to the item’s main text, so the row reads “Fast Performance” instead of “Item #1”.
- Items that do not share the same fields stay as separate controls. A featured card with a checklist is not the same shape as a plain card. Elementor cannot put a repeater inside a repeater, so the deepest repeated group wins.
- On Prospects Hive process steps, each default item keeps the HTML shell from that position. An image-left step and an image-right step stay visually distinct. Reordering moves the copy into the shell of the new position.

### Headings

Every heading an editor can change has two controls: the heading text, and HTML Tag.

- The select offers H1, H2, H3, H4, H5, H6, div, span, and p.
- The default is the tag already in the HTML. An `h1` defaults to H1. An `h3` defaults to H3. Do not default every heading to H2.
- The renderer replaces the tag name only. The existing class stays on the element. Do not write CSS for each tag, and do not change type size because the tag changed.
- A heading that repeats inside items has its HTML Tag field on the repeater item, not as one tag for the whole collection.
- `nexora_ele_heading_tag()` in `includes/class-widget-base.php` (and again in `includes/ph-content.php` when Elementor is not loaded) allows only those nine tags. An empty or unknown value falls back to the original tag, so a heading is never printed without a tag.
- GTM Funnel: the section heading defaults to H2, the group heading defaults to H2, and the card title defaults to H3. The classes `nexora-gtm__section-title`, `nexora-gtm__group-heading`, and `nexora-gtm__card-title` stay.
- Timeline: the step title defaults to H3. The class stays `step-title`. The timeline script selects that class, not the tag name.

### Content architecture

- Every visible content property that the widget script does not own has exactly one control.
- A repeated structure uses a repeater. Do not number controls as Item 1 Title, Item 2 Title, Item 3 Title.
- Prospects Hive stores the map in `widgets/ph-{slug}/content-map.json`. `includes/ph-content.php` turns each `section` into an Elementor section and each repeater into its own section. A heading tag control replaces only the bytes of the tag name, so the attributes and the class are untouched. When the saved value still equals the default, those bytes are left alone.
- The audit of every control is `PROSPECTS_HIVE_CONTENT_INVENTORY.md`.

---

## Document history

| Item | Value |
| --- | --- |
| Derived from | Plugin source at version 1.1.1 |
| Primary files read | `nexora-for-elementor.php`, `includes/*`, `widgets/class-ele-*.php`, `widgets/gtm-funnel/*`, `widgets/timeline/*`, `widgets/lib/class-funnel-geometry.php`, `assets/js/*`, `assets/css/*` (timeline and admin in full, GTM CSS by structure) |
| Not modified | Plugin behavior at the time section 36 was written. Section 37 documents Prospects Hive content controls. Section 38 documents sidebar organization, repeaters, and heading tags. |
