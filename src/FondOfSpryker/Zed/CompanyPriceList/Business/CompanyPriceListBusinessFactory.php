<?php

namespace FondOfSpryker\Zed\CompanyPriceList\Business;

use FondOfSpryker\Zed\CompanyPriceList\Business\Model\CompanyHydrator;
use FondOfSpryker\Zed\CompanyPriceList\Business\Model\CompanyHydratorInterface;
use FondOfSpryker\Zed\CompanyPriceList\CompanyPriceListDependencyProvider;
use FondOfSpryker\Zed\CompanyPriceList\Dependency\Facade\CompanyPriceListToPriceListFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class CompanyPriceListBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \FondOfSpryker\Zed\CompanyPriceList\Business\Model\CompanyHydratorInterface
     */
    public function createCompanyHydrator(): CompanyHydratorInterface
    {
        return new CompanyHydrator($this->getPriceListFacade());
    }

    /**
     * @return \FondOfSpryker\Zed\CompanyPriceList\Dependency\Facade\CompanyPriceListToPriceListFacadeInterface
     */
    protected function getPriceListFacade(): CompanyPriceListToPriceListFacadeInterface
    {
        return $this->getProvidedDependency(CompanyPriceListDependencyProvider::FACADE_PRICE_LIST);
    }
}
