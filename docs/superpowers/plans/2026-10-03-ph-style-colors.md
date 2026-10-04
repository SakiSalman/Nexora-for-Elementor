# PH Style Colors Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add Style → Colors to every `ele-ph-*` widget with one COLOR control per distinct standalone solid and one `Group_Control_Background` (gradient) per distinct gradient usage, with verify-gated full coverage and unchanged defaults.

**Architecture:** A CLI extract/verify script builds expected solids/gradients per slug from CSS + widget PHP/JS + shared rules used by markup. Per-slug inventory PHP arrays drive `nexora_ph_register_style_colors()`. Solids set `--ph-{slug}-{token}` (and shared semantic `--ph-{token}` where applicable) on `{{WRAPPER}} .nexora-ph`; gradients use Elementor background group controls on real selectors (GTM Funnel pattern). CSS uses `var(..., #fallback)` so unset settings match today’s look.

**Tech Stack:** Elementor `Controls_Manager::COLOR`, `Group_Control_Background`, PHP inventories, PH CSS (`nexora-ph-shared.css` + `nexora-ph-{slug}.css`), Node or PHP CLI verify script.

## Global Constraints

- Spec: `docs/superpowers/specs/2026-10-03-ph-style-colors-design.md`
- Every includable standalone solid + every distinct 2+ stop gradient per widget must be editable
- Gradients use gradient pickers (`Group_Control_Background`), not only stop COLOR fields
- Defaults / CSS fallbacks must preserve current appearance
- GTM Funnel / Timeline Style tabs unchanged
- Verify script must report zero `missing_*` and zero `unwired_*` for every PH slug before done
- Exclude pure white/black opacity glass/sheen overlays; brand-tinted rgba map to solid RGB
- Bump plugin version when shipping (currently `1.2.7` → `1.2.8`)
- Do not commit unless the user explicitly asks

---

## File structure

| Path | Role |
| --- | --- |
| `bin/ph-style-colors-verify.php` | Extract expected solids/gradients; compare to inventories + scan for unwired hardcodes; exit non-zero on gaps |
| `includes/ph-style-colors.php` | `nexora_ph_register_style_colors( $widget, string $slug ): void` |
| `includes/ph-style-colors/{slug}.php` | Returns `['solids' => [...], 'gradients' => [...]]` for one widget |
| `includes/ph-style-colors/_shared-tokens.php` | Shared solids/gradients reused by multiple widgets (semantic tokens + selectors) |
| `widgets/class-ele-ph-*.php` | Call registrar from `register_controls()` |
| `assets/css/nexora-ph-shared.css` | Solids → `var(--ph-…, #hex)`; remove competing gradient backgrounds owned by group controls |
| `assets/css/nexora-ph-{slug}.css` | Same for widget-only colors/gradients |
| `widgets/ph-{slug}/**/*.php` | Inline hex/gradients → classes or `var()` / remove when group control owns background |
| `nexora-for-elementor.php` | `require_once` style-colors helper; version bump |

### Inventory entry shapes

```php
// Solid
[
  'token'   => 'ink',           // CSS var suffix → --ph-{slug}-ink OR shared --ph-ink
  'label'   => 'Text',
  'default' => '#0B1620',
  'group'   => 'text',          // text|accent|surface|button|tag|fill|widget
  'shared'  => false,           // true → set --ph-{token} (no slug) for shared.css
]

// Gradient
[
  'name'     => 'btn_primary',  // group control name prefix: ph_{slug}_btn_primary
  'label'    => 'Primary button',
  'group'    => 'button',
  'selector' => '{{WRAPPER}} .nexora-ph a.btn-primary, {{WRAPPER}} .nexora-ph .btn-primary',
  'default'  => [
    'background'     => 'gradient',
    'color'          => '#FF8350',
    'color_b'        => '#DC5016',
    'gradient_type'  => 'linear',
    'gradient_angle' => [ 'unit' => 'deg', 'size' => 180 ],
    // Extra stops beyond Elementor A/B: keep mid-stop via CSS var bridge if needed
    // (see Task 2 note on 3-stop buttons)
  ],
]
```

