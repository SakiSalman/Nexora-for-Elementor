# Contact Form 7 template (PH Contact)

Paste this into **Contact → Contact Forms → Form** tab. Then put the form shortcode into the PH Contact widget → **Contact form → Form shortcode**.

Styles apply automatically inside the black panel (`.ct-form-panel`).

## Form

```html
<h3 class="ct-form-title">Your details</h3>

<div class="ct-form-grid">
  <div class="ct-form-field">
    <label class="ct-form-label" for="ct-name">Full name <span class="ct-req">*</span></label>
    [text* your-name id:ct-name class:field placeholder "Enter your full name"]
  </div>
  <div class="ct-form-field">
    <label class="ct-form-label" for="ct-url">Company URL</label>
    [url your-website id:ct-url class:field placeholder "Enter your website"]
  </div>
  <div class="ct-form-field">
    <label class="ct-form-label" for="ct-email">Email <span class="ct-req">*</span></label>
    [email* your-email id:ct-email class:field placeholder "Enter your work email"]
  </div>
  <div class="ct-form-field">
    <label class="ct-form-label" for="ct-wa">WhatsApp Number</label>
    [tel your-whatsapp id:ct-wa class:field placeholder "Enter your number"]
  </div>
</div>

<fieldset class="ct-budget">
  <legend>Project budget <span class="ct-req">*</span></legend>
  <div class="ct-budget-options">
    [radio project-budget use_label_element "Less than $5K" "$5K to $10K" "$10K to $20K" "$20K to $50K" "More than $50K"]
  </div>
</fieldset>

<div class="ct-form-field">
  <label class="ct-form-label" for="ct-msg">Project details</label>
  [textarea your-message id:ct-msg class:field placeholder "Tell us about your project and goals"]
</div>

[submit class:ct-form-submit "Let's Connect"]
```

## Mail (suggested)

- **To:** your inbox  
- **From:** `[your-name] <wordpress@your-domain.com>`  
- **Subject:** New contact from [your-name]  
- **Message body:**

```text
From: [your-name]
Email: [your-email]
Website: [your-website]
WhatsApp: [your-whatsapp]
Budget: [project-budget]

Message:
[your-message]
```

Make `project-budget` required in CF7 by wrapping the radio as needed, or use a required acceptance pattern your CF7 version supports. If your CF7 build requires a different required-radio syntax, keep the same labels and class structure so the panel CSS still matches.
