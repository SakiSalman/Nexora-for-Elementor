# Nav from a WordPress menu

This replaces the repeater menu in `2026-10-01-nav-menu-design.md`. The PH Nav widget reads its center links from a WordPress menu. The logo, the Book A Call button, and the mega menu content stay in the widget. One top-level item can be the mega menu. The widget has one set of mega fields, and that set belongs to the checked item.

## WordPress menu

In Appearance → Menus, each item gets a Mega menu checkbox.

Saving a menu keeps one checked item. When the save includes items that were not checked before, the last of those new items in menu order stays checked and every other item in the same menu is cleared. When several items are checked and none of them is new, the last checked item in menu order stays checked and the others are cleared.

The checkbox applies to a top-level item. A checked child does not open a mega panel. Items nested under a child are left out of the nav.

A child item's Description is the line under its label in the dropdown and in the phone submenu. An empty description leaves that line out. Description is the menu item's own Description field. It is visible when Description is enabled under Screen Options.

The item title and URL come from the menu item.

## Widget

```text
Logo
├── Image
├── Alt
├── Link
└── Accessible label

Menu
└── Menu                 WordPress menu

Mega menu
├── View all text
├── View all link
├── Service cards        Label, Description, Link, Icon
├── Other services label
├── Other services       Label, Link, Icon
└── Call to action       Title, Description, Link

Button
├── Text
└── Link
```

The mega fields belong to the checked top-level item in the selected menu. With no item checked, those fields are unused, and a top-level item with children opens the small dropdown. With no menu selected, or with a selected menu that has been deleted, the bar shows the logo, an empty center, and the button. The phone menu then contains only the button.

Two copies of the widget may use different menus. Copies that use the same menu share that menu's checkbox. Each copy keeps its own mega content.

The nav accessible name stays "Main". The phone button accessible name stays "Open menu". Those two strings are not controls.

## Icons

The icon control is Elementor's icon picker.

- A seeded card or other-service row with an empty icon renders the original SVG stored with that row.
- Clearing a picked icon on a seeded row returns that original SVG.
- A new card or other-service row with an empty icon uses one shared fallback icon. That fallback is a single SVG already used in the nav.
- A picked icon replaces the original SVG.

## Links

An `http` or `https` link opens in a new tab with `rel="noopener"`. Any other link stays in the same tab. An empty link is `#`.

## Rendering

Desktop order is logo, top-level menu items, button. Items follow the menu order.

- A top-level item with the Mega menu checkbox is a button with the existing chevron. It opens the mega panel. This stays true when that item has no children. Its child items are left out.
- A top-level item with children, and without that checkbox, is a button with the existing chevron. It opens the small dropdown.
- Any other top-level item is a link with no chevron.

Only one panel is open. Hover or click opens that item's panel. Leaving the nav, or pressing Escape, closes it. Choosing another item closes the previous panel. A link inside a panel closes the panel. A link in the phone menu closes the phone menu.

The mega panel uses the existing Services layout.

- Service cards fill the grid in order. The first service card gets the highlighted card style.
- Other services sit in the footer row under the label.
- Hide View all when its text is empty. Hide the call-to-action card when its title is empty. Hide the other-services block when that list is empty. An empty label still shows the other-service links, with no heading.
- A mega item with no cards still opens the panel and shows whatever View all, other services, and call-to-action remain.

The dropdown is the small glass panel under that item. Each direct child is the label, the Description, and the link. An empty description leaves that line out. Items nested under a child are left out. A dropdown with no visible children still opens an empty panel.

WordPress marks the current item. A top-level item with `current-menu-item`, `current-menu-parent`, or `current-menu-ancestor` uses the highlighted label (`.navlink.on`, color `#0B1620`, weight 600). A hash link stays plain.

The phone menu uses the same items.

- A link is a row with the trailing arrow.
- A dropdown or mega item expands under its label.
- A dropdown row shows the label and description, and no icon.
- A mega menu shows each card as icon plus title, then the other-services label and links. Card descriptions stay on the desktop panel.
- The phone menu ends with the same Book A Call button.

## Defaults

Logo: `assets/logo-color.png`, alt "Prospects Hive", link `#`, accessible label "Prospects Hive home".

Button: "Book A Call", link `https://tidycal.com/prospectshive/discovery-call`.

The Menu dropdown starts empty. The widget does not create a WordPress menu.

Mega menu content starts as the current Services panel:

- View all text "View all services", link `#services`.
- Other services label "Other services".
- Call to action title "Book a Call", description "See how Prospects Hive's outbound system can fill your pipeline.", link `https://tidycal.com/prospectshive/discovery-call`.
- Nine service cards, all linking to `#services`. The first is highlighted. Each keeps its current icon. In order:
  - Cold Email Outreach. Secure meetings with decision-makers and build a stable pipeline.
  - LinkedIn Lead Generation. Relationship-first LinkedIn outreach that starts real sales conversations.
  - Email Marketing. Nurture campaigns that break through the noise and book calls.
  - Account-Based Marketing (ABM). Personal, research-led campaigns that win high-value accounts.
  - CRM Setup & Management. Clean pipelines, routing and reporting your sales team can trust.
  - B2B Content Marketing. Content that builds authority and warms up accounts before outreach.
  - AI Workflow Automation. Automate research, follow-ups and reporting to save hours every week.
  - Outbound Marketing. Email, LinkedIn and follow-ups run as one coordinated campaign.
  - B2B Lead Generation. Hand-picked, verified lists of sales-ready prospects.
- Three other services, all linking to `#services`: Attio CRM, HubSpot CRM, Investment Management. Each keeps its current icon.

## Scope

This is the PH Nav widget only. No color, typography, spacing, or animation controls. No second menu for the phone. Other widgets stay as they are.

Two copies of the widget on one page do not share the open panel or the phone menu.

## Checks

- With no menu selected, the bar shows the logo, an empty center, and Book A Call. The phone menu shows only that button.
- A selected menu shows its top-level items in menu order, on desktop and in the phone menu.
- A top-level item with no children and no Mega menu checkbox is a link with no chevron.
- A top-level item with children and no checkbox opens the small dropdown. Each child shows its label and Description. An empty Description omits that line. An item nested under a child does not appear.
- The checked top-level item opens the mega panel from the widget. Its children do not appear. A checked child does not open a mega panel.
- Saving a second Mega menu checkbox clears the first.
- The first service card is highlighted. Empty View all text, an empty call-to-action title, and an empty other-services list omit those blocks.
- Picking an icon replaces that row's icon. Clearing it restores the seeded SVG, or the fallback on a new row.
- The top-level item WordPress marks as current uses the highlighted label. A hash link stays plain.
- Opening one panel closes another. Leaving the nav and pressing Escape close the open panel.
- Two copies using one menu share the checkbox and keep separate mega content, open panels, and phone menus.
