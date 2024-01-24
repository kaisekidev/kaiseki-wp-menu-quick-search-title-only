<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\MenuQuickSearchTitleOnly;

final class ConfigProvider
{
    /**
     * @return array<mixed>
     */
    public function __invoke(): array
    {
        return [
            'menu_quick_search_title_only' => [
                'post_types' => [],
                'posts_per_page' => 20,
            ],
            'hook' => [
                'provider' => [
                    UpdateRequest::class,
                ],
            ],
            'dependencies' => [
                'aliases' => [],
                'factories' => [
                    UpdateRequest::class => UpdateRequestFactory::class,
                ],
            ],
        ];
    }
}
