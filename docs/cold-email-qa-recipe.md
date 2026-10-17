# Cold Email widgets — QA page recipe

Used by every phase. The parity gate (`bin/ce-parity.mjs`) and the visual QA both run against one private Elementor page.

## 1. Serve the design template

```powershell
cd "dist\Cold Email (1)"
python -m http.server 8099
```

Open `http://localhost:8099/ColdEmail.dc.html` — the reference rendering, side by side with WordPress.

## 2. QA page in WordPress

1. Create a private page **"Cold Email — QA"**.
2. Edit with Elementor. Page layout: **Elementor Full Width**, hide title.
3. Drop `ele-ph-nav` first, then the 9 CE widgets in template order:
   `CE Hero`, `CE Problem`, `CE Solution`, `CE Services`, `CE Pipeline`, `CE Process`, `CE Outlook`,
   `CE Why`, `CE Signal`.
4. Leave every widget on its defaults - defaults must already be byte-identical to the design.

## 3. Parity gate

```powershell
node bin/ce-parity.mjs "http://testing.local/cold-email-qa/"            # whole page
node bin/ce-parity.mjs "http://testing.local/cold-email-qa/" --widget ce-hero
node bin/ce-parity.mjs saved-page.html --verbose                        # writes actual/expected pairs to dist/plans/
```

Every widget prints `OK`; any `FAIL` prints the first differing bytes of both sides.
From phase 3 on, `var(--ph-…, fallback)` is reversed automatically before comparing.

## 4. Visual QA

Breakpoints: 1440, 1280, 1025, 1024, 768, 390 px. Check:

- Sticky stacks: `ce-solution` (`.ss-card` scale-under), `ce-process` (`.stk-card` offsets).
- `ce-pipeline`: tab pick, 7 s auto-advance, progress line, pause on hover.
- Marquees (`ce-hero` logos) and floats keep moving; everything with
  `.ph-off` pauses when scrolled off-screen and resumes on return.
- Two copies of one widget on the page do not interfere (tabs, ids, animations).
- Reduced motion (DevTools → Emulate `prefers-reduced-motion: reduce`): animations stop.
- Editor: add / edit / reorder a repeater row, preview re-renders, JS still binds.

## 5. Disabled-widget check

Nexora → Widgets: disable one CE widget with the QA page containing it → the page keeps its
saved content, the widget prints the disabled placeholder in the editor and nothing on the front end.
