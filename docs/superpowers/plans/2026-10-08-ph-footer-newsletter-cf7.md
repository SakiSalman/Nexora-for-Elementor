# PH Footer newsletter CF7 — plan

**Goal:** Contact-style shortcode for footer email + Subscribe only.

## Files

| File | Change |
| --- | --- |
| `widgets/ph-footer/markup.html` | Marker; drop native input/button; no outer `<form>` |
| `widgets/ph-footer/content-map.json` | Drop `footer_f14`/`footer_f15`; re-offset |
| `widgets/ph-footer/controls-content.php` | Newsletter form shortcode control |
| `widgets/ph-footer/render.php` | Inject shortcode / placeholder |
| `assets/css/nexora-ph-footer.css` | CF7 pill styles |
| `docs/superpowers/cf7-footer-newsletter-template.md` | CF7 template |
| `PROSPECTS_HIVE_CONTENT_INVENTORY.md` | Drop f14/f15 rows |

## Tasks

1. Markup + content-map
2. Controls + render
3. CSS + CF7 doc + inventory
