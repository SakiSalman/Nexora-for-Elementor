# PH Style colors (every distinct color per widget)

Each Prospects Hive (`ele-ph-*`) widget gets a **Style → Colors** section. Solids use Elementor **COLOR** pickers; multi-stop gradients use Elementor **gradient** background controls (`Group_Control_Background`, gradient type — same pattern as GTM Funnel Style). Defaults match today’s CSS/markup so existing pages look unchanged until edited.

ELE GTM Funnel and ELE Timeline keep their existing Style tabs unchanged.

## Goal

- Editors can recolor any brand/UI color that appears in a given PH widget.
- Same solid hex used in multiple places inside one widget → **one** COLOR control (deduped).
- Different hexes → different COLOR controls, even if they look similar.
- Where the UI uses a **gradient**, editors get a **gradient color picker** (not only separate stop COLOR fields), with defaults matching the current gradient.
- No saved Style value → CSS fallback → **identical** to current design.

## What counts as a “distinct color”

Include:

- Solid hexes in that widget’s CSS (`nexora-ph-{slug}.css`)
- Solid hexes in that widget’s PHP/JS inline styles (render/data/media/html/icons)
- Solid hexes from **shared** CSS rules that the widget’s markup actually uses (`.tag`, `.btn-primary`, `.card`, `.soft`, etc.)
- Every distinct **gradient usage** (linear/radial with 2+ color stops) as its own gradient control

Exclude (stay hardcoded):

- Pure opacity overlays of white/black used only for glass/sheen (e.g. `rgba(255,255,255,.38)`) unless they are a unique non-white/black tint
- SVG `currentColor` / inherited strokes with no hex
- GTM Funnel / Timeline (already have Style)

Normalize solids before dedupe: uppercase, expand `#000` → `#000000`, `#FFF` → `#FFFFFF`.

Brand-tinted `rgba(R,G,B,a)` that map to a solid hex already in the inventory (e.g. `rgba(241,94,34,.15)` → `#F15E22`) stay tied to that solid’s CSS var for the RGB channels; alpha stays in CSS.

## Solids vs gradients

| Usage in CSS/markup | Style control | Wiring |
| --- | --- | --- |
| Single solid (`color`, `border-color`, one-stop `background`) | Elementor `COLOR` | `--ph-{slug}-{token}: {{VALUE}}` + `var(..., #fallback)` |
| `linear-gradient` / `radial-gradient` with 2+ stops (buttons, icons, tags, section washes, `.grad-text`, cards, etc.) | Elementor `Group_Control_Background` with **gradient** (and classic if useful), defaulted to current stops/angle | Apply `background` (or `background-image`) on the real selector(s); keep current look as group-control defaults |
| Same gradient string reused on multiple selectors in one widget | **One** gradient control targeting all those selectors (comma-separated / shared class) | Do not split into N duplicate pickers |
| Gradient stop hex that also appears alone as a solid elsewhere | Solid still gets its own COLOR if used standalone; gradient keeps its own gradient picker for that fill | Two controls are OK — different jobs |

Gradient inventory key = full gradient signature (type + angle/position + ordered stops), not individual stop hexes alone. Stop hexes that only exist inside a gradient do **not** need separate COLOR controls unless they also appear as standalone solids.

Examples that must use a gradient picker: `.btn-primary`, `.btn-dark`, `.btn-white`, `.icon` fills, `.tag` backgrounds, `.grad-text`, section `.soft` / `.cool` / `.ink` washes, pricing card header gradients, stories/video play-button fills, contact founder-card gradient, etc.

## Mechanism

1. Per-widget inventory: `solids[]` + `gradients[]` (PHP arrays under `includes/ph-style-colors/`).
2. Register Style → Colors on that widget only.
3. Solids:

```php
'selectors' => [
  '{{WRAPPER}} .nexora-ph' => '--ph-{slug}-{token}: {{VALUE}};',
],
'default' => '#F15E22',
```

4. Gradients: `Group_Control_Background` with `types` including `gradient`, `selector` = the element(s) that currently use that gradient, `fields_options` defaults matching today’s stops/angle. Prefer applying background on the painted element (same approach as GTM Funnel). Where a gradient is also animated (e.g. `.grad-text` background-size/clip), keep non-color CSS in the stylesheet and only let the group control own the gradient image/colors.
5. CSS/markup for solids uses `var(--ph-{slug}-{token}, #FALLBACK)`. For gradients owned by group controls, remove the competing hardcoded `background`/`background-image` from CSS (or set to `var` only when using a CSS-var bridge); defaults on the control must reproduce the current look when unset/default.
6. Labels are human-readable by usage (e.g. “Primary button”, “Accent icon”, “Section wash”).

## Control grouping (UX)

Group under Style headings:

- Text (solids)
- Accent / links (solids)
- Surfaces / borders (solids)
- Buttons (gradient pickers + on-button text solids)
- Tags / chips (gradient + solid text/dot as needed)
- Fills / washes (section and card gradients)
- Widget-specific

Every distinct solid and every distinct gradient usage still gets its own control; grouping is only for panel order.

## Per-widget inventory (solid hexes found today)

Counts below are from widget CSS + inline PHP/JS. Shared-class colors used by the widget are **added at implementation** by scanning that widget’s markup against `nexora-ph-shared.css` (so e.g. CTA/Expertise/Partners are not “0 colors”).

