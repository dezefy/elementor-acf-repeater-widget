# Elementor ACF Repeater Widget

A powerful Elementor widget that allows you to display Advanced Custom Fields (ACF) repeater fields with custom HTML templates.

Developed by [Dezefy](https://dezefy.com)

## Features

- Select any ACF repeater field from a dropdown
- Automatically lists all available subfields for easy reference
- Create custom HTML templates using shortcodes to display repeater data
- Comprehensive styling options in the Elementor interface
- Custom CSS section for advanced styling needs
- Full control over the structure and styling of your repeater field display

## Requirements

- WordPress 5.0 or higher
- Elementor 3.0.0 or higher
- Advanced Custom Fields 5.0.0 or higher (ACF Pro required for repeater fields)

## Installation

1. Upload the `elementor-acf-repeater-widget` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The 'ACF Repeater' widget will be available in the Elementor editor under the 'General' category

## Usage

### Basic Setup

1. Create an ACF repeater field in your WordPress site
2. Add subfields to your repeater field
3. Add the 'ACF Repeater' widget to your Elementor page or template
4. Select your repeater field from the dropdown
5. The HTML template editor will automatically populate with available subfields
6. Customize the HTML template using the shortcodes
7. Style the output using the Style tab options

### Shortcode Format

Use shortcodes in your HTML template to display the repeater field data:

```
[acfr_subfield_name]
```

For example, if your subfield is named "title":

```
[acfr_title]
```

### Example Use Cases

#### Team Member Showcase

If you have an ACF repeater field for "Team Members" with subfields:
- `name` (text)
- `position` (text)
- `bio` (textarea)
- `photo` (image)

You could create a template like this:

```html
<div class="acf-repeater-item">
  <div class="member-photo">
    <img src="[acfr_photo]" alt="[acfr_name]">
  </div>
  <div class="member-info">
    <h3 class="member-name">[acfr_name]</h3>
    <div class="member-position">[acfr_position]</div>
    <div class="member-bio">[acfr_bio]</div>
  </div>
</div>
```

#### Product Features

For a repeater field containing product features with subfields:
- `feature_title` (text)
- `feature_description` (textarea)
- `feature_icon` (image)

You could use:

```html
<div class="acf-repeater-item">
  <div class="feature">
    <div class="feature-icon">
      <img src="[acfr_feature_icon]" alt="[acfr_feature_title]">
    </div>
    <h4 class="feature-title">[acfr_feature_title]</h4>
    <p class="feature-description">[acfr_feature_description]</p>
  </div>
</div>
```

## Styling Options

The plugin provides comprehensive styling controls for:

1. **Wrapper Styling**
   - Padding, margin
   - Background color
   - Border options

2. **Item Styling**
   - Padding, margin
   - Background color
   - Border options

3. **Typography Styling**
   - Headings
   - Paragraphs
   - Custom CSS

4. **Button Styling**
   - Colors
   - Typography
   - Hover effects

## Advanced Usage

### Custom CSS

You can add custom CSS in the Style tab to further customize the appearance of your repeater fields.

### Handling Special Field Types

- **Image fields**: When using an image field, the shortcode `[acfr_image]` will output the image URL.
- **Link fields**: For link fields, the shortcode `[acfr_link]` will output the link URL.

## Support

If you encounter any issues or have questions about the Elementor ACF Repeater Widget, please contact the support team at [Dezefy](https://dezefy.com/contact).

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Developed by [Dezefy](https://dezefy.com) - Creating exceptional digital experiences.

## Changelog

### 1.0.0
- Initial release