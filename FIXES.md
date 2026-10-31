# Portfolio repair handoff

## What caused the overlap

The home/hero area was sized against the viewport while the page also used a fixed navigation layer and responsive positioning rules. That combination allowed the hero’s visual layer to extend over later document content, especially when navigating to anchors such as `#contact`. The repair keeps the hero visually full-height but restores deterministic document flow, adds safe section boundaries, and uses `scroll-padding-top` so the fixed navigation does not cover anchored sections.

## What was repaired

The public sections now use stable absolute include paths and a shared data layer. The data layer prefers MySQL when configured and falls back to `data/content.json` when MySQL is unavailable, so the public page and admin panel use one source of truth in either environment. Project images have a safe local fallback, and contact/footer rendering no longer depends on fragile relative paths.

The admin panel at `admin/` is now a connected control center. It includes authentication, CSRF protection, responsive navigation, live overview counts, hero editing with image upload, repeatable CRUD editors for services/projects/skills/testimonials, contact and social editing, section copy editing, ordering controls, featured/highlight toggles, and immediate publish feedback.

## Local access

Use `admin/index.php` to sign in. With no MySQL service configured, the local fallback credentials are `adem` / `admin123`. For production, configure MySQL and change the admin credentials before deployment. Fresh SQL installs use the corrected bcrypt hash in `database.sql`.

## Validation

All PHP files pass `php -l`, the admin JavaScript passes Node syntax validation, the fallback JSON parses successfully, the public page renders all sections, the contact anchor lands below the fixed navigation, the admin login reaches the control center, and an authenticated Hero save returns the success message and persists data.