| Widget | Distinct in own CSS/inline | Notes |
| --- | ---: | --- |
| ph-hero | 9 | + shared tags/buttons/soft |
| ph-nav | ~27 + icon strokes | includes mega-menu blues/oranges |
| ph-approach | 3 | + shared icon/card/tag |
| ph-why | 4 | + shared |
| ph-challenge | 2 | + shared |
| ph-impact | 6 | + shared tabs/cards |
| ph-cases | 11 | + shared |
| ph-stories | ~10 | CSS + play-button inline |
| ph-video | ~8 | + play icon |
| ph-pricing | ~20 | CSS + card/tier inline |
| ph-contact | 13 | + shared buttons |
| ph-insights | 2+ | + shared cards/more link |
| ph-faq | 14 | |
| ph-footer | 12 | + shared footer link colors |
| ph-framework | 19 | |
| ph-growth | 30 | largest own CSS set |
| ph-industries | 18 | |
| ph-process | 8 | |
| ph-solutions | 24 | |
| ph-tech | 13 | |
| ph-logos | 2 | + shared strip |
| ph-cta | 0 own | inventory = shared classes used in markup |
| ph-expertise | 0 own | same |
| ph-partners | 0 own | same |
| Shared base | 63 hexes in file | not a widget; vars consumed by widgets that use those selectors |

Exact token lists are finalized during implementation by extract + verify (below). Spec requirement: **no distinct solid hex left without a Style control on the widget that displays it.**

## Verification (hard gate — no missed colors)

Implementation is incomplete until verification passes for **every** `ele-ph-*` widget. Missing even one includable color is a fail.

### Sources scanned per widget

1. `assets/css/nexora-ph-{slug}.css`
2. All PHP/JS under `widgets/ph-{slug}/` (inline `style=`, SVG `fill`/`stroke`, gradients)
3. `assets/css/nexora-ph-shared.css` rules whose selectors match classes present in that widget’s rendered markup
4. Any other CSS the widget enqueues that is PH-owned (not Elementor/core)

### Extract rules

- **Solids:** Match `#RGB` / `#RRGGBB` / `#RRGGBBAA` (ignore alpha nibble for dedupe key → base RGB); `rgb()` / `rgba()` brand tints map to solid RGB; skip excluded white/black opacity overlays
- Normalize solids: uppercase, `#000` → `#000000`, `#FFF` → `#FFFFFF`
- **Gradients:** Match each `linear-gradient(...)` / `radial-gradient(...)` with 2+ color stops; normalize whitespace; key = type + angle/position + ordered stop colors
- Build **ExpectedSolids[slug]** and **ExpectedGradients[slug]** from sources above
- Stop hexes that appear *only* inside a covered gradient are satisfied by the gradient control (not required as separate COLOR entries)

### Coverage checks (must all pass)

| Check | Pass condition |
| --- | --- |
| Solids complete | Every hex in **ExpectedSolids[slug]** (standalone uses) exists as a Style COLOR `default` |
| Gradients complete | Every entry in **ExpectedGradients[slug]** has a `Group_Control_Background` (gradient) with matching defaults/selectors |
| Solid wired | Standalone solid occurrences use `var(--ph-{slug}-{token}, #HEX)` — no orphan hardcoded includable solid |
| Gradient wired | Each inventoried gradient’s selectors are owned by the group control (no competing hardcoded gradient left on those properties) |
| No false green | Orphan inventory tokens/controls with no source usage are removed |
| Cross-widget | Shared solids/gradients are covered on **each** widget that displays them |

### How to run

- Ship a small extract/verify script under `docs/superpowers/plans/` or `bin/` (PHP or Node) that prints:

  - per slug: expected solids/gradients, inventory counts, **missing_solids[]**, **missing_gradients[]**, **unwired_solids[]**, **unwired_gradients[]**, **orphan[]**
  - exit non-zero if any missing/unwired

- Run once after migration and again before release. Paste the clean report into the implementation plan checklist.

### Manual spot-check

- In Elementor, open each PH widget → Style → Colors: solid count and gradient-control count ≥ expected.
- Change one solid and one gradient (e.g. primary button) and confirm the front end updates; reset confirms default look.

## Registration pattern

- `includes/ph-style-colors.php` loads inventories and exposes `nexora_ph_register_style_colors( $widget, $slug )`.
- Each `class-ele-ph-*.php` calls it once in `register_controls()`.
- Inventories live beside the helper (one array per slug) so Style labels/tokens stay reviewable in git.

## CSS / markup migration

- Prefer CSS files; move stubborn inline hexes to classes or `style="color: var(--ph-…, #…)"`.
- Keep existing `!important` locks, but point them at the same vars so Style edits still apply.
- Do not change layout, radii, or non-color values.

## Compatibility

- Fresh widget: Style shows current hex defaults; front end matches today.
- Existing pages with no Style colors saved: fallbacks → unchanged.
- Edits are `{{WRAPPER}}`-scoped (per instance).

## Out of scope

- Typography / spacing Style sections
- Changing GTM Funnel / Timeline Style
- Per-slide or per-repeater item colors (instance-level only, not row-level)
- Alpha-only decorative overlays listed under “Exclude”

## Acceptance

1. For every PH widget, every distinct standalone solid hex is editable via COLOR, and every distinct gradient usage via a gradient background picker.
2. **Verify script reports zero missing/unwired solids and gradients for every PH slug** (see Verification).
3. With all controls at default (or unset), visual output matches pre-change screenshots/CSS.
4. Changing one Style control on one widget instance does not affect other widgets or other instances.
