# PH Hero + Logo Strip Merge Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Merge PH Logo Strip into PH Hero as one Elementor widget matching the Home page design, then hard-remove `ele-ph-logos` from the codebase.

**Architecture:** Append design-export logo-strip markup after hero markup inside one `.nexora-ph-hero` wrapper; shift content-map byte offsets by the hero markup length; fold logos CSS and style-color inventory into hero; delete the logos widget registration and files. Restore Home page spacing/overlap (no flush-zero gap experiments).

**Tech Stack:** WordPress, Elementor widgets, PH content-map (`ph-content.php` / `ph-markup.php`), PHP style-color inventories, CSS from design export.

## Global Constraints

- Visual design must match `C:\Users\USER\Downloads\Home page\Redesign.dc.html` (hero + overlapping logo strip).
- Hard remove `ele-ph-logos` — no hidden legacy widget, no auto-migrate.
- Keep content control ids `logos_*`, `logos_r1`, `logos_r2`.
- Plugin version stays `1.2.8` unless the user asks to bump.
- Do not create git commits unless the user explicitly asks.
- Spec: `docs/superpowers/specs/2026-10-05-ph-hero-logo-merge-design.md`.

## File map

| File | Role after merge |
| --- | --- |
| `widgets/ph-hero/markup.html` | Hero section + logo strip section |
| `widgets/ph-hero/content-map.json` | Hero + logos fields (shifted offsets) |
| `assets/css/nexora-ph-hero.css` | Hero + logos styles; design overlap restored |
| `includes/ph-style-colors/hero.php` | Hero + logos-only colors |
| `widgets/class-ele-ph-hero.php` | Title/description/keywords update |
| `includes/class-widget-registry.php` | Drop `ele-ph-logos`; update hero blurb |
| Delete `widgets/class-ele-ph-logos.php`, `widgets/ph-logos/**`, `assets/css/nexora-ph-logos.css`, `includes/ph-style-colors/logos.php` | Hard remove |

---

### Task 1: Merge markup + content-map

**Files:**
- Modify: `widgets/ph-hero/markup.html`
- Modify: `widgets/ph-hero/content-map.json`
- Modify: `widgets/ph-hero/controls-content.php` (`_schema_version` → `'2'`)
- Read: `widgets/ph-logos/markup.html`, `widgets/ph-logos/content-map.json`

**Interfaces:**
- Consumes: logos markup bytes; logos content-map with absolute offsets relative to logos file
- Produces: combined markup; hero content-map with logos fields whose `start`/`end` are shifted by `7813` (current UTF-8 length of hero `markup.html`, which already ends with `\n`)

- [ ] **Step 1: Append logos markup to hero markup**

Hero file currently ends with `\n` (byte 10). Concatenate without an extra blank line:

```powershell
$heroPath = "widgets/ph-hero/markup.html"
$logosPath = "widgets/ph-logos/markup.html"
$hero = [System.IO.File]::ReadAllBytes((Resolve-Path $heroPath))
$logos = [System.IO.File]::ReadAllBytes((Resolve-Path $logosPath))
$offset = $hero.Length  # expect 7813
[System.IO.File]::WriteAllBytes((Resolve-Path $heroPath), ($hero + $logos))
Write-Output "offset=$offset combined=$($hero.Length + $logos.Length)"
```

Expected: `offset=7813 combined=12509`. Combined file must start with `<!-- 1 HERO -->` and later contain `<!-- 2 LOGO STRIP -->` and `margin-top: -118px`.

- [ ] **Step 2: Merge content-map with shifted offsets**

Run from plugin root (PowerShell). Shift every logos singleton/repeater absolute `start`/`end` by `$offset`. Leave `shellFields` relative offsets unchanged. Remap logos singleton `section: "Header"` → `"Logo strip"` so they do not merge into the hero Header panel group. Keep repeater sections `Logos` and `Badges`.

