# ELE GTM Funnel Widget

Dynamic Elementor widget for the GTM strategy / sales funnel section.

## Usage

1. Open Elementor and search for **ELE GTM Funnel**.
2. Drag onto the page — defaults match the original static design.
3. Edit **Process Steps** and **Funnel Tiers** repeaters under Content.
4. Adjust typography, colors, geometry, and interaction under Style.

## Content controls

- **Layout** — funnel left/right, column spans, min-height, mobile stack, optional section heading
- **Process Steps** — flat repeater; set **Group Heading** to start a new group (Elementor has no nested repeaters)
- **Funnel Tiers** — value, labels, 3-stop gradients, node color/SVG
- **Interaction** — hover / click / both, default active, dim inactive, auto-rotate, keyboard

## Style controls

CSS custom properties on the widget wrapper avoid fighting Tailwind utilities:

- Wrapper background, padding, gap, min-height
- Group headings typography / color / tracking
- Step badge variants (accent / dark / blue)
- Step card gradients (normal + active), radius, padding, dim opacity
- Title, description, icon size/colors
- Funnel geometry (8 SVG unit sliders — changing them leaves the exact 4-tier preset)
- Funnel typography (value / label / sublabel)
- Funnel interaction scales, glow filter, max width

## Breakpoints

Tailwind `lg` is set to **1025px** so it does not collide with Elementor's default tablet max-width of 1024px.

## Build

```bash
cd wp-content/plugins/nexora-for-elementor
npm install
npm run build
```

Re-run `npm run build` after editing `assets/src/nexora-ele-gtm-funnel.css`.

## Multi-instance

SVG gradient / filter IDs are namespaced per Elementor widget ID. JS uses `AbortController` per root so editor re-renders and multiple instances stay isolated.

## Schema

Hidden `_schema_version` = `1` for future migrations.
