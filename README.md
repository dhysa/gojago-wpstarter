# Gojago WordPress Starter

Production-standard local WordPress starter for client projects. It uses Docker, a custom Gutenberg-first block theme, Tailwind, React/Gutenberg block tooling, ACF Pro readiness, Gravity Forms Pro readiness, SEO scaffolding, analytics placeholders, and a PM/QA-friendly editing workflow.

No `inspo/` folder was present when this starter was generated, so the theme uses the clean white standard baseline direction: neutral typography, white/canvas surfaces, subtle borders, and one restrained accent color.

The theme defaults page containers to full width. Individual sections use full-width top-level blocks, with constrained inner wrappers only where content readability or navigation layout needs it.

## Starter Factory Workflow

This repository is not intended to become every client project's GitHub repository. Use it as the base development environment for building and testing the Gojago WordPress theme locally.

Recommended flow:

1. Develop locally in this starter repo with Docker, WordPress, WP-CLI, and the full theme toolchain.
2. Keep reusable boilerplate improvements in this repo.
3. Export only the theme when a client project needs code.
4. Commit the exported theme into the client repository.

Export the theme:

```bash
bin/export-theme
```

Output:

- `dist/gojago-starter-vX.Y.Z/`
- `dist/gojago-starter-vX.Y.Z.zip`

The export command builds theme assets, copies `wp-content/themes/gojago-starter`, and excludes local-only folders such as `node_modules`.

If you already built the theme and want to skip rebuilding:

```bash
SKIP_BUILD=1 bin/export-theme
```

## Versioning

The starter version is tracked in `STARTER_VERSION`.

Use Git tags for stable starter releases:

```bash
git tag v1.0.0
git push origin v1.0.0
```

Use semantic versioning:

- `PATCH`, for fixes that should be safe for existing client themes, for example `v1.0.1`
- `MINOR`, for compatible starter/theme improvements, for example `v1.1.0`
- `MAJOR`, for breaking changes or large restructuring, for example `v2.0.0`

Before tagging a new version, bump the shared starter/theme version:

```bash
bin/bump-version 1.1.0
```

Then:

1. Update `CHANGELOG.md`.
2. Add file-level notes to `UPGRADE.md` when existing client themes may need manual changes.
3. Run `bin/export-theme`.
4. Commit the changes.
5. Tag the release.

## Requirements

- Docker with Docker Compose
- Node.js and npm
- Git

## Local URLs

- Site: http://localhost:8080
- Admin: http://localhost:8080/wp-admin
- phpMyAdmin: http://localhost:8081

## Dummy Admin Login

- Username: `gojago_admin`
- Password: `Gojago123!Secure`
- Email: `dev@gojago.com`

## Docker

```bash
docker compose config
docker compose up -d
docker compose ps
```

Container names follow `${PROJECT_NAME}` from `.env`:

- `project_db`
- `project_wordpress`
- `project_phpmyadmin`
- `project_wpcli`

The `wpcli` service installs WordPress when needed, activates `gojago-starter`, sets pretty permalinks, deletes default Twenty* themes when possible, and seeds starter pages/menus through theme activation.

## Theme Build

```bash
cd wp-content/themes/gojago-starter
npm install
npm run build
```

Build output:

- CSS: `assets/css/main.css`
- Custom block: `build/blocks/example-block`

## Folder Structure

- `docker/php/uploads.ini`: PHP upload and memory limits
- `plugins/`: ignored local folder for licensed commercial plugin ZIPs
- `wordpress/`: ignored bind-mounted WordPress runtime files
- `wp-content/themes/gojago-starter/`: the only custom theme
- `wp-content/themes/gojago-starter/inc/`: modular PHP setup
- `wp-content/themes/gojago-starter/templates/`: block templates
- `wp-content/themes/gojago-starter/parts/`: editable header/footer template parts
- `wp-content/themes/gojago-starter/patterns/`: reusable homepage patterns
- `wp-content/themes/gojago-starter/src/blocks/example-block/`: example advanced block

## Layout Defaults

- Theme `contentSize` and `wideSize` are `100%`
- Top-level templates and homepage sections render full width by default
- Header, footer, and designed section content use inner max-width wrappers for readability
- New client sections should start as full-width block sections unless the supplied design says otherwise

## Editing Workflow

Edit header and footer:

- Go to `wp-admin > Appearance > Editor > Patterns > Template Parts`
- Open `Header` or `Footer`
- Update logo, site title, navigation block, footer copy, or footer layout

Edit header/footer menu items:

- Block navigation path: `wp-admin > Appearance > Editor > Navigation`
- Classic menu path, when available: `wp-admin > Appearance > Menus`
- Registered locations: `Primary Menu` and `Footer Menu`

The header and footer template parts do not hardcode production menu links. Navigation is managed through WordPress menus/navigation.

Edit homepage sections:

- Go to `wp-admin > Pages > Home`
- Edit the Gutenberg content directly
- Starter sections are inserted from editable block patterns: hero, about, feature/proof row, service cards, media/gallery, and CTA

PM/QA can change hero headline, copy, buttons, cards, CTA, media placeholders, footer content, and navigation without touching code.

## ACF Pro