```powershell
$offset = 7813
$heroMapPath = "widgets/ph-hero/content-map.json"
$logosMapPath = "widgets/ph-logos/content-map.json"
$heroMap = Get-Content -Raw $heroMapPath | ConvertFrom-Json
$logosMap = Get-Content -Raw $logosMapPath | ConvertFrom-Json

function Shift-Field($field, $delta) {
  if ($null -eq $field) { return $field }
  if ($field.PSObject.Properties.Name -contains 'start') { $field.start = [int]$field.start + $delta }
  if ($field.PSObject.Properties.Name -contains 'end') { $field.end = [int]$field.end + $delta }
  if ($field.PSObject.Properties.Name -contains 'openStart') { $field.openStart = [int]$field.openStart + $delta }
  if ($field.PSObject.Properties.Name -contains 'openEnd') { $field.openEnd = [int]$field.openEnd + $delta }
  if ($field.PSObject.Properties.Name -contains 'closeStart') { $field.closeStart = [int]$field.closeStart + $delta }
  if ($field.PSObject.Properties.Name -contains 'closeEnd') { $field.closeEnd = [int]$field.closeEnd + $delta }
  return $field
}

$shiftedSingles = @()
foreach ($s in $logosMap.singletons) {
  $copy = $s | ConvertTo-Json -Depth 20 | ConvertFrom-Json
  $copy = Shift-Field $copy $offset
  if ($copy.section -eq 'Header') { $copy.section = 'Logo strip' }
  $shiftedSingles += $copy
}

$shiftedReps = @()
foreach ($r in $logosMap.repeaters) {
  $copy = $r | ConvertTo-Json -Depth 30 | ConvertFrom-Json
  $copy = Shift-Field $copy $offset
  # Do NOT shift shellFields[*].start/end — those are relative to each shell string
  $shiftedReps += $copy
}

$heroMap.singletons = @($heroMap.singletons) + $shiftedSingles
$heroMap.repeaters = @($heroMap.repeaters) + $shiftedReps
$heroMap.slug = 'hero'
$heroMap | ConvertTo-Json -Depth 40 -Compress | Set-Content -Path $heroMapPath -Encoding utf8NoBOM
```

If `utf8NoBOM` is unavailable, write via .NET `UTF8Encoding($false)`.

- [ ] **Step 3: Verify mapped slices against combined markup**

```powershell
$html = [System.IO.File]::ReadAllText("widgets/ph-hero/markup.html")
$map = Get-Content -Raw "widgets/ph-hero/content-map.json" | ConvertFrom-Json
foreach ($id in @('logos_f3','logos_f5','logos_f9')) {
  $f = $map.singletons | Where-Object { $_.id -eq $id }
  $slice = $html.Substring([int]$f.start, [int]$f.end - [int]$f.start)
  Write-Output "$id => [$slice]"
}
$r1 = $map.repeaters | Where-Object { $_.id -eq 'logos_r1' }
Write-Output ("logos_r1 starts with: " + $html.Substring([int]$r1.start, 40))
```

Expected:
- `logos_f3 => [Clients]`
- `logos_f5 => [200+]`
- `logos_f9 => [Instantly, HeyReach, GetSales, lemlist & Apollo]`
- `logos_r1` slice starts with `<span class="logo-chip">`

Also confirm an existing hero field still works, e.g. `hero_f3` → `B2B Outbound and GTM Agency`.

- [ ] **Step 4: Bump schema version**

In `widgets/ph-hero/controls-content.php`, change:

```php
'default' => '1',
```

to:

```php
'default' => '2',
```

- [ ] **Step 5: Smoke-check PHP map load (optional)**

```bash
php -r "define('ABSPATH','1'); define('NEXORA_ELE_PATH', getcwd().'/'); require 'includes/ph-content.php'; \$m=nexora_ph_content_map('hero'); echo count(\$m['singletons']).' '.count(\$m['repeaters']);"
```

Expected: singleton count = previous hero + 7; repeater count = previous hero + 2 (1 + 2 = 3 if hero had one repeater).

---

### Task 2: Merge CSS + restore design overlap

**Files:**
- Modify: `assets/css/nexora-ph-hero.css`
- Read: `assets/css/nexora-ph-logos.css`
- Reference: Home page media rules for `.hero-sec` / `.logo-sec`