**3-stop gradients (e.g. `#FF8350 → #F15E22 → #DC5016`):** Elementor’s background group exposes two color stops. Bridge the mid stop with a solid COLOR control (`Accent` / `Primary mid`) used inside a CSS `linear-gradient(180deg, var(--ph-btn-soft,#FF8350) 0%, var(--ph-accent,#F15E22) 55%, var(--ph-btn-deep,#DC5016) 100%)` **or** apply the group control for the dominant fill and accept A/B mapping to first/last with mid as a linked solid. Prefer CSS-var bridge for exact 3-stop fidelity so defaults stay pixel-true; still register a gradient group control that writes the same custom properties when practical. Document the chosen bridge in the inventory `notes` key.

---

### Task 1: Extract + verify CLI

**Files:**
- Create: `bin/ph-style-colors-verify.php`
- Create: `includes/ph-style-colors/.gitkeep` (replaced by inventories in later tasks)

**Interfaces:**
- Produces: CLI that can run with `--extract` (print JSON expected sets) or default verify mode against `includes/ph-style-colors/{slug}.php`
- Produces: exit code `0` only when all slugs have empty missing/unwired lists

- [ ] **Step 1: Add verify script**

Create `bin/ph-style-colors-verify.php` that:

1. Defines slug list matching all `ele-ph-*` widgets:  
   `approach, cases, challenge, contact, cta, expertise, faq, footer, framework, growth, hero, impact, industries, insights, logos, nav, partners, pricing, process, solutions, stories, tech, video, why`
2. For each slug, reads:
   - `assets/css/nexora-ph-{slug}.css` (if exists)
   - all `widgets/ph-{slug}/**/*.{php,js}`
   - `assets/css/nexora-ph-shared.css`
   - markup class tokens from `widgets/ph-{slug}/render.php` (+ html/data/media as present) to filter which shared rules apply
3. Extracts solids + gradients per spec normalize/exclude rules
4. Loads inventory via `includes/ph-style-colors/{slug}.php` if readable (function `nexora_ph_style_colors_inventory_{slug}` returning the array) or empty
5. Prints per slug: counts + `missing_solids`, `missing_gradients`, `unwired_solids`, `unwired_gradients`, `orphan`
6. Exit `1` if any missing/unwired

Minimal solid normalize helper inside the script:

```php
function ph_sc_norm_hex( string $raw ): string {
  $h = strtoupper( ltrim( trim( $raw ), '#' ) );
  if ( strlen( $h ) === 3 ) {
    $h = $h[0] . $h[0] . $h[1] . $h[1] . $h[2] . $h[2];
  }
  if ( strlen( $h ) === 8 ) {
    $h = substr( $h, 0, 6 );
  }
  return '#' . $h;
}
```

Gradient extract: regex for `linear-gradient\(` / `radial-gradient\(` and balanced parentheses capture; skip gradients whose only stops are excluded white/black opacity overlays.

Unwired solid heuristic after migration: in widget CSS + inline sources, any includable `#HEX` that is not inside a `var(--ph-…, #HEX)` fallback and not listed only as a gradient stop covered by inventory gradients.

- [ ] **Step 2: Smoke-run extract (expect gaps)**

Run:

```bash
php bin/ph-style-colors-verify.php
```

Expected: non-zero exit; prints missing inventories for all slugs (inventories not created yet). Confirms script runs.

- [ ] **Step 3: Commit only if user asks**

```bash
git add bin/ph-style-colors-verify.php
git commit -m "chore(ph): add style colors coverage verify script"
```

---

### Task 2: Registrar + shared token catalog

**Files:**
- Create: `includes/ph-style-colors.php`
- Create: `includes/ph-style-colors/_shared-tokens.php`
- Modify: `nexora-for-elementor.php` (require helper near other includes, ~line 24–27)

