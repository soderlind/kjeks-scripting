# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 0.2.0 - 2026-08-31

### Changed

- Show the scripting settings as a "Scripting" tab on the core Kjeks "Cookie Consent" screen instead of a separate submenu.

## 0.1.1 - 2026-08-29

### Added

- Clear button per handle row to reset a single mapping.

### Changed

- Align the handle input, category select, and Clear button on one row.

## 0.1.0 - 2026-08-29

### Added

- Initial release.
- Map any registered/enqueued script handle to a Kjeks consent category from a
  single Network Admin (Multisite) or Settings (single site) screen.
- Gated handles are rewritten to inert, consent-aware tags by the core Kjeks
  script gate and only run once the visitor consents.
- Built on the shared `Soderlind\Kjeks\AddonKit` base classes in core Kjeks.