**Interfaces:**
- Consumes: logos CSS rules; design export padding/overlap
- Produces: single hero stylesheet that paints hero + strip with desktop `-118px` overlap

- [ ] **Step 1: Append logos paint rules into hero CSS**

Copy from `nexora-ph-logos.css` into the end of `nexora-ph-hero.css` (keep these selectors/declarations):

- `.mask-x`, `.logo-img`, `.logo-pill`, `.logo-chip`, `.logo-av-1` … `.logo-av-4`
- marquee rules under `.logo-pill`
- `@media (min-width:1025px)` logo-sec sizing block
- `@media (max-width:1024px)` / `760px` logo-pill stacking (grid, border-right, padding)

Do **not** copy the flush-zero Elementor blocks that set `.logo-sec{margin-top:0}`.

- [ ] **Step 2: Retarget CSS variables for hero slug**

In the merged logo rules, replace var prefixes so Style controls under slug `hero` paint correctly:

| Old | New |
| --- | --- |
| `--ph-logos-pill` | `--ph-hero-pill` |
| `--ph-logos-pill-mid` | `--ph-hero-pill-mid` |
| `--ph-logos-pill-end` | `--ph-hero-pill-end` |
| `--ph-logos-pill-angle` / `*-start-stop` / `*-end-stop` | `--ph-hero-pill-*` equivalents |
| `--ph-logos-av1` … `av4` (+ angle/end/stops) | `--ph-hero-av1` … `--ph-hero-av4` (+ same suffixes) |

Defaults in `var(--…, fallback)` must stay identical to today’s logos CSS.

- [ ] **Step 3: Restore design hero/logo spacing (revert flush experiments)**

Replace the current tablet/phone hero spacing block in `nexora-ph-hero.css` with design-aligned rules:

```css
/* Elementor wrapper: allow overlap; no extra chrome */
.elementor-widget-ele-ph-hero,
.elementor-widget-ele-ph-hero > .elementor-widget-container,
.elementor-widget-ele-ph-hero .nexora-ph{
	margin:0 !important;
	padding:0 !important;
	overflow:visible !important;
	min-height:0 !important;
	background:transparent !important
}
.elementor-element:has(.elementor-widget-ele-ph-hero){
	overflow:visible !important
}
@media (max-width:1024px){
	.hero-sec .stack-sm{grid-template-columns:minmax(0,1fr) !important;gap:56px !important}
	.hero-sec .stack-sm>div:last-child{max-width:420px !important}
	.hero-sec{min-height:0 !important}
	.hero-sec .ghost-word{bottom:-2% !important}
	.logo-sec{margin-top:-70px !important}
}
@media (max-width:760px){
	.hero-sec{padding:112px 0 104px !important}
	.hero-sec .btn{height:52px;padding:0 24px;font-size:15.5px}
	.hero-sec .lead{font-size:17px !important}
	.hero-rating{padding:4px 0}
	.hr-badge{width:46px;height:46px}
	.hr-top strong{font-size:24px}
	.hr-stars svg{width:16px;height:16px}
	.hc-chips span{font-size:10.5px;padding:4px 8px}
	.hc-chips i{display:none}
	.hc-spark{width:70%}
}
```

Desktop `.logo-sec` keeps inline `margin-top: -118px` from markup. Do not zero it in CSS except where the design uses `-70px` at ≤1024.

- [ ] **Step 4: Visual check checklist**

Against Home page at three widths:

1. Desktop: logo pill overlaps hero bottom (~`-118px`).
2. ≤1024: strip stacks; overlap `-70px`; hero `min-height` not forcing empty viewport.
3. ≤760: hero `padding: 112px 0 104px`; pill full-width.

---

### Task 3: Merge style colors into hero inventory

**Files:**
- Modify: `includes/ph-style-colors/hero.php`
- Delete (in Task 5): `includes/ph-style-colors/logos.php`
- Modify: `bin/ph-style-colors-verify.php` (remove `'logos'` from slug list)

