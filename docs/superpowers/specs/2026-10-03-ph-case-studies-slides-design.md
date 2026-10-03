# PH Case Studies slides

PH Case Studies keeps its current carousel (card + image/stats panel, dots, prev/next, 6s autoplay). Slide copy moves out of hardcoded JS into an Elementor **Slides** repeater, following the Impact/Pricing pattern: PHP prints JSON; JS only switches the active slide.

Each slide has its own **title, summary, client, industry, services, image, image alt, button text, button link, and stats**. Editors can add/remove slides. Stats are a newline textarea (`value | label` per line), add/remove freely.

## Sidebar

```text
Header                          existing mapped controls
├── Eyebrow                     cases_f2
├── Heading                     cases_f3
├── Highlight                   cases_f4
└── HTML Tag                    cases_tag1

Content                         existing mapped controls (trimmed)
├── Label                       cases_f5  ("Featured case" chip)
├── Eyebrow                     cases_f8  (no-image fallback tag)
├── Previous aria-label         cases_f9
└── Next aria-label             cases_f10

Case facts                      existing mapped repeater cases_r1
└── Text                        row labels: Client / Industry / Services

Slides                          new Content section (repeater)
├── Title                       TEXT
├── Summary                     TEXTAREA
├── Client                      TEXT
├── Industry                    TEXT
├── Services                    TEXT
├── Image                       MEDIA (default case1/2/3.webp by seed)
├── Image alt                   TEXT
├── Button text                 TEXT
├── Button link                 TEXT / URL
└── Stats                       TEXTAREA, one "value | label" per line
```

Remove singleton **Button text** / **Button link** (`cases_f7`, `cases_f6`). Those become per-slide fields.

## Defaults

Seed **3 slides** from the current `CASES` array in `nexora-ph-cases.js` (titles, summaries, facts, stats, images `case1.webp`–`case3.webp`, alts).

Each seeded slide gets:

- Button text: `View full case study`
- Button link: `#` (same as today’s singleton default)

Stats default as three `value | label` lines matching the current design (e.g. `18,260 | Prospects engaged`).

Empty stats lines are dropped. A line without `|` is value-only (label empty). An empty slides repeater falls back to the three seeded defaults so the section never renders with zero slides.

When a slide has no image URL (cleared MEDIA), payload sets `hasImg: false` so the existing no-image fallback panel still works.

## Rendering

### Data bridge

- Add `widgets/ph-cases/data.php`: defaults, normalize rows, JSON-safe payload `{ cases: [...] }`.
- Each case in the payload: `title`, `summary`, `client`, `industry`, `services`, `img` (resolved URL or empty), `alt`, `hasImg`, `buttonText`, `buttonUrl`, `buttonTarget`, `buttonRel`, `stats` (`[{ v, l }, ...]`).
- Markup embeds `<script type="application/json" class="ph-cases-data">__PH_CASES_JSON__</script>`.
- `render.php` applies content-map first (placeholder still in file so byte offsets stay valid), then injects JSON, then passes prepared HTML to `nexora_ph_render_section`.
- Default packaged images use MEDIA default URLs under `assets/images/prospects/`. While still default, payload may emit short `assets/caseN.webp` so the PH `assets/` rewrite runs.

### JS (`nexora-ph-cases.js`)

- Delete the hardcoded `CASES` constant.
- Read JSON from the widget root (Pricing/Impact pattern).
- Keep `startCs` autoplay (6s, pause on section hover), prev/next, dots.
- Resolve image URLs with `NexoraPH.asset` when the value is still an `assets/...` path.
- Clamp active index when slide count changes.
- Bind CTA: `href` / optional `target` / `rel` from the active slide; button label from `buttonText`.

### Markup

Keep structure. Wire CTA like Pricing:

- `{{ cs.buttonText }}`
- `href="{{ cs.buttonUrl }}" target="{{ cs.buttonTarget }}" rel="{{ cs.buttonRel }}"`
- External http(s) links get `target="_blank"` and `rel="noopener"`; in-page `#` links do not

Image / title / summary / facts / stats stay `{{ cs.* }}` holes.

## Accessibility

- Prev/next aria-labels stay editable via mapped controls.
- Dot buttons keep generated labels (`Show case study N`).
- Image uses per-slide alt text.

## Editor

- Repeater edits re-render the widget preview.
- Carousel interaction only updates client state.
- Repeater `title_field` uses Title.

## Out of scope

- Changing Impact / Framework / Pricing patterns beyond reuse
- Nested Elementor repeater for stats (textarea lines only)
- Style-tab redesign of the carousel chrome
- Making “Featured case” / fact row labels per slide
