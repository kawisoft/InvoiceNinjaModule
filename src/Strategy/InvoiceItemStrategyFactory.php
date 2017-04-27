<?php

namespace InvoiceNinjaModule\Strategy;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class InvoiceItemStrategyFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new InvoiceItemsStrategy($container->get('ReflectionHydrator'));
    }
}