**Interfaces:**
- Consumes: logos-only solid `pill-mid` + gradients `pill`, `avatar_1`…`avatar_4` from `logos.php`
- Produces: same paints via `nexora_ph_register_style_colors( $this, 'hero' )` → controls `ph_hero_pill_mid`, `ph_hero_pill`, `ph_hero_avatar_1`…

- [ ] **Step 1: Add logos-only entries to hero inventory**

Inside `nexora_ph_style_colors_inventory_hero()`, merge into the existing `solids` / `gradients` arrays (after current hero-only entries). Do **not** re-add shared tokens already in hero (`ink`, `muted`, `lead`, `surface`, `accent`, links, `grad_text`, etc.).

Solids — add:

```php
[
	'token'   => 'pill-mid',
	'label'   => 'Logo pill — middle tint',
	'default' => '#FFF4EC',
	'group'   => 'fill',
],
```

Gradients — add (note prefixes `ph-hero-pill` / `ph-hero-avN` to match Task 2 CSS):

```php
[
	'name'           => 'pill',
	'label'          => 'Logo pill background',
	'group'          => 'fill',
	'selector'       => '{{WRAPPER}} .nexora-ph .logo-pill',
	'default'        => [
		'background'     => 'gradient',
		'color'          => 'rgba(255,255,255,.86)',
		'color_b'        => 'rgba(232,242,251,.78)',
		'color_stop'     => [ 'unit' => '%', 'size' => 0 ],
		'color_b_stop'   => [ 'unit' => '%', 'size' => 100 ],
		'gradient_type'  => 'linear',
		'gradient_angle' => [ 'unit' => 'deg', 'size' => 120 ],
	],
	'signature'      => 'linear-gradient(var(--ph-hero-pill-angle,120deg),var(--ph-hero-pill,rgba(255,255,255,.86)) var(--ph-hero-pill-start-stop,0%),color-mix(in srgb,var(--ph-hero-pill-mid,#FFF4EC) 74%,transparent) 45%,var(--ph-hero-pill-end,rgba(232,242,251,.78)) var(--ph-hero-pill-end-stop,100%))',
	'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-hero-pill' ) ),
	'notes'          => 'The Logo pill middle tint solid stays the fixed middle stop (45%, 74% opacity).',
],
nexora_ph_style_colors_linear_gradient( 'avatar_1', 'Avatar 1 fill', 'fill', '{{WRAPPER}} .nexora-ph .logo-av-1', 'ph-hero-av1', '#FF9D72', '#F15E22', 135 ),
nexora_ph_style_colors_linear_gradient( 'avatar_2', 'Avatar 2 fill', 'fill', '{{WRAPPER}} .nexora-ph .logo-av-2', 'ph-hero-av2', '#7CC2F0', '#2374AC', 135 ),
nexora_ph_style_colors_linear_gradient( 'avatar_3', 'Avatar 3 fill', 'fill', '{{WRAPPER}} .nexora-ph .logo-av-3', 'ph-hero-av3', '#DCE4EA', '#8FA0AF', 135 ),
nexora_ph_style_colors_linear_gradient( 'avatar_4', 'Avatar 4 fill', 'fill', '{{WRAPPER}} .nexora-ph .logo-av-4', 'ph-hero-av4', '#FFD7C4', '#F5A77E', 135 ),
```

- [ ] **Step 2: Update verify script**

In `bin/ph-style-colors-verify.php`, remove `'logos'` from the slug array. Keep `'hero'`.

- [ ] **Step 3: Run verify for hero**

```bash
php bin/ph-style-colors-verify.php
```

Expected: hero passes; no logos slug errors; no missing `--ph-hero-pill*` / avatar signatures vs CSS.

---

### Task 4: Update hero widget chrome + registry

**Files:**
- Modify: `widgets/class-ele-ph-hero.php`
- Modify: `includes/class-widget-registry.php`

- [ ] **Step 1: Update hero widget copy**

In `class-ele-ph-hero.php`:

```php
public function get_title(): string {
	return esc_html__( 'PH Hero', 'nexora-elementor' );
}

public function get_keywords(): array {
	return [ 'nexora', 'prospects', 'hero', 'logos', 'logo strip' ];
}
```

