# kaiseki/wp-menu-quick-search-title-only

Restrict the WordPress admin menu quick-search to title-only matching, with configurable post types and result count.

A single `kaiseki/wp-hook` `HookProviderInterface` (`UpdateRequest`) that hooks into the admin menu
"quick search" AJAX request (`action=menu-quick-search`). When the request is relevant it forces the
underlying `WP_Query` to match the search term against `post_title` only — via a `posts_where` clause
that adds a `LIKE` on `wp_posts.post_title` — instead of WordPress' default broader search, and caps
the number of results returned.

## Installation

```bash
composer require kaiseki/wp-menu-quick-search-title-only
```

Requires PHP 8.2 or newer.

## Usage

Register `ConfigProvider` with your laminas-style config aggregator and activate the provider via
`kaiseki/wp-hook`. `ConfigProvider` wires `UpdateRequest` as a hook provider and supplies its default
config:

```php
use Kaiseki\WordPress\MenuQuickSearchTitleOnly\ConfigProvider;

return (new ConfigProvider())();
```

Tune behaviour through the `menu_quick_search_title_only` config key:

```php
return [
    'menu_quick_search_title_only' => [
        // Post types whose quick-search should be title-only. Empty means
        // "apply to every quick-search request".
        'post_types'     => ['post', 'page'],
        // Maximum number of results returned by the quick search.
        'posts_per_page' => 20,
    ],
];
```

When `post_types` is non-empty, the filter only applies to requests whose `type` matches one of the
configured post types (matched against the `quick-search-posttype-<type>` markers WordPress sends); an
empty list applies the title-only behaviour to all quick-search requests.

## Development

```bash
composer install
composer check   # check-deps, cs-check, phpstan
composer phpunit
```

## License

MIT — see [LICENSE](LICENSE).
