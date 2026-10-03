# PH Insights blog posts

Article cards load the **latest 3 published WordPress posts** (all posts). No manual Articles repeater.

## Card data

| UI | Source |
| --- | --- |
| Image | Featured image, else packaged placeholder |
| Chip | First category name (omit if none) |
| Read time | `ceil(word_count / 200)` → `N min read` |
| Title | Post title |
| Excerpt | Post excerpt / trimmed content |
| Link | Permalink; label stays “Read article” (shared control optional later) |

Header + View all CTA stay content-mapped.