Licensed ZIPs are not committed. Put a ZIP in `plugins/`, for example:

- `acf-pro.zip`
- `advanced-custom-fields-pro.zip`
- `advanced-custom-fields-pro-*.zip`

Expected ZIP content: `advanced-custom-fields-pro/acf.php`.

The theme adds ACF local JSON save/load paths at:

- `wp-content/themes/gojago-starter/inc/acf/json/`

Manual upload path:

- `wp-admin > Plugins > Add Plugin > Upload Plugin`

The dashboard reminder detects missing/inactive ACF Pro and can install/activate it from a matching ZIP in `plugins/`.

## Gravity Forms Pro

Put a licensed ZIP in `plugins/`, for example:

- `gravityforms.zip`
- `gravity-forms.zip`
- `gravityforms_*.zip`
- `gravityforms-*.zip`

Expected ZIP content: `gravityforms/gravityforms.php`.

Manual upload path:

- `wp-admin > Plugins > Add Plugin > Upload Plugin`

Theme hooks and CSS are ready for Gravity Forms styling in:

- `wp-content/themes/gojago-starter/inc/gravity-forms/gravity-forms.php`
- `wp-content/themes/gojago-starter/src/css/main.css`

## Plugin ZIP Handling

`plugins/` is mounted read-only into WordPress and WP-CLI as `/project-plugins`.

Files inside `plugins/` are ignored by git, while `plugins/.gitkeep` preserves the folder. The wp-admin reminder checks filename hints and ZIP contents where possible, then protects install actions with nonces and capability checks.

## Upload Limits

Upload limits are configured in `docker/php/uploads.ini`:

- `upload_max_filesize = 64M`
- `post_max_size = 64M`
- `memory_limit = 256M`

If wp-admin says the uploaded file exceeds `upload_max_filesize`, increase both `upload_max_filesize` and `post_max_size`, then restart Docker.

## SEO

The theme supports `title-tag`, semantic templates, proper heading structures in starter patterns, local `noindex,nofollow`, and lightweight Open Graph tags for singular content.

SEO helper file:

- `wp-content/themes/gojago-starter/inc/seo/seo.php`

Production projects can add a dedicated SEO plugin or extend this scaffold.

## Analytics

Analytics is configured through environment/config placeholders, not hardcoded production snippets:

- `GOJAGO_GA_MEASUREMENT_ID`
- `GOJAGO_GTM_CONTAINER_ID`

Analytics helper file:

- `wp-content/themes/gojago-starter/inc/analytics/analytics.php`

Add approved GA4/GTM production snippets there.

## 404 Page

The custom 404 is a block template:

- `wp-content/themes/gojago-starter/templates/404.html`

It includes a clear heading, supportive copy, search, and a home link.

## Optional Inspo Workflow

If a future project includes `inspo/`, inspect all briefs, screenshots, assets, exports, videos, and notes before design work. Copy usable assets into the theme, usually:

- `assets/images/`
- `assets/videos/`

Optimize supplied videos for lightweight native `<video>` playback, use `preload="metadata"` or `preload="none"`, and add posters/fallbacks where available.

## QA Checklist

- Run `docker compose config`
- Run `docker compose up -d`
- Confirm `docker compose ps` shows healthy/running services
- Confirm site returns `200 OK`
- Confirm wp-admin is reachable
- Confirm phpMyAdmin is reachable
- Confirm `gojago-starter` is active
- Confirm `wp theme list` shows only `gojago-starter`
- Confirm footer shows `Created by Gojago`
- Confirm custom 404 renders
- Confirm upload limits are at least `64M`
- Confirm ACF Pro and Gravity Forms Pro dashboard reminder appears when missing/inactive
- Confirm header/footer are editable in Site Editor
- Confirm header/footer menu items are managed from Appearance navigation/menus
- Confirm homepage sections are editable on the Home page
- Confirm no important text overflows on desktop or mobile
- Run `npm run build`

## Verification Status

Verified on June 23, 2026:

- `docker compose config`: passed
- `docker compose up -d`: passed
- `docker compose ps`: `project_db`, `project_wordpress`, `project_phpmyadmin`, and `project_wpcli` running
- Site `http://localhost:8080`: `200 OK`
- Admin `http://localhost:8080/wp-admin/`: reachable, redirects to login as expected
- phpMyAdmin `http://localhost:8081`: `200 OK`
- Active theme: `gojago-starter`
- Installed themes: only `gojago-starter`
- Deleted bundled default Twenty* themes: deleted installed Twenty Twenty-Three, Twenty Twenty-Four, and Twenty Twenty-Five; Twenty Twenty-Two and Twenty Twenty-Six were not present
- `npm install`: completed with upstream WordPress package peer/deprecation warnings and npm audit findings
- `npm run build`: passed
- PHP lint in WordPress container: `functions.php` and `inc/setup/required-plugins.php` passed
- Upload limits: `upload_max_filesize=64M`, `post_max_size=64M`, `memory_limit=256M`
- Footer attribution: confirmed `Created by Gojago`
- Custom 404: confirmed output renders
- Plugin readiness: ACF Pro and Gravity Forms Pro report missing; no ZIPs were present in `plugins/`
- Custom example block: registered in WordPress
