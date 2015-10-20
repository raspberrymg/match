<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\DependencyInjection\Truckee.php

namespace Truckee\MatchBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Truckee\MatchBundle\DependencyInjection\Configuration;

/**
 * TruckeeMatchExtension
 *
 */
class TruckeeMatchExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->updateContainerParameters($container, $config);
    }

    protected function updateContainerParameters(ContainerBuilder $container, array $config)
    {
        // Set a parameter for each configuration value
        $container->setParameter('truckee_match.expiring_alerts', $config['expiring_alerts']);
        $container->setParameter('truckee_match.opportunity_email', $config['opportunity_email']);
//        $container->setParameter('acme_app.my_boolean_node', $config['my_boolean_node']);
//        $container->setParameter('acme_app.my_enum_node', $config['my_enum_node']);
//        $container->setParameter('acme_app.my_array_node.child_setting_one', $config['my_array_node']['child_setting_one']);
//        $container->setParameter('acme_app.my_array_node.child_setting_two', $config['my_array_node']['child_setting_two']);
    }
}
