=== Kjeks Scripting ===
Contributors: PerS
Tags: consent, cookies, gdpr, privacy, scripts
Requires at least: 6.8
Tested up to: 6.8
Requires PHP: 8.3
Stable tag: 0.1.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Consent-gate any enqueued script by handle for Kjeks.

== Description ==

Assign a registered script handle to a Kjeks consent category, and the script
stays inert until the visitor consents to that category. You gate scripts by
handle from an admin screen — no changes to the scripts themselves.

On the front end each mapped handle is registered with the core Kjeks blocking
registry, which rewrites the tag to a consent-aware, inert form until the
visitor consents.

Requires the Kjeks plugin, which provides the consent UI, the script gate, and
the shared add-on base classes.

== Installation ==

1. Install and activate the Kjeks plugin.
2. Install and activate Kjeks Scripting.
3. On Multisite, go to Network Admin > Settings > Kjeks Scripting. On single
   site, go to Settings > Kjeks Scripting.
4. Map script handles to consent categories and save.

== Frequently Asked Questions ==

= How do I find a script handle? =

Handles are the first argument to wp_enqueue_script()/wp_register_script(). View
source and look at the id attribute of the script tag; WordPress appends "-js",
so id="google-analytics-js" means the handle is "google-analytics".

== Changelog ==

= 0.1.0 =
* Initial release.
