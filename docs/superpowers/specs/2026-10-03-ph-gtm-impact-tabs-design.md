# PH GTM Impact tabs

PH GTM Impact keeps its current tabbed layout (tablist + image panel + copy panel). Tab copy moves out of hardcoded JS into an Elementor **Tabs** repeater, following the Pricing pattern: PHP prints JSON; JS only switches the active tab.

Each tab has its own **label, title, body, image, image alt, and points**. Editors can add/remove tabs. Points are a newline textarea (add/remove lines freely), same as Pricing list fields.

## Sidebar

```text
Header                          existing mapped controls
├── Eyebrow                     impact_f1
├── Heading                     impact_f2
├── Highlight                   impact_f3
└── HTML Tag                    impact_tag1

Tabs                            new Content section (repeater)
├── Tab label                   TEXT (tab button + image badge)
├── Title                       TEXT
├── Body                        TEXTAREA
├── Image                       MEDIA (default impact.webp)
├── Image alt                   TEXT
└── Points                      TEXTAREA, one point per line

Content                         existing mapped controls (trimmed)
└── Accessible label            impact_f4 (tablist aria-label)
```

Remove the singleton Content **Image** / **Image alt** controls (`impact_f5`, `impact_f6`). Those become per-tab fields so each tab can show a different image.

## Defaults

Seed **5 tabs** from the current `IMPACT` array in `nexora-ph-impact.js`:

| Label | Title |
| --- | --- |
| Better Targeting | Find the accounts that matter |
| Relevant Engagement | Reach buyers with messages that land |
| Scalable Execution | Grow outreach without growing headcount |
| Revenue Visibility | See exactly what drives pipeline |
| Predictable Growth | Build a pipeline you can plan around |

Each default row keeps its current body and four points. Image defaults to the packaged `impact.webp` URL (same MEDIA default pattern as other PH widgets). Image alt defaults to `Connected GTM targeting map` on every seeded row.

Empty points lines are dropped when building the payload (Pricing `lines()` behavior). An empty tabs repeater falls back to the five seeded defaults so the section never renders with zero tabs.

## Rendering

### Data bridge

- Add `widgets/ph-impact/data.php` (Pricing-style): defaults, normalize repeater rows, build a JSON-safe payload `{ tabs: [...] }`.
- Each tab in the payload: `label`, `title`, `body`, `image` (resolved URL), `alt`, `points` (string array).
- Markup embeds the payload in a `<script type="application/json" class="ph-impact-data">` (or equivalent) inside the existing PH template shell, replaced at render like `__PH_PRICING_JSON__`.
- Mapped header + aria-label still go through `nexora_ph_apply_content` / content-map. Image holes in markup become JS-driven (`{{ impact.image }}` / `{{ impact.alt }}`), not content-map singletons.

### JS (`nexora-ph-impact.js`)

- Delete the hardcoded `IMPACT` constant.
- Read JSON from the widget root (same approach as `nexora-ph-pricing.js`).
- `renderVals`:
  - `impactTabs` from `tabs` with `cls` / `sel` / `pick` for the active index
  - `impact` = active tab object (`label`, `title`, `body`, `points`, `image`, `alt`)
- Clamp active index when the tab count changes.
- Image badge continues to show `impact.label`.

### Markup / render

Keep the current tablist + tabpanel structure. Change the image tag to JS bindings:

- `src="{{ impact.image }}"`
- `alt="{{ impact.alt }}"`
- Badge / title / body / points stay `{{ impact.label }}`, `{{ impact.title }}`, `{{ impact.body }}`, `{{ impact.points }}`

`render.php` loads markup, injects `__PH_IMPACT_JSON__` from `nexora_ph_impact_payload()`, then runs mapped content for Header + aria-label only (content-map without `impact_f5` / `impact_f6`).

Default tab images use the same MEDIA default URL pattern as other PH widgets (`NEXORA_ELE_URL . 'assets/images/prospects/impact.webp'`). While the chosen URL is still that default, the payload may emit the short `assets/impact.webp` form so the existing PH `assets/` rewrite can run—same rule as the content-map image docs.

## Accessibility

- Tablist `aria-label` stays editable via `impact_f4`.
- Tab buttons keep `role="tab"` and `aria-selected`.
- Panel keeps `role="tabpanel"`.
- Image uses per-tab alt text.

## Editor

- Repeater edits re-render the widget preview (template render).
- Switching tabs in the preview only updates client state; it does not write Elementor settings.
- Title field on the repeater row uses Tab label.

## Out of scope

- Changing Framework / Cases script-owned copy (separate widgets)
- Nested Elementor repeater for points (textarea lines only)
- Style-tab redesign of the tab chrome
- Per-tab icon controls (image only)
