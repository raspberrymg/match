<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\DependencyInjection\Configuration.php


namespace Truckee\MatchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('truckee_match');

        $rootNode
            ->children()
                ->booleanNode('expiring_alerts')->defaultTrue()->end()
                ->booleanNode('opportunity_email')->defaultTrue()->end()
                ->booleanNode('search_email')->defaultTrue()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
