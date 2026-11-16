=== Meetbly Booking: Appointment Scheduling & Booking Page ===
Contributors: nexibyllc, atiqbd4ever
Tags: booking, appointments, scheduling, calendar, embed
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Embed your Meetbly appointment booking widget on WordPress with a block, shortcode, or Elementor widget.

== Description ==

Meetbly Booking lets you add a Meetbly service booking embed to any WordPress page. Visitors see real-time availability and book without leaving your site.

#### What it does

* Embeds the official Meetbly hosted booking widget in an iframe.
* Resizes the iframe automatically when Meetbly sends a height message from meetbly.com.
* Stores only your plugin settings in WordPress (no booking data in Phase 1).

#### Block, shortcode, and Elementor

* **Block:** Search for "Meetbly Booking" in the block inserter (Embed category).
* **Shortcode:** `[meetbly handle="your-handle" service="your-service"]`
* **Elementor:** Use the "Meetbly Booking" widget when Elementor is active.

Handle and service are optional in the block and shortcode if you set defaults under **Meetbly Booking** in the admin menu.

#### Appearance options

* Default and per-embed min height, max width, and alignment (left, center, right).
* Optional consent note and privacy policy link below the widget.

#### Allow your domain in Meetbly

The embed only displays on domains you allow in Meetbly under **Manage Allowed Domains**. If your domain is not listed, the iframe may appear blank. Add your WordPress site domain in Meetbly before publishing the widget.

== External services ==

This plugin embeds a booking widget served by **Meetbly** (https://meetbly.com), operated by Nexiby LLC.

When a page containing the widget is loaded, the visitor's browser requests content from `https://meetbly.com` to display the booking interface. The included JavaScript listens for resize messages posted from `https://meetbly.com` so the iframe height updates. The plugin does not send booking data to your WordPress site and does not make other external connections.

* Meetbly website: https://meetbly.com
* Terms of Service: https://meetbly.com/terms
* Privacy Policy: https://meetbly.com/privacy

== Installation ==

1. Install and activate the plugin.
2. Open **Meetbly Booking** in the WordPress admin.
3. Enter your default Meetbly provider handle and service slug.
4. In Meetbly, add your WordPress site domain under **Manage Allowed Domains**.
5. Add the Meetbly Booking block, shortcode, or Elementor widget to a page.

== Frequently Asked Questions ==

= Where do I find my handle and service slug? =

Your handle is your Meetbly provider username (for example `nexiby`). The service slug is the public link slug for the service you want to embed (for example `test`).

= Why is the widget blank? =

Usually your domain is not listed under **Manage Allowed Domains** in Meetbly. Add your site URL and save in Meetbly, then reload the page.

= Can I place more than one widget on a page? =

Yes. Each embed uses a unique iframe id and the resize listener matches the correct iframe by message source.

= Does the plugin collect visitor data? =

The plugin stores only its own settings in WordPress. Booking data is handled by Meetbly inside the embed.

= Does it work with Elementor? =

Yes, when Elementor is installed and active.

= What happens on uninstall? =

By default, your settings are kept. You can enable **Delete all plugin data on uninstall** on the settings page to remove settings when the plugin is deleted.

== Changelog ==

= 1.0.0 =
* Initial release.