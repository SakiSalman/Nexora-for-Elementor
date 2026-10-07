# PH Footer newsletter CF7 shortcode

## Goal

Replace only the footer newsletter **email row + Subscribe button** with a Contact Form 7 shortcode, matching the PH Contact pattern. Keep the title and description as Elementor content-map controls.

## Behavior

- New Content section **Newsletter form** → textarea **Form shortcode**.
- Markup keeps label + description; the pill row becomes `__PH_FOOTER_NEWSLETTER__`.
- Outer shell is a `div` (not `<form>`) so CF7’s own form is not nested.
- Render: `do_shortcode()` when set; otherwise a short placeholder message.
- Remove content-map controls for email placeholder (`footer_f14`) and Subscribe button text (`footer_f15`).
- CSS styles CF7 email + submit inside `.ft-news-form` to the existing 44px pill + orange button look.
- Doc: CF7 form template for email + Subscribe submit.

## Out of scope

- Replacing title/description with CF7
- Mock input fallback when shortcode is empty
