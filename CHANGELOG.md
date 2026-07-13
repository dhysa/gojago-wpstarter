# Changelog

All notable changes to the Gojago WordPress Starter are documented here.

This repository is a development starter factory. Client repositories should usually receive only the exported theme from `wp-content/themes/gojago-starter`.

## v1.1.2 - 2026-07-13

- Added `GOJAGO_LOGIN_SLUG=managesite` and a must-use login hardening plugin.
- Made `/managesite` the unauthenticated login/admin entrypoint.
- Blocked unauthenticated `/wp-login.php` and routed unauthenticated `/wp-admin/` through `/managesite`.
- Updated local setup to mount `wp-content/mu-plugins`, update WordPress core, remove Hello Dolly/Akismet, install required plugins, and seed a clean starter homepage.
- Added admin navigation cleanup that hides `Edit Site` and exposes `Customize CSS`.
- Added license-key placeholders for ACF Pro and Gravity Forms Pro setup flows.

## v1.1.1 - 2026-07-10

- Added All-in-One WP Migration automatic install support.
- Added All-in-One WP Migration Unlimited Extension ZIP detection.
- Raised local upload/runtime limits for migration workflows.
- Bumped shared starter/theme version metadata to `1.1.1`.

## v1.0.0 - 2026-07-10

- Established the starter as a Docker-based local WordPress development base.
- Added the `gojago-starter` Gutenberg-first block theme.
- Added readiness hooks for ACF Pro, Gravity Forms Pro, SEO, analytics, editable patterns, and PM/QA-friendly editing.
- Added starter version tracking with `STARTER_VERSION`.
