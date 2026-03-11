Story Engine — Interactive Storytelling Plugin

A reusable WordPress plugin for creating interactive timeline-based stories with JSON-driven content.

Features
- Interactive storytelling with timeline navigation
- JSON-based demo datasets for easy content management
- Editorial timeline layouts with responsive design
- Reusable shell architecture for multiple story types
- WordPress shortcode integration with flexible parameters
- SEO-friendly fallback content
- Theme-based styling system

Installation

1) Compress this folder as ZIP.
2) In WordPress: Plugins -> Add New -> Upload Plugin -> Activate.
3) Create a page and add the shortcode:

Usage

Primary shortcode (recommended):
[story_engine demo="heroes" theme="editorial" layout="fullbleed"]

Legacy shortcode (compatibility):
[heroes_paz]

Available Parameters

- demo: Dataset identifier (heroes, timeline-demo, profiles-demo)
- layout: Layout mode (fullbleed, contained)
- theme: Visual theme (editorial)
- slug: Optional slug for customization
- data_url: Override data source URL (advanced use)

Available Datasets

- heroes: Original "Héroes de la Paz" investigation
- timeline-demo: Democratic transition timeline example
- profiles-demo: Change makers profiles example

Data Management

Edit JSON files in /data/ directory:
- heroes.json: Main dataset
- timeline-demo.json: Timeline example
- profiles-demo.json: Profiles example

Data Structure Rules

- Keep consistent: months[].id and events[].month
- events[].month must exist in months[].id
- events[].id must be unique and stable (used for anchors and deep-link)
- All content should be plain text (HTML requires sanitization)
- Images should use full URLs (WordPress Media Library recommended)

Templates

- JSON files include sample data for visual testing.
- Datasets fallback to heroes.json if requested file doesn't exist.

Technical Notes

- CSS/JS only load when shortcode is used.
- SEO fallback renders as indexable HTML and hides when JS hydrates the UI.
- Theme system supports multiple visual themes.
- Layout system supports both fullbleed and contained modes.

Analytics (optional)

The frontend emits CustomEvents in window:
- timeline_month_view (detail: { monthId })
- profile_open (detail: { eventId, monthId })
- source_click (detail: { href })

Examples

[story_engine demo="heroes" theme="editorial" layout="fullbleed"]
[story_engine demo="timeline-demo" theme="editorial" layout="contained"]
[story_engine demo="profiles-demo" theme="editorial" layout="fullbleed"]
