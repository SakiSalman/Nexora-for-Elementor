# PH Contact CF7 panel

Right column becomes a **light glass panel** with an Elementor **Form shortcode** control for Contact Form 7. Form fields keep the same light field/pill look. Mock form content-map controls are removed.

## Sidebar

- Form shortcode (TEXTAREA), e.g. `[contact-form-7 id="123" title="Contact"]`
- Left column mapped controls unchanged

## Markup

Replace mock `<form class="glass">…` with:

```html
<div class="ct-form-panel reveal">__PH_CONTACT_FORM__</div>
```

Render injects `do_shortcode()` output (escaped empty → placeholder).

## CF7

Document the form HTML template + CSS under `.nexora-ph-contact .ct-form-panel`.
