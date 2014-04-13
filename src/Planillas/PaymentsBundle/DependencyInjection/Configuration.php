<?php

namespace Planillas\PaymentsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('planillas_payments');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
            ->scalarNode('dias_por_mes')->defaultValue(30)->end()
            ->scalarNode('factor_seguro')->defaultValue(0.09)->end()
            ->scalarNode('horas_diurno')->defaultValue(8)->end()
            ->scalarNode('indice_horas_extras')->defaultValue(1.5)->end()
            ->end();

        return $treeBuilder;
    }
}
