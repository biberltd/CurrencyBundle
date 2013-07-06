<?php

namespace BiberLtd\Bundles\CurrencyBundle;

use BiberLtd\Bundles\CurrencyBundle\DependencyInjection\AutoLoad;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BiberLtdBundlesCurrencyBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AutoLoad\LoadRouters());
    }
}
