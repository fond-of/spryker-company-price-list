<?php

namespace FondOfSpryker\Zed\CompanyPriceList;

use FondOfSpryker\Zed\CompanyPriceList\Dependency\Facade\CompanyPriceListToPriceListFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyPriceListDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRICE_LIST = 'FACADE_PRICE_LIST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addPriceListFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceListFacade(Container $container): Container
    {
        $container[static::FACADE_PRICE_LIST] = function (Container $container) {
            return new CompanyPriceListToPriceListFacadeBridge(
                $container->getLocator()->priceList()->facade()
            );
        };

        return $container;
    }
}
