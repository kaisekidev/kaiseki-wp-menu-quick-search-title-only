# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.0.0 - 2026-06-02

First tagged release.

### Added

- `UpdateRequest` hook provider (and `ConfigProvider`) that restricts the WordPress admin menu
  quick-search (`action=menu-quick-search`) to title-only matching: it sets a `posts_where` clause
  adding a `LIKE` on `wp_posts.post_title` and caps results via `posts_per_page`. Behaviour is tuned
  through the `menu_quick_search_title_only` config key (`post_types`, `posts_per_page`).

### Changed

- PHP requirement is `^8.2` (PHP 8.4 is the primary target).
- Modernized the dev toolchain (PHPStan 2, PHPUnit 11 schema, composer-require-checker 4); now depends
  on `kaiseki/php-coding-standard: ^1.0` with the shared PHPStan config; `kaiseki/config` and
  `kaiseki/wp-hook` pinned to `^2.0`. CI now runs via the reusable workflow in `kaisekidev/.github`.
- `composer.json` `license` corrected from `proprietary` to `MIT` to match the bundled LICENSE file.
- CI coverage threshold set to 33% in the `checks.yml` caller: only `ConfigProvider` is unit-tested
  today (line coverage 33.33%); `UpdateRequest` and `UpdateRequestFactory` exercise WordPress globals
  and are not yet covered.

### Fixed

- PHPStan 2 (level max): `updateWhereClause` now narrows the `$wpdb` global with an `instanceof wpdb`
  guard (returning the unchanged `WHERE` clause when unavailable) and drops a redundant `(string)`
  cast on `esc_like()`. No behaviour change for valid quick-search requests.
