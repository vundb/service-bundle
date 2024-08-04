<?php

namespace Vundb\ServiceBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class VundbServiceBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('database_name')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }
}
