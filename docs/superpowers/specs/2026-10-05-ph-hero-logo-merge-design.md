# PH Hero + Logo Strip merge

**Status:** Implemented (inline execution, 2026-10-05).

## Goal

Merge **PH Logo Strip** into **PH Hero** as one Elementor widget so the Home page composition (hero + overlapping logo strip) is a single unit. **Hard-remove** `ele-ph-logos` from the panel and the codebase. **Do not change the visual design** relative to `C:\Users\USER\Downloads\Home page` (`Redesign.dc.html`).

## Decisions

| Topic | Choice |
| --- | --- |
| Approach | Append logo-strip markup after hero markup inside one widget (demo HTML order) |
| Design fidelity | Match Home page DOM structure, spacing, and overlap; no redesign |
| Legacy Logo Strip | Hard remove — no hidden widget, no auto-migrate |
| Existing pages | Editors delete leftover Logo Strip instances; use merged Hero only |
| Content control IDs | Keep `logos_*` / repeater IDs in the hero content-map for clarity |
| Style colors | Fold logos-only inventory into `hero.php`; drop `logos.php` |
| CSS vars for strip paints | Retarget `--ph-logos-*` → `--ph-hero-*` (or bridge prefixes) under slug `hero` with **identical default values** |
| Version | Keep plugin at `1.2.8` unless a bump is requested at zip time |

## Non-goals

- Auto-migration of Elementor JSON from `ele-ph-logos` → hero settings
- Keeping a deprecated / hidden `ele-ph-logos` registration
- Changing marquee content, pill layout, avatar colors, or hero artwork beyond what the design export already has
- Merging any other PH widgets

## Design reference (source of truth)

From `Redesign.dc.html`:

1. `<!-- 1 HERO -->` — `<section class="hero-sec" style="… padding: 150px 0 190px; min-height: clamp(780px, 100vh, 960px); …">`
2. `<!-- 2 LOGO STRIP -->` — `<section class="logo-sec" style="position: relative; margin-top: -118px; z-index: 3">`

Responsive (must remain):

- `@media (max-width:1024px)` — `.hero-sec { min-height: 0 }`, `.logo-sec { margin-top: -70px }`
- `@media (max-width:760px)` — `.hero-sec { padding: 112px 0 104px }`, logo pill stacking rules from the export

The recent Elementor “zero bottom gap” / flush mobile experiments that fight this overlap **must be reverted** in favor of the design export rules once both blocks live in one widget (no cross-section Elementor gap).

## Target structure

```
.elementor-widget-ele-ph-hero
  .nexora-ph.nexora-ph-hero
    <section class="hero-sec">…</section>
    <section class="logo-sec" style="margin-top:-118px; …">…</section>
```

One wrapper, two sections — same as the design page root, not a new outer layout shell.

## Markup & content

1. Append full `widgets/ph-logos/markup.html` after `widgets/ph-hero/markup.html` (preserve comments and attributes from the design).
2. Merge `widgets/ph-logos/content-map.json` fields into `widgets/ph-hero/content-map.json`:
   - Keep field ids: `logos_f3`…, `logos_r1`, `logos_r2`, etc.
   - **Regenerate byte `start`/`end` offsets** against the combined `markup.html` (offsets are absolute in the file).
   - Map `slug` stays `"hero"`.
   - Content tab sections may include Logo Strip groups (e.g. Logos, Badges) via each field’s `section` label.
3. `nexora_ph_render_section( 'hero', … )` remains the only render path.
4. Bump hero `_schema_version` default if the schema control exists (document in controls).

## Style colors

1. Move logos-only solids/gradients from `includes/ph-style-colors/logos.php` into `includes/ph-style-colors/hero.php`:
   - Logo pill middle tint
   - Logo pill background gradient
   - Avatar 1–4 fills
2. Deduplicate shared tokens already present on hero (`ink`, `muted`, `lead`, `surface`, `accent`, links, `grad_text`, etc.) — do not register twice.
3. Because registrar uses slug `hero`, control names become `ph_hero_pill`, `ph_hero_avatar_1`, … Update CSS signatures / `fields_options` bridges so painted vars match defaults from the design (same colors/stops/angles as today’s logos inventory).
4. Delete `includes/ph-style-colors/logos.php`.
5. Update `bin/ph-style-colors-verify.php` (and any inventory docs) to drop the `logos` slug.

## CSS & assets

1. Append rules from `assets/css/nexora-ph-logos.css` into `assets/css/nexora-ph-hero.css`.
2. Restore design spacing/overlap (remove flush-zero mobile bottom padding that broke the composition).
3. Retarget any `.elementor-widget-ele-ph-logos` selectors to `.elementor-widget-ele-ph-hero` only where needed for overflow; prefer class selectors (`.logo-sec`, `.logo-pill`) so rules match the design export.
4. Delete `assets/css/nexora-ph-logos.css`.
5. Registry + `get_style_depends()`: hero keeps `nexora-ph-fonts`, `nexora-ph-shared`, `nexora-ph-hero` only.

## Hard remove Logo Strip widget

Delete / unregister:

| Item | Action |
| --- | --- |
| `includes/class-widget-registry.php` → `ele-ph-logos` | Remove entry |
| `widgets/class-ele-ph-logos.php` | Delete |
| `widgets/ph-logos/**` | Delete |
| `assets/css/nexora-ph-logos.css` | Delete (after merge) |
| `includes/ph-style-colors/logos.php` | Delete (after merge) |
| Admin / settings | Goes away with registry |
| `PROSPECTS_HIVE_CONTENT_INVENTORY.md` | Fold logos section under hero; note merge |
| Docs that list PH Logo Strip | Update |

**Editor note:** Pages that still contain `widgetType: ele-ph-logos` will show Elementor’s missing-widget state until the instance is removed manually. No migration tool in this scope.

## Widget chrome

- Update hero registry title/description to mention logo strip (e.g. “Hero + logo strip”).
- Single Elementor panel entry: **PH Hero**.

## Success criteria

1. Front-end HTML for hero + logo strip matches the design export structure (two sections, `-118px` overlap on desktop, responsive rules above).
2. Visual composition matches Home page (spacing, pill overlap, marquee, badges) — no intentional redesign.
3. All former Logo Strip content + color controls are editable on PH Hero.
4. `ele-ph-logos` is gone from registry, panel, files, and style-color verify slugs.
5. No second Elementor section required under the hero for the strip.

## Implementation order

1. Merge markup + regenerate content-map offsets.
2. Merge CSS; restore design spacing/overlap; retarget Elementor selectors.
3. Merge style-color inventory; fix var bridges; delete `logos.php`.
4. Update hero widget class / registry description / style depends.
5. Delete logos widget files + registry entry + CSS file.
6. Update inventory/docs/verify script.
7. Manual check vs Home page at desktop, ≤1024, ≤760.
8. Generate plugin zip when requested.

## Risks

| Risk | Mitigation |
| --- | --- |
| Content-map offsets wrong after append | Regenerate from combined markup; spot-check each `logos_*` control in editor |
| Style var rename breaks paint | Keep identical defaults; verify pill/avatars against design |
| Mobile gap regressions | Prefer design export media rules over Elementor gap-kill experiments |
| Orphan `ele-ph-logos` in saved pages | Document hard remove; manual delete in Elementor |
