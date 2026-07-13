# Upgrade Guide

Use this guide when you want to bring improvements from `dhysa/gojago-wpstarter` into an existing client theme repository.

The safest workflow is usually manual or cherry-picked, because client projects will change theme files over time.

## Recommended Flow

1. Read `CHANGELOG.md` for the target starter version.
2. Compare only the theme folder:

   ```bash
   git diff v1.0.0..main -- wp-content/themes/gojago-starter
   ```

3. Copy or cherry-pick the useful theme changes into the client theme repo.
4. Run the client theme build.
5. Test the client site locally.

## From v1.1.4 to v1.1.5

- Update `docker-compose.yml` so local setup chooses the newest matching plugin ZIP from `plugins/`, installs it with force/overwrite behavior, then runs the WordPress plugin updater.
- Copy the updated `app/setup/required-plugins.php` helper so wp-admin install actions can overwrite existing ZIP-installed plugins and request the latest official update before activation.
- When replacing ACF Pro, Gravity Forms Pro, WP Cerber, or another local ZIP plugin, put the ZIP in `plugins/` and rerun setup or use the wp-admin reminder action. Paid-plugin latest updates still require the plugin vendor's license/update channel to be available.

## From v1.1.3 to v1.1.4

- Remove duplicate root-level `templates/`, `parts/`, and `patterns/` folders from client themes.
- Keep `resources/views/templates`, `resources/views/parts`, and `resources/views/patterns` as the source of truth.
- Confirm `app/setup/views.php` is loaded from `functions.php`, because it maps `resources/views` into WordPress block template loading.

## From v1.1.2 to v1.1.3

- Remove any project-specific requirement/reminder for All-in-One WP Migration Unlimited Extension.
- Keep All-in-One WP Migration core installed and active for backup/migration workflows.

## From v1.1.1 to v1.1.2

- Copy `wp-content/mu-plugins/gojago-login-hardening.php` into existing client projects that should use `/managesite`.
- Update `docker-compose.yml` to mount `wp-content/mu-plugins` into WordPress and WP-CLI.
- Add `GOJAGO_LOGIN_SLUG=managesite`, `ACF_PRO_LICENSE_KEY=`, and `GRAVITY_FORMS_LICENSE_KEY=` to the local environment file.
- Copy `wp-content/themes/gojago-starter/app/setup/admin-bar.php` if the project should hide `Edit Site` and show `Customize CSS`.
- Re-run local setup or manually remove Hello Dolly/Akismet with WP-CLI.
- Existing client database content is not replaced automatically; update homepage content manually if you want the new clean starter homepage in an existing project.

## From v1.0.0 to v1.1.1

- Update `docker-compose.yml` if the project should install All-in-One WP Migration automatically.
- Update `docker/php/uploads.ini` if the project needs unlimited local migration upload/runtime limits.
