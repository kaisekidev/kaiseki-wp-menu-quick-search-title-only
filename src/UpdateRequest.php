<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\MenuQuickSearchTitleOnly;

use Kaiseki\WordPress\Hook\HookProviderInterface;
use WP_Query;

use function add_action;
use function add_filter;
use function array_map;
use function esc_sql;
use function in_array;
use function is_string;
use function remove_filter;

final class UpdateRequest implements HookProviderInterface
{
    /** @var list<string> */
    private array $postTypes;

    /**
     * @param list<string> $postTypes
     * @param int          $postsPerPage
     */
    public function __construct(
        array $postTypes = [],
        private readonly int $postsPerPage = 100,
    ) {
        $this->postTypes = array_map(
            static fn(string $postType) => 'quick-search-posttype-' . $postType,
            $postTypes
        );
    }

    public function addHooks(): void
    {
        add_action('pre_get_posts', [$this, 'preGetPosts'], 1);
    }

    public function preGetPosts(WP_Query $q): void
    {
        if (!$this->isRelevantPostRequest()) {
            return;
        }

        $q->set('search_post_title', $_POST['q']);
        $q->set('posts_per_page', $this->postsPerPage);
        add_filter('posts_where', [$this, 'updateWhereClause'], 10, 2);
    }

    public function updateWhereClause(string $where, WP_Query $wpQuery): string
    {
        global $wpdb;
        $searchTerm = $wpQuery->get('search_post_title');
        if (is_string($searchTerm) && $searchTerm !== '') {
            $like = '%' . esc_sql((string)$wpdb->esc_like($searchTerm)) . '%';
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . $like . '\'';
        }
        remove_filter('posts_where', [$this, 'title_filter']);

        return $where;
    }

    private function isRelevantPostRequest(): bool
    {
        if (
            !isset($_POST['action'])
            || $_POST['action'] !== "menu-quick-search"
            || !isset($_POST['q'])
        ) {
            return false;
        }

        if ($this->postTypes === []) {
            return true;
        }

        return
            isset($_POST['type'])
            && in_array($_POST['type'], $this->postTypes, true);
    }
}
