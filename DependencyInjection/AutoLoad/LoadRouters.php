<?php
/**
 * AutoLoad
 *
 * Compilerpass auto loader of CurrencyBundle.
 *
 * @vendor      BiberLtd
 * @package		CurrencyBundle
 * @subpackage	Controller
 * @name	    AutoLoad
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.0
 * @date        30.06.2013
 *
 */

namespace BiberLtd\Bundles\CurrencyBundle\DependencyInjection\Autoload;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class LoadRouters implements CompilerPassInterface{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('biberltd_currency.routing_loader')) {
            return;
        }

        $definition = $container->getDefinition('biberltd_currency.routing_loader');

        $arguments = array(
            __DIR__.'/../../Resources/config/routing.yml',
            'yaml'
        );
        $definition->addMethodCall('load', $arguments);
    }
}