File docblock: mention hero + logo strip.

`get_style_depends()` stays `[ 'nexora-ph-fonts', 'nexora-ph-shared', 'nexora-ph-hero' ]` (no logos handle).

- [ ] **Step 2: Update registry hero entry; remove logos entry**

In `includes/class-widget-registry.php`:

1. Change hero `description` to something like: `Prospects Hive hero with rating, artwork, and logo strip.`
2. Delete the entire `'ele-ph-logos' => [ ... ],` array entry (styles + scripts).

---

### Task 5: Hard-delete logos widget files

**Files:**
- Delete: `widgets/class-ele-ph-logos.php`
- Delete: `widgets/ph-logos/controls-content.php`
- Delete: `widgets/ph-logos/content-map.json`
- Delete: `widgets/ph-logos/markup.html`
- Delete: `widgets/ph-logos/render.php`
- Delete: `assets/css/nexora-ph-logos.css`
- Delete: `includes/ph-style-colors/logos.php`

- [ ] **Step 1: Delete files listed above**

```powershell
Remove-Item -Force @(
  "widgets/class-ele-ph-logos.php",
  "widgets/ph-logos/controls-content.php",
  "widgets/ph-logos/content-map.json",
  "widgets/ph-logos/markup.html",
  "widgets/ph-logos/render.php",
  "assets/css/nexora-ph-logos.css",
  "includes/ph-style-colors/logos.php"
)
# Remove empty dir if empty
if ((Get-ChildItem "widgets/ph-logos" -ErrorAction SilentlyContinue | Measure-Object).Count -eq 0) {
  Remove-Item -Force -Recurse "widgets/ph-logos"
}
```

- [ ] **Step 2: Grep for leftovers**

```bash
rg -n "ele-ph-logos|ph-logos|nexora-ph-logos|inventory_logos" --glob '!docs/**' --glob '!.superpowers/**'
```

Expected: no runtime hits under `widgets/`, `includes/`, `assets/`, `bin/`. Docs may still mention the merge historically.

- [ ] **Step 3: Confirm registry load**

```bash
php -r "define('ABSPATH','1'); /* bootstrap minimally if needed */"
```

Prefer opening WP admin Elementor panel: **PH Logo Strip** must be absent; **PH Hero** present.

---

### Task 6: Docs inventory + final verification

**Files:**
- Modify: `PROSPECTS_HIVE_CONTENT_INVENTORY.md` (fold logos under hero; note merge)
- Optional: short note in `docs/superpowers/specs/2026-10-05-ph-hero-logo-merge-design.md` status line if the project tracks status there

- [ ] **Step 1: Update content inventory**

Move the `## logos` control list under hero as “Logo strip (merged)”. State that `ele-ph-logos` was removed.

- [ ] **Step 2: Front-end verification vs Home page**

On a page with **only** PH Hero (remove any leftover Logo Strip instance manually in Elementor):

1. Desktop: composition matches design (overlap, pill, marquee, badges).
2. Tablet/phone: same responsive behavior as Home page.
3. Elementor Content: Logo strip / Logos / Badges sections editable.
4. Style → Colors: Logo pill + avatars editable; defaults look unchanged.
5. No missing-widget error from a deleted logos instance on a clean page.

- [ ] **Step 3: Zip only if user asks**

Use the existing forward-slash zip procedure to `dist/nexora-for-elementor-1.2.8.zip` + Downloads when requested.

---

## Spec coverage check

| Spec requirement | Task |
| --- | --- |
| Append markup, two sections, `-118px` | Task 1 + 2 |
| Design fidelity / restore padding | Task 2 |
| Merge content-map + keep `logos_*` ids | Task 1 |
| Fold style colors; drop `logos.php` | Task 3 + 5 |
| Merge CSS; retarget Elementor selectors | Task 2 |
| Hard remove registry + files | Task 4 + 5 |
| Update inventory/docs/verify | Task 3 + 6 |
| Manual Home page check | Task 6 |
| No auto-migrate / no hidden legacy | Tasks 4–5 (delete only) |
