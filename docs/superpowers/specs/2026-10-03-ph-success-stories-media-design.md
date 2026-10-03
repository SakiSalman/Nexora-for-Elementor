# PH Success Stories media

Each Our success slide media block supports **image-only** or a **real video** (Self Hosted / YouTube / Vimeo). When a video source is set, the slide image is the **poster**. The play button shows only when a playable source exists.

## Sidebar (Our success repeater)

```text
Media source     Self Hosted | YouTube | Vimeo
Video            MEDIA video (Self Hosted)
Video URL        TEXT (YouTube / Vimeo)
Image / Poster   MEDIA image
Image alt        TEXT
Play label       TEXT (aria-label on play button)
```

Quote fields stay as they are. No video → image only, no play button.

## Defaults

- Source: Self Hosted, empty video → current testimonial image, no play control
- Poster default: `testimonial.webp`
- Play label default: `Play client video`

## Rendering

`render.php` applies the content map, then replaces each `[data-ph-story-media]` inner HTML from the repeater item (image-only or player shell with `data-ph-video`). Stories JS handles play → self `<video>` or YouTube/Vimeo iframe (same URL parsing as PH Video).
