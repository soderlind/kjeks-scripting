# Kjeks Scripting

Consent-gate any enqueued script by handle for [Kjeks](https://github.com/soderlind/kjeks).

Assign a registered script handle to a consent category, and the script stays
inert until the visitor consents to that category. No code changes to the
scripts themselves — you gate them by handle from an admin screen.

## Requirements

- WordPress 6.8+
- PHP 8.3+
- The [Kjeks](https://github.com/soderlind/kjeks) plugin (active) — it provides
  the consent UI, the script gate, and the shared `AddonKit` base classes.

## How it works

1. A script is registered/enqueued with a handle (by a theme, plugin, or your
   own code) — for example `google-analytics` or `hotjar`.
2. On the settings screen you map that handle to a consent category
   (Analytics, Marketing, or Preferences).
3. On the front end the add-on registers each mapped handle with the core Kjeks
   blocking registry. Core rewrites the tag to `type="text/plain"` with
   `data-kjeks-category`, so the browser does not run it until consent is given.

## Settings

- **Multisite:** Network Admin → Settings → Kjeks Scripting (network-wide).
- **Single site:** Settings → Kjeks Scripting.

Type or pick a script handle (the field suggests handles registered in the
admin), choose its category, and save. Add as many rows as you need; leave a row
blank to ignore it.

## Finding a handle

Handles are the first argument passed to `wp_enqueue_script()` /
`wp_register_script()`. Check the enqueuing plugin/theme, or view source and
look at the `id` attribute of the `<script>` tag (WordPress appends `-js`, e.g.
`id="google-analytics-js"` → handle `google-analytics`).

## Updates

Self-updates from GitHub releases. For a private repository, define a
`KJEKS_GITHUB_TOKEN` constant in `wp-config.php`.

## License

GPL-2.0-or-later. Copyright Per Søderlind.
