# PH Style Colors Paint-Role Labels Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans (or subagent-driven-development) to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rename every PH Style → Colors control label to a paint-role name so editors know what each color paints.

**Architecture:** Keep tokens/CSS/groups unchanged. Add `nexora_ph_style_colors_apply_labels()` to overlay label maps onto shared subsets. Improve shared-catalog default labels. Per-widget inventories pass widget-specific solid/gradient label maps and rename their own entries.

**Tech Stack:** PHP inventories in `includes/ph-style-colors/`, Elementor Style tab labels only.

## Global Constraints

- Spec: `docs/superpowers/specs/2026-10-04-ph-style-colors-labels-design.md`
- Multi-use → one control; join jobs with ` / `
- Hover/state suffix: `(hover)` at end
- Per-widget labels for shared tokens
- No new colors, no splits, no regroup, no CSS changes
- `bin/ph-style-colors-verify.php` must exit 0
- Commit only if the user asks

---

### Task 1: Helper + shared catalog defaults

**Files:** `includes/ph-style-colors/_shared-tokens.php`

- [x] Add `nexora_ph_style_colors_apply_labels( array $slice, array $solid_labels, array $gradient_labels ): array`
- [x] Rewrite shared catalog solid/gradient `label` strings to paint-role defaults
- [x] Smoke: PHP lint / load inventories

### Task 2: Per-widget label maps

**Files:** all `includes/ph-style-colors/{slug}.php`

- [x] After `shared_subset`, apply widget-specific label maps
- [x] Rename widget-only solid/gradient labels the same way
- [x] Verify exit 0

### Task 3: Verify

- [x] `php bin/ph-style-colors-verify.php` exit 0
