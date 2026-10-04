# PH Style Colors — paint-role labels

Editors find Style → Colors hard to use because control labels name tokens (`Ink`, `Muted`, `Lead`) instead of what they paint (`Card title`, `Card description`, `Primary button (hover)`).

## Goal

Rename Style → Colors labels so each control answers: **what UI job does this color paint?** Keep the same controls, tokens, groups, and CSS wiring.

## Decisions

| Topic | Choice |
| --- | --- |
| Main pain | Labels don’t say what they paint |
| Multi-use token | One control; label lists main jobs with ` / ` |
| Shared tokens | Per-widget labels (Why vs Footer can differ) |
| Hover / state | State at the end: `Card title (hover)` |
| Approach | Label rewrite only — no regroup, no new/split controls |

## Naming rules

1. Lead with the UI job — `Heading`, `Card title`, `Primary button` — not `Ink` / `Muted`.
2. Multi-use → one control — join with ` / ` — `Card description / captions`.
3. State at the end — `Card title (hover)`, `Primary button (hover)`, `Card panel (hover)`.
4. Per-widget labels — same token may read differently per inventory.
5. Keep current Style groups — Text, Accent / Links, Surfaces / Borders, Buttons, Tags / Chips, Fills / Washes, Widget-specific.
6. No new colors, no control splits, no CSS changes.

### Why (example)

| Token (unchanged) | New label |
| --- | --- |
| `ink` | Heading / body text |
| `muted` | Card description / captions |
| `on-dark` | Card title (hover) |
| `lead-on-dark` | Card description (hover) |
| `hv_dark` | Card panel (hover) |
| `btn` / primary button gradient | Primary button |
| arrow / button hover gradient | Primary button (hover) — when that is the painted job |

Exact copy is finalized per widget during implementation by scanning that widget’s markup/CSS uses.

## Mechanism

1. **Tokens stay the same** (`ink`, `muted`, `hv_dark`, CSS vars, selectors). Only the Elementor `label` string changes.
2. **Shared catalog** keeps a sensible default label (improved toward paint-role where possible).
3. **Per-widget override** — each `includes/ph-style-colors/{slug}.php` may override labels for shared tokens it includes, plus its widget-only entries.
4. **Helper** — add a small merge helper (e.g. apply a `token => label` / `gradient name => label` map onto a subset result) so inventories don’t fork full solid/gradient arrays only to rename.
5. **Registrar** — no change required beyond reading the (possibly overridden) `label` field already used today.

## Rollout

- All 24 PH widgets that register Style colors.
- Run `bin/ph-style-colors-verify.php` after inventory edits (must still exit 0).
- Spot-check Elementor: open Why, Challenge, Hero, Footer — labels read as paint jobs; changing a color still paints the same places.

## Out of scope

- Regrouping sections by UI part (Header / Cards / Hover)
- Splitting one token into multiple pickers
- Hiding “advanced” colors
- Elementor `description` under every control (optional later)
- Changing defaults, CSS, or saved settings keys

## Acceptance

1. In every PH widget Style → Colors, labels describe painted UI jobs, not raw token jargon.
2. Multi-use colors keep a single control with a ` / `-joined label.
3. Hover/active/dark counterparts use `(hover)` (or the accurate state word) at the end.
4. Shared tokens show widget-appropriate labels where the inventory overrides them.
5. Full style-colors verify still passes; front-end look unchanged until an editor changes a color.

## Implementation note

Next step after this spec is approved: write an implementation plan (writing-plans), then apply label maps widget by widget using the helper + inventory overrides.