**Interfaces:**
- Consumes: inventory files `nexora_ph_style_colors_inventory_{slug}(): array`
- Produces: `nexora_ph_register_style_colors( \Elementor\Widget_Base $widget, string $slug ): void`

- [ ] **Step 1: Shared tokens file**

Create `includes/ph-style-colors/_shared-tokens.php` exporting `nexora_ph_style_colors_shared_catalog(): array` with solids/gradients used across many widgets (from `nexora-ph-shared.css`), including at least:

Solids (shared semantic): `ink #0B1620`, `muted #56626D`, `lead #3A4753`, `accent #F15E22`, `link #C2410C`, `link-blue #1A5A87`, `on-accent #FFFFFF`, `surface #FFFFFF`, tag/chip/border hexes that appear as standalone colors.

Gradients (shared): `btn-primary`, `btn-dark`, `btn-white`, `btn-case`, `tag`, `tag-blue`, `icon`, `icon-o`, `icon-b`, `card`, `soft`, `cool`, `ink` section wash, `grad-text` (special: keep `background-size` / `background-clip` / animation in CSS; group control or var-bridge owns only the gradient image colors).

Each shared gradient entry includes the `selector` relative to `{{WRAPPER}} .nexora-ph …`.

- [ ] **Step 2: Registrar**

Create `includes/ph-style-colors.php`:

```php
<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

require_once __DIR__ . '/ph-style-colors/_shared-tokens.php';

/**
 * @param \Elementor\Widget_Base $widget Widget.
 * @param string                 $slug   e.g. hero, nav.
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
  $fn = 'nexora_ph_style_colors_inventory_' . str_replace( '-', '_', $slug );
  if ( ! function_exists( $fn ) ) {
    return;
  }

  $inv = $fn();
  if ( ! is_array( $inv ) ) {
    return;
  }

  $solids     = isset( $inv['solids'] ) && is_array( $inv['solids'] ) ? $inv['solids'] : [];
  $gradients  = isset( $inv['gradients'] ) && is_array( $inv['gradients'] ) ? $inv['gradients'] : [];
  $use_shared = ! empty( $inv['include_shared'] );

  if ( $use_shared ) {
    $shared = nexora_ph_style_colors_shared_catalog();
    $solids = array_merge( $shared['solids'] ?? [], $solids );
    $gradients = array_merge( $shared['gradients'] ?? [], $gradients );
  }

  // Dedupe solids by default hex+token; gradients by name.
  // start_controls_section per group label…
  // COLOR controls: selectors '{{WRAPPER}} .nexora-ph' => $shared ? '--ph-{token}: {{VALUE}};' : '--ph-{slug}-{token}: {{VALUE}};'
  // Group_Control_Background for each gradient with fields_options from default.
}
```

Implement section grouping keys: `text`, `accent`, `surface`, `button`, `tag`, `fill`, `widget` with labels matching the spec.

For each gradient:

```php
$widget->add_group_control(
  Group_Control_Background::get_type(),
  [
    'name'           => 'ph_' . $slug . '_' . $g['name'],
    'types'          => [ 'classic', 'gradient' ],
    'selector'       => $g['selector'],
    'fields_options' => [
      'background' => [ 'default' => $g['default']['background'] ?? 'gradient' ],
      'color'      => [ 'default' => $g['default']['color'] ?? '' ],
      'color_b'    => [ 'default' => $g['default']['color_b'] ?? '' ],
      'gradient_type' => [ 'default' => $g['default']['gradient_type'] ?? 'linear' ],
      'gradient_angle' => [ 'default' => $g['default']['gradient_angle'] ?? [ 'unit' => 'deg', 'size' => 180 ] ],
    ],
  ]
);
```

- [ ] **Step 3: Load helper from bootstrap**

In `nexora-for-elementor.php` after other `require_once` includes:

```php
require_once NEXORA_ELE_PATH . 'includes/ph-style-colors.php';
```

- [ ] **Step 4: Commit only if user asks**

```bash
git add includes/ph-style-colors.php includes/ph-style-colors/_shared-tokens.php nexora-for-elementor.php
git commit -m "feat(ph): add style colors registrar and shared catalog"
```

