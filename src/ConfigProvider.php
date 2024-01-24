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
            'package_name' => [
                'feature_notice' => 'foo',
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
