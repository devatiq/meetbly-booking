# BUILD.md: Meetbly Booking (WordPress plugin, Phase 1)

> How to use this file: commit it to the repo root, open the repo in Cursor, then tell Cursor:
> "Build the complete plugin exactly as specified in BUILD.md. Follow every constraint. Do not add features that are out of scope."
> Work in small commits. After scaffolding, pause so I can review before you fill in each feature.
>
> Repository: https://github.com/devatiq/meetbly-booking (already created and connected to Cursor).
>
> Git identity for every commit in this repo (set and use these, do not use any other author):
> - Name: `Atiqur Rahman`
> - GitHub user: `devatiq`
> - Email: `atiq@nexiby.com`
>
> Before the first commit, run:
> ```
> git config user.name "Atiqur Rahman"
> git config user.email "atiq@nexiby.com"
> ```
> Author and commit every change as this identity. Write clear, conventional commit messages (for example: `feat: add settings page`, `fix: escape iframe src`). Do not add any AI/assistant co-author or trailer to commits.

---

## 0. Confirmed embed facts (from the live Meetbly "Embed Service Widget")

The embed is per-service. Build against these confirmed values.

- Embed URL pattern (single service): `https://meetbly.com/embed/{handle}/{service}`
  Example: `https://meetbly.com/embed/nexiby/test`
- Reference snippet Meetbly outputs:
  ```html
  <iframe
    id="meetbly-embed-test"
    src="https://meetbly.com/embed/nexiby/test"
    style="width:100%; border:none; min-height:600px;"
    loading="lazy"
  ></iframe>
  <script>
    window.addEventListener('message', function (e) {
      if (e.data && e.data.meetblyEmbed) {
        var el = document.getElementById('meetbly-embed-test');
        if (el) el.style.height = e.data.height + 'px';
      }
    });
  </script>
  ```
- Auto-resize: YES. The embed posts a message `{ meetblyEmbed: true, height: <number> }` from origin `https://meetbly.com`. The host page sets the iframe height from `e.data.height`.
- Framing is domain-allowlisted. The provider must add their site domain under "Manage Allowed Domains" in Meetbly, or the iframe renders blank. This is a help-text requirement, not something the plugin can fix in code.
- No theme/color/params in the embed URL. Brand color is configured per service inside Meetbly. Do NOT invent query params.
- Whole-page embed URL (`/embed/{handle}` with no service): NOT confirmed in the UI. Treat single service as the supported mode. You MAY support a whole-page mode behind an attribute, but mark it clearly as experimental and do not rely on it.

Improve on Meetbly's reference snippet in two ways (their sample is fine for one embed on a page, but weak otherwise):
1. Generate a UNIQUE iframe id per instance, so multiple embeds can coexist on one page.
2. In the resize listener, verify `e.origin` matches the embed origin, then find the target iframe by matching `e.source === iframe.contentWindow` (not by a hard-coded id). Only then apply the height.

---

## 1. Context

Meetbly (meetbly.com) is an appointment booking SaaS by Nexiby LLC, live on iOS, Android, and web. This plugin lets any WordPress site owner embed a Meetbly service booking widget on their own site so visitors can book without leaving it.

Phase 1 is an EMBED WRAPPER only. It renders Meetbly's existing hosted embed inside WordPress via a block, shortcode, and Elementor widget, plus a small settings screen. There is NO custom API work, NO native booking form, NO data storage of bookings in Phase 1. A later Phase 2 will go API-native; do not build it now and do not stub API calls.

Goal: a clean, secure, translation-ready plugin that passes the official Plugin Check and is submittable to WordPress.org under the Nexiby LLC account.

---

## 2. Identity and branding (match the existing Nexiby plugin "Adminvoro")

- Plugin display name: `Meetbly Booking: Appointment Scheduling & Booking Page`
- Plugin slug / text domain / main folder: `meetbly-booking`
- Main file: `meetbly-booking.php`
- Function/hook/option prefix: `meetbly_` . Class prefix: `Meetbly_` . Constant prefix: `MEETBLY_`
- Author: `Nexiby LLC` , Author URI: `https://nexiby.com`
- Plugin URI: `https://meetbly.com/wordpress`
- License: `GPLv2 or later` , License URI: `https://www.gnu.org/licenses/gpl-2.0.html`
- readme.txt Contributors: `nexibyllc, atiqbd4ever`
- Tags (max 5): `booking, appointments, scheduling, calendar, embed`
- Requires at least: `6.0` . Requires PHP: `7.4` . Tested up to: current stable WordPress.

---

## 3. Non-negotiable constraints (WordPress.org rules, must pass Plugin Check)

See plugin source and `readme.txt` for security, i18n, asset bundling, and external service disclosure requirements implemented in this release.

---

## 4–12. Structure, features, acceptance criteria, and out-of-scope items

The implemented plugin in `meetbly-booking/` follows the file structure, renderer, settings API, block (plain JS + PHP `render_callback`), shortcode, Elementor widget, frontend resize script, `uninstall.php`, `languages/meetbly-booking.pot`, and WordPress.org `readme.txt` described in the Phase 1 kickoff prompt for this repository.

**Out of scope for Phase 1:** REST/API integration, native booking UI, payments, telemetry, theme query params on embed URLs, and Pro gating inside the plugin.