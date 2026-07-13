# Changelog

All notable changes to the Gojago WordPress Starter are documented here.

This repository is a development starter factory. Client repositories should usually receive only the exported theme from `wp-content/themes/gojago-starter`.

## v1.1.7 - 2026-07-13

- Fixed custom login flow so successful `/managesite` logins land in the WordPress dashboard instead of the login route or a not-found page.
- Redirected authenticated visits to `/managesite` into `wp-admin`.
- Updated docs to present `/managesite` as the admin login entrypoint.

## v1.1.6 - 2026-07-13

- Replaced multi-page starter seed content with a minimal Home page and one disposable Starter Features inventory page.
- Updated native WordPress Primary/Footer menu seeding to use the Home and Starter Features pages instead of fake client pages.
- Documented that the Starter Features page is temporary and safe to delete after project setup.

## v1.1.5 - 2026-07-13

- Updated ZIP-based plugin installs to select the newest matching package instead of the first match.
- Made local setup and wp-admin install helpers overwrite existing ZIP-installed plugins, then ask WordPress to update them to the latest available version from their official updater before final activation.
- Documented the rerun workflow for replacing ACF Pro, Gravity Forms Pro, WP Cerber, or other local ZIP plugins with newer ZIP files.

## v1.1.4 - 2026-07-13

- Clarified that `resources/views` is the only source location for block templates, template parts, and patterns.
- Removed references to duplicate root-level `templates/`, `parts/`, and `patterns/` theme folders.

## v1.1.3 - 2026-07-13

- Removed All-in-One WP Migration Unlimited Extension from starter requirements, automatic setup, and dashboard reminder checks.
- Kept the core All-in-One WP Migration plugin as the required backup/migration plugin.

## v1.1.2 - 2026-07-13

- Added `GOJAGO_LOGIN_SLUG=managesite` and a must-use login hardening plugin.
- Made `/managesite` the unauthenticated login/admin entrypoint.
- Blocked unauthenticated `/wp-login.php` and routed unauthenticated `/wp-admin/` through `/managesite`.
- Updated local setup to mount `wp-content/mu-plugins`, update WordPress core, remove Hello Dolly/Akismet, install required plugins, and seed a clean starter homepage.
- Added admin navigation cleanup that hides `Edit Site` and exposes `Customize CSS`.
- Added license-key placeholders for ACF Pro and Gravity Forms Pro setup flows.

## v1.1.1 - 2026-07-10

- Added All-in-One WP Migration automatic install support.
- Raised local upload/runtime limits for migration workflows.
- Bumped shared starter/theme version metadata to `1.1.1`.

## v1.0.0 - 2026-07-10

- Established the starter as a Docker-based local WordPress development base.
- Added the `gojago-starter` Gutenberg-first block theme.
- Added readiness hooks for ACF Pro, Gravity Forms Pro, SEO, analytics, editable patterns, and PM/QA-friendly editing.
- Added starter version tracking with `STARTER_VERSION`.
