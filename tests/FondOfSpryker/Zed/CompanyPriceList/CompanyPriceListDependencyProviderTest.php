<?php

namespace FondOfSpryker\Zed\CompanyPriceList;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Container;

class CompanyPriceListDependencyProviderTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\CompanyPriceList\CompanyPriceListDependencyProvider
     */
    protected $companyPriceListDependencyProvider;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\Container
     */
    protected $containerMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyPriceListDependencyProvider = new CompanyPriceListDependencyProvider();
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependencies(): void
    {
        $this->assertInstanceOf(
            Container::class,
            $this->companyPriceListDependencyProvider->provideBusinessLayerDependencies(
                $this->containerMock
            )
        );
    }
}
