<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\MenuQuickSearchTitleOnly;

use Kaiseki\Config\Config;
use Psr\Container\ContainerInterface;

final class FeatureNameFactory
{
    public function __invoke(ContainerInterface $container): FeatureName
    {
        $config = Config::get($container);
        /** @var list<string> $postTypes */
        $postTypes = $config->array('menu_quick_search_title_only/post_types');
        return new FeatureName(
            $postTypes,
            $config->int('menu_quick_search_title_only/posts_per_page')
        );
    }
}
