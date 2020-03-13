<?php

namespace FondOfSpryker\Zed\CompanyPriceList\Business\Model;

use FondOfSpryker\Zed\CompanyPriceList\Dependency\Facade\CompanyPriceListToPriceListFacadeInterface;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\PriceListTransfer;

class CompanyHydrator implements CompanyHydratorInterface
{
    /**
     * @var \FondOfSpryker\Zed\CompanyPriceList\Dependency\Facade\CompanyPriceListToPriceListFacadeInterface
     */
    protected $priceListFacade;

    /**
     * @param \FondOfSpryker\Zed\CompanyPriceList\Dependency\Facade\CompanyPriceListToPriceListFacadeInterface $priceListFacade
     */
    public function __construct(CompanyPriceListToPriceListFacadeInterface $priceListFacade)
    {
        $this->priceListFacade = $priceListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function hydrate(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        $priceListId = $companyTransfer->getFkPriceList();

        if ($priceListId === null) {
            return $companyTransfer;
        }

        $priceListTransfer = (new PriceListTransfer())->setIdPriceList($priceListId);

        $priceListTransfer = $this->priceListFacade->findPriceListById($priceListTransfer);

        return $companyTransfer->setPriceList($priceListTransfer);
    }
}
