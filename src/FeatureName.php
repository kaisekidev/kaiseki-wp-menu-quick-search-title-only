<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\MenuQuickSearchTitleOnly;

use Kaiseki\WordPress\Hook\HookCallbackProviderInterface;

use function add_action;

final class FeatureName implements HookCallbackProviderInterface
{
    /**
     * @param list<string> $postTypes
     */
    private array $postTypes;

    public function __construct(
        array $postTypes = [],
        private readonly int $postsPerPage = 100,
    )
    {
        $this->postTypes = array_map(
            static fn (string $postType) => 'quick-search-posttype-' . $postType,
            $postTypes
        );
    }

    public function registerHookCallbacks(): void
    {
        add_action('pre_get_posts', [$this, 'preGetPosts'], 1, 2);
    }

    public function preGetPosts(\WP_Query $q): void
    {
        if (!$this->isRelevantPostRequest()) {
            return;
        }

        $q->set('search_post_title', $_POST['q']);
        $q->set('posts_per_page', $this->postsPerPage);
        add_filter('posts_where', [$this, 'updateWhereClause'], 10, 2);
    }

    public function updateWhereClause(string $where, \WP_Query $wp_query)
    {
        global $wpdb;
        if ($search_term = $wp_query->get('search_post_title')) {
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql($wpdb->esc_like($search_term)) . '%\'';
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

        if (
            !isset($_POST['type'])
            || !in_array($_POST['type'], $this->postTypes)
        ) {
            return false;
        }

        return true;
    }
}
