# PH Video player

The PH Video widget keeps its current header, lead copy, benefit pills, and branded frame. The center mock (“Your all-in-one growth engine” + fake play UI) becomes a real video player with a poster.

Source support: **self-hosted MP4**, **YouTube**, and **Vimeo**, plus a **poster** image for all three.

## Sidebar

```text
Video player                    new Content section (before mapped copy controls)
├── Source                      Self Hosted | YouTube | Vimeo
├── Video                       MEDIA (MP4), Self Hosted only
├── URL                         TEXT / URL, YouTube or Vimeo only
├── Poster                      MEDIA image, all sources
└── Play label                  TEXT, default "Play video"
```

Existing mapped controls (eyebrow, heading, lead, benefits, decorative logo text, etc.) stay as they are. No change to Schema.

Switching Source hides the unused Video / URL field and keeps saved values.

## Defaults

- Source: Self Hosted
- Video: empty
- URL: empty
- Poster: empty
- Play label: `Play video`

When Video (self-hosted) or URL (YouTube/Vimeo) is empty, the section renders the **current static mock** so the page never shows a broken player. Poster alone does not start a player; it only replaces the mock backdrop once a playable source exists.

## Rendering

The outer gradient frame and `.vid-box` aspect ratio stay.

### Idle (has playable source)

1. Show the video surface (self-hosted `<video>` with `poster`, or a poster/`div` shell for YouTube/Vimeo).
2. Overlay the existing orange play button (`.pulse.sheen`) centered.
3. Hide the fake progress bar (`0:00 / 1:45`, `.vbar`).
4. Keep a poster cover (or video first frame with poster attribute) over the self-hosted video until play so the branded button stays the primary control.
5. Decorative mock copy (logo, “Your all-in-one…”, subtitle) is **hidden** when a playable source is set. Those mapped text fields remain editable in the sidebar for the empty-source fallback only.

### Play

- **Self Hosted:** hide overlay; call `video.play()`; show native `controls` after play starts (or immediately on play).
- **YouTube / Vimeo:** replace the shell with an iframe embed (`autoplay=1`, privacy-friendly YouTube `youtube-nocookie.com` when possible). Parse IDs from common URL shapes (`watch?v=`, `youtu.be/`, `vimeo.com/`).

### Pause / end

No custom chrome required beyond native controls for self-hosted. Remote embeds use their own UI after play.

## Markup / assets

- Add a small player root inside `.vid-box`, e.g. `[data-ph-video]` with `data-source`, `data-url` / media URL, and poster URL as needed.
- New JS handle `nexora-ph-video` (depends on existing PH runtime if present, otherwise plain DOM ready + Elementor frontend hooks).
- CSS: overlay positioning for poster + play button; iframe/video fill `.vid-box`; keep mobile aspect-ratio rules.

## Accessibility

- Play control is a `<button>` with `aria-label` from Play label.
- After play, focus moves into the player when practical (self-hosted video element).
- Empty-source mock keeps the existing play button for visual parity; it does nothing on click and uses the Play label as `aria-label`.

## Editor

- Controls update the preview in Elementor.
- Changing Source, Video, URL, or Poster re-renders the player shell; playing state resets on re-render.

## Out of scope

- Custom scrubber / HD badge / fake timeline (removed when a real source is set)
- Playlist, captions UI, or multiple videos
- Changing header / benefit mapped content structure
