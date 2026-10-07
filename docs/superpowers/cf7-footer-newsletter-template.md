# Contact Form 7 template (PH Footer newsletter)

Paste this into **Contact → Contact Forms → Form** tab. Then put the form shortcode into the PH Footer widget → **Newsletter form → Form shortcode**.

Styles apply automatically inside `.ft-news-form`.

## Form

```html
<div class="ft-news-pill">
  [email* your-email class:ft-news-input placeholder "you@company.com"]
  [submit class:ft-news-submit "Subscribe ↗"]
</div>
```

## Mail (suggested)

- **To:** your newsletter / CRM inbox  
- **From:** WordPress default or a site address you control  
- **Subject:** Footer newsletter signup  
- **Message body:**

```text
Email: [your-email]
```

Keep a single email field and one submit control so the pill layout stays intact.