---

### Task 3: Wire every `class-ele-ph-*.php` register_controls

**Files:**
- Modify: all 24 `widgets/class-ele-ph-*.php` `register_controls()` methods

**Interfaces:**
- Consumes: `nexora_ph_register_style_colors( $this, '{slug}' )`
- Produces: Style tab appears once inventory file exists for that slug

- [ ] **Step 1: Patch each widget class**

After content controls, call:

```php
if ( function_exists( 'nexora_ph_register_style_colors' ) ) {
  nexora_ph_register_style_colors( $this, 'hero' ); // slug matches file stem
}
```

Slug map = widget folder name without `ph-` prefix (`ele-ph-hero` → `hero`).

Example for hero `register_controls`:

```php
protected function register_controls(): void {
  if ( function_exists( 'nexora_ph_hero_register_content_controls' ) ) {
    nexora_ph_hero_register_content_controls( $this );
  }
  if ( function_exists( 'nexora_ph_register_style_colors' ) ) {
    nexora_ph_register_style_colors( $this, 'hero' );
  }
}
```

Repeat for: approach, cases, challenge, contact, cta, expertise, faq, footer, framework, growth, hero, impact, industries, insights, logos, nav, partners, pricing, process, solutions, stories, tech, video, why.

- [ ] **Step 2: Commit only if user asks**

```bash
git add widgets/class-ele-ph-*.php
git commit -m "feat(ph): hook style colors registration on all PH widgets"
```

---

### Task 4: Migrate `nexora-ph-shared.css` + first pilot widget (hero)

**Files:**
- Create: `includes/ph-style-colors/hero.php`
- Modify: `assets/css/nexora-ph-shared.css`
- Modify: `assets/css/nexora-ph-hero.css`
- Modify: `widgets/ph-hero/**` if inline hexes exist

**Interfaces:**
- Consumes: shared catalog + registrar
- Produces: hero Style → Colors fully covering hero expected set; shared CSS var fallbacks

- [ ] **Step 1: Run extract for hero; build `hero.php` inventory**

```bash
php bin/ph-style-colors-verify.php --extract=hero
```

Create `includes/ph-style-colors/hero.php` with `nexora_ph_style_colors_inventory_hero(): array` setting `'include_shared' => true` plus hero-only solids/gradients from `nexora-ph-hero.css`.

- [ ] **Step 2: Wire shared + hero CSS to vars / gradient ownership**

Replace standalone solids with `var(--ph-{token}, #HEX)` or `var(--ph-hero-{token}, #HEX)`.  
Remove or neutralize hardcoded backgrounds on selectors owned by gradient group controls (leave layout shadows/borders that are not the fill).

- [ ] **Step 3: Verify hero clean**

```bash
php bin/ph-style-colors-verify.php --slug=hero
```

Expected: hero section shows zero missing/unwired. Other slugs may still fail.

- [ ] **Step 4: Manual Elementor check**

Open PH Hero → Style → Colors; change Text + Primary button gradient; confirm preview; reset.

- [ ] **Step 5: Commit only if user asks**

```bash
git add includes/ph-style-colors/hero.php assets/css/nexora-ph-shared.css assets/css/nexora-ph-hero.css widgets/ph-hero
git commit -m "feat(ph-hero): style colors with shared token wiring"
```

---

### Task 5: Widget batch — nav, footer, cta, logos, partners

**Files:**
- Create: `includes/ph-style-colors/{nav,footer,cta,logos,partners}.php`
- Modify: matching `assets/css/nexora-ph-*.css` and `widgets/ph-*/*` inline colors (nav icons/html, etc.)

- [ ] **Step 1: Build inventories with `include_shared` as needed; wire CSS/inline**
- [ ] **Step 2: Verify each slug clean**

```bash
php bin/ph-style-colors-verify.php --slug=nav
php bin/ph-style-colors-verify.php --slug=footer
php bin/ph-style-colors-verify.php --slug=cta
php bin/ph-style-colors-verify.php --slug=logos
php bin/ph-style-colors-verify.php --slug=partners
```

