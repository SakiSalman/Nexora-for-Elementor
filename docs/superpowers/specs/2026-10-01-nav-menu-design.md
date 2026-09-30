# Nav menu sidebar

The PH Nav widget keeps the current bar, the Services mega panel, and the phone menu. The center links become one editable list. The logo and the Book A Call button stay outside that list.

Elementor cannot put a repeater inside a repeater, and an icon picker cannot live in a line of text. Child rows therefore sit in the same list, after the menu item they belong to.

## Sidebar

```text
Logo
├── Image
├── Alt
├── Link
└── Accessible label

Menu                         repeater, row title is Label
└── each row
    ├── Kind                 Menu item | Dropdown item | Service card | Other service
    ├── Label
    └── fields for that kind only

Button
├── Text
└── Link
```

A Menu item has Type: Link, Dropdown, or Mega menu. One select, so Mega and Dropdown cannot both be on.

- Link shows Link and Current. Current adds the existing highlighted pill (`.navlink.on`) and the stronger Home label (`#0B1620`, weight 600). More than one row may be current, and each one is highlighted. Home starts current.
- Dropdown has no extra fields on that row. Its items are the Dropdown item rows that follow it.
- Mega menu shows View all text, View all link, Other services label, and the call-to-action title, description, and link.

A Dropdown item shows Link and Description. A Service card shows Icon, Description, and Link. An Other service shows Icon and Link.

Switching Kind or Type hides the other fields and keeps the saved values. The repeater `title_field` is the label.

The nav accessible name stays "Main". The phone button accessible name stays "Open menu". Those two strings are not controls.

## Grouping

Walk the rows from top to bottom.

- A Menu item starts a group.
- Later rows that are not Menu items belong to that group, until the next Menu item.
- Rows before the first Menu item are ignored.
- A Dropdown item renders only when its group type is Dropdown.
- A Service card or Other service renders only when its group type is Mega menu.
- A child of the wrong type stays saved and is not shown.
- Reordering the list reorders both the desktop bar and the phone menu.

## Icons

The icon control is Elementor's icon picker.

- A seeded card or other-service row with an empty icon renders the original SVG stored with that row.
- Clearing a picked icon on a seeded row returns that original SVG.
- A new card or other-service row with an empty icon uses one shared fallback icon. That fallback is a single SVG already used in the nav, not a new graphic.
- A picked icon replaces the original SVG.
- Dropdown items have no icon.

## Links

An `http` or `https` link opens in a new tab with `rel="noopener"`. A hash or any other link stays in the same tab. An empty link is `#`.

## Rendering

Desktop order is logo, menu groups, button.

- A Link is a text item with no chevron.
- A Dropdown or Mega menu item is a button with the existing chevron.
- Only one panel is open. Hover or click opens that item's panel. Leaving the nav, or pressing Escape, closes it. Choosing another item closes the previous panel.
- A link inside a panel closes the panel. A link in the phone menu closes the phone menu.

The mega panel uses the existing Services layout.

- Service cards fill the grid in order. The first service card in that group gets the highlighted card style.
- Other services sit in the footer row under the group's label.
- Hide View all when its text is empty. Hide the call-to-action card when its title is empty. Hide the other-services block when the group has no other-service rows. An empty label still shows the other-service links, with no heading.
- A mega item with no cards still opens the panel and shows whatever View all, other services, and call-to-action remain.

The dropdown is a small panel under that item. It is not a second mega panel. It uses the nav's existing light glass panel. Each row is the label, the description, and the link. No icons. An empty description leaves that line out. A dropdown with no visible items still opens an empty panel.

The phone menu uses the same groups.

- A Link is a row. Every plain phone link uses the existing row with the trailing arrow, including Home and any new link.
- A Dropdown or Mega menu item expands under its label.
- A dropdown row shows the label and description, and no icon.
- A mega menu shows each card as icon plus title, then the other-services label and links. Card descriptions stay on the desktop panel.
- The phone menu ends with the same Book A Call button.

An empty menu list leaves the logo, the button, and an empty center. The phone menu then contains only the button.

## Defaults

Logo: `assets/logo-color.png`, alt "Prospects Hive", link `#`, accessible label "Prospects Hive home".

Button: "Book A Call", link `https://tidycal.com/prospectshive/discovery-call`.

Menu, in order:

1. Home. Link `#`. Current.
2. Services. Mega menu. View all text "View all services", link `#services`. Other services label "Other services". Call to action title "Book a Call", description "See how Prospects Hive's outbound system can fill your pipeline.", link `https://tidycal.com/prospectshive/discovery-call`.
3. Nine service cards, all linking to `#services`. The first is highlighted. Each keeps its current icon. In order:
   - Cold Email Outreach. Secure meetings with decision-makers and build a stable pipeline.
   - LinkedIn Lead Generation. Relationship-first LinkedIn outreach that starts real sales conversations.
   - Email Marketing. Nurture campaigns that break through the noise and book calls.
   - Account-Based Marketing (ABM). Personal, research-led campaigns that win high-value accounts.
   - CRM Setup & Management. Clean pipelines, routing and reporting your sales team can trust.
   - B2B Content Marketing. Content that builds authority and warms up accounts before outreach.
   - AI Workflow Automation. Automate research, follow-ups and reporting to save hours every week.
   - Outbound Marketing. Email, LinkedIn and follow-ups run as one coordinated campaign.
   - B2B Lead Generation. Hand-picked, verified lists of sales-ready prospects.
4. Three other services, all linking to `#services`: Attio CRM, HubSpot CRM, Investment Management. Each keeps its current icon.
5. Case Studies. Link `#cases`.
6. Resources. Link `#insights`. No chevron.
7. Contact. Link `#contact`.

Unchanged settings reproduce this menu. Resources no longer shows a chevron, because a chevron means Dropdown or Mega menu.

## Scope

This is the PH Nav widget only. No color, typography, spacing, or animation controls. No second menu for the phone. Other widgets stay as they are.

Two copies of the widget on one page do not share the open panel or the phone menu.

## Checks

- Unchanged settings show the current logo, five center items, Services panel, and Book A Call button. Resources has no chevron.
- Adding a Link adds a desktop item and a phone row.
- A Dropdown item under a Dropdown menu item appears in the dropdown and the phone submenu. The same row under a Mega menu item does not appear.
- A service card under a Mega menu item appears in the grid. The first card is highlighted. A card under a Dropdown does not appear.
- Picking an icon replaces that row's icon. Clearing it restores the seeded SVG, or the fallback on a new row.
- Empty View all text, an empty call-to-action title, and an empty other-services list omit those blocks.
- Opening one panel closes another. Leaving the nav and pressing Escape close the open panel.
- Reordering rows reorders the desktop bar and the phone menu.
