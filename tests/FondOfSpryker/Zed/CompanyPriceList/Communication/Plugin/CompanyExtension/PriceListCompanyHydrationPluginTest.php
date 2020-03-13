<?php

namespace FondOfSpryker\Zed\CompanyPriceList\Communication\Plugin\CompanyExtension;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\CompanyPriceList\Business\CompanyPriceListFacade;
use Generated\Shared\Transfer\CompanyTransfer;

class PriceListCompanyHydrationPluginTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\CompanyPriceList\Communication\Plugin\CompanyExtension\PriceListCompanyHydrationPlugin
     */
    protected $priceListCompanyHydrationPlugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CompanyTransfer
     */
    protected $companyTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\CompanyPriceList\Business\CompanyPriceListFacade
     */
    protected $companyPriceListFacadeMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->companyTransferMock = $this->getMockBuilder(CompanyTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyPriceListFacadeMock = $this->getMockBuilder(CompanyPriceListFacade::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListCompanyHydrationPlugin = new PriceListCompanyHydrationPlugin();
        $this->priceListCompanyHydrationPlugin->setFacade($this->companyPriceListFacadeMock);
    }

    /**
     * @return void
     */
    public function testHydrate(): void
    {
        $this->companyPriceListFacadeMock->expects($this->atLeastOnce())
            ->method('hydrateCompany')
            ->with($this->companyTransferMock)
            ->willReturn($this->companyTransferMock);

        $this->assertInstanceOf(
            CompanyTransfer::class,
            $this->priceListCompanyHydrationPlugin->hydrate(
                $this->companyTransferMock
            )
        );
    }
}