Expected: each exits 0 for that slug filter.

- [ ] **Step 3: Commit only if user asks**

```bash
git commit -m "feat(ph): style colors for nav footer cta logos partners"
```

---

### Task 6: Widget batch — approach, why, challenge, impact, cases, stories, video

**Files:**
- Create: `includes/ph-style-colors/{approach,why,challenge,impact,cases,stories,video}.php`
- Modify: CSS + inline (`ph-stories/media.php`, `ph-cases`, `ph-video/player.php`, etc.)

- [ ] **Step 1: Inventories + wire (gradient play buttons via group control or class + gradient control)**
- [ ] **Step 2: Verify each slug clean**
- [ ] **Step 3: Commit only if user asks**

```bash
git commit -m "feat(ph): style colors for approach why challenge impact cases stories video"
```

---

### Task 7: Widget batch — pricing, contact, insights, faq, expertise

**Files:**
- Create: `includes/ph-style-colors/{pricing,contact,insights,faq,expertise}.php`
- Modify: CSS + `ph-pricing/data.php` inline card gradients → classes + Style gradient controls; contact form accents; insights more-link

- [ ] **Step 1: Inventories + wire**
- [ ] **Step 2: Verify each slug clean**
- [ ] **Step 3: Commit only if user asks**

```bash
git commit -m "feat(ph): style colors for pricing contact insights faq expertise"
```

---

### Task 8: Widget batch — framework, growth, industries, process, solutions, tech

**Files:**
- Create: `includes/ph-style-colors/{framework,growth,industries,process,solutions,tech}.php`
- Modify: matching CSS (growth has largest own set — expect many solids + gradients)

- [ ] **Step 1: Inventories + wire**
- [ ] **Step 2: Verify each slug clean**
- [ ] **Step 3: Commit only if user asks**

```bash
git commit -m "feat(ph): style colors for framework growth industries process solutions tech"
```

---

### Task 9: Full-suite verify + version bump

**Files:**
- Modify: `nexora-for-elementor.php` version `1.2.7` → `1.2.8`
- Modify: any remaining gaps the full verify reports

- [ ] **Step 1: Full verify**

```bash
php bin/ph-style-colors-verify.php
```

Expected: exit `0`; every slug `missing_solids=[] missing_gradients=[] unwired_solids=[] unwired_gradients=[]`.

If non-zero: fix reported hex/gradient, re-run; do not bump version until clean.

- [ ] **Step 2: Version bump**

In `nexora-for-elementor.php` header and `NEXORA_ELE_VERSION` constant (if present): `1.2.8`.

- [ ] **Step 3: Spot-check matrix (manual)**

For each of hero, nav, pricing, contact, growth: change one solid + one gradient in Elementor; confirm scoped to that instance; reset restores look.

- [ ] **Step 4: Commit only if user asks**

```bash
git commit -m "feat(ph): complete style colors coverage and bump to 1.2.8"
```

---

## Spec coverage checklist

| Spec requirement | Task |
| --- | --- |
| COLOR per distinct standalone solid per widget | 4–8 |
| Gradient picker per distinct gradient usage | 2, 4–8 |
| CSS var fallbacks / unchanged defaults | 4–8 |
| Shared classes covered per widget that uses them | 2 (`include_shared`), 4–8 |
| Exclude glass white/black overlays; map brand rgba | 1 extract rules |
| GTM/Timeline untouched | Global constraint |
| Verify hard gate zero missing/unwired | 1, 9 |
| Registrar + per-slug inventories | 2–3 |
| Grouped Style UI | 2 registrar groups |

## Self-review notes

- No TBD placeholders; 3-stop gradient bridge strategy documented in File structure
- Commits gated on user request (repo rule)
- Type/name consistency: `nexora_ph_register_style_colors`, `nexora_ph_style_colors_inventory_{slug}`, `--ph-{slug}-{token}` / shared `--ph-{token}`
