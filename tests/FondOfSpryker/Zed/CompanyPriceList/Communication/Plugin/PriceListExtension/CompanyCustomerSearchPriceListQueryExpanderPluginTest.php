<?php

namespace FondOfSpryker\Zed\CompanyPriceList\Communication\Plugin\PriceListExtension;

use Codeception\Test\Unit;
use Exception;
use FondOfSpryker\Shared\CompanyPriceList\CompanyPriceListConstants;
use FondOfSpryker\Zed\CompanyPriceList\Business\CompanyPriceListFacade;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\PriceList\Persistence\Map\FosPriceListTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

class CompanyCustomerSearchPriceListQueryExpanderPluginTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\CompanyPriceList\Communication\Plugin\PriceListExtension\CompanyCustomerSearchPriceListQueryExpanderPlugin
     */
    protected $plugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CompanyTransfer
     */
    protected $companyTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\FilterFieldTransfer
     */
    protected $filterFieldTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\QueryJoinCollectionTransfer
     */
    protected $queryJoinCollectionTransfer;

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

        $this->filterFieldTransferMock = $this->getMockBuilder(FilterFieldTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryJoinCollectionTransfer = $this->getMockBuilder(QueryJoinCollectionTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyPriceListFacadeMock = $this->getMockBuilder(CompanyPriceListFacade::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new CompanyCustomerSearchPriceListQueryExpanderPlugin();
        $this->plugin->setFacade($this->companyPriceListFacadeMock);
    }

    /**
     * @return void
     */
    public function testIsApplicableWillReturnFalse(): void
    {
        $this->filterFieldTransferMock->expects($this->atLeastOnce())
            ->method('getType')
            ->willReturn('');

        $this->assertFalse(
            $this->plugin->isApplicable(
                [$this->filterFieldTransferMock]
            )
        );
    }

    /**
     * @return void
     */
    public function testIsApplicableWillReturnTrue(): void
    {
        $this->filterFieldTransferMock->expects($this->atLeastOnce())
            ->method('getType')
            ->willReturn(CompanyPriceListConstants::FILTER_FIELD_TYPE_ID_CUSTOMER);

        $this->assertTrue(
            $this->plugin->isApplicable(
                [$this->filterFieldTransferMock]
            )
        );
    }

    /**
     * @return void
     */
    public function testExpandWillDoNothing(): void
    {
        $this->filterFieldTransferMock->expects($this->atLeastOnce())
            ->method('getType')
            ->willReturn('');

        $this->plugin->expand(
            [$this->filterFieldTransferMock],
            $this->queryJoinCollectionTransfer
        );
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function testExpand(): void
    {
        $self = $this;

        $this->filterFieldTransferMock->expects($this->atLeastOnce())
            ->method('getType')
            ->willReturn(CompanyPriceListConstants::FILTER_FIELD_TYPE_ID_CUSTOMER);

        $this->filterFieldTransferMock->expects($this->atLeastOnce())
            ->method('getValue')
            ->willReturn('value');

        $this->queryJoinCollectionTransfer->expects($this->atLeastOnce())
            ->method('addQueryJoin')
            ->willReturnCallback(static function (QueryJoinTransfer $queryJoinTransfer) use ($self) {

                if ($queryJoinTransfer->getLeft() === [FosPriceListTableMap::COL_ID_PRICE_LIST]) {
                    static::assertSame($queryJoinTransfer->getJoinType(), Criteria::INNER_JOIN);
                    static::assertSame($queryJoinTransfer->getLeft(), [FosPriceListTableMap::COL_ID_PRICE_LIST]);
                    static::assertSame($queryJoinTransfer->getRight(), [SpyCompanyTableMap::COL_FK_PRICE_LIST]);
                } elseif ($queryJoinTransfer->getLeft() === [SpyCompanyTableMap::COL_ID_COMPANY]) {
                    static::assertSame($queryJoinTransfer->getJoinType(), Criteria::INNER_JOIN);
                    static::assertSame($queryJoinTransfer->getLeft(), [SpyCompanyTableMap::COL_ID_COMPANY]);
                    static::assertSame($queryJoinTransfer->getRight(), [SpyCompanyUserTableMap::COL_FK_COMPANY]);

                    static::assertSame($queryJoinTransfer->getWhereConditions()[0]->getValue(), 'value');
                    static::assertSame($queryJoinTransfer->getWhereConditions()[0]->getColumn(), SpyCompanyUserTableMap::COL_FK_CUSTOMER);
                    static::assertSame($queryJoinTransfer->getWhereConditions()[0]->getComparison(), Criteria::EQUAL);
                } else {
                    throw new Exception('fail test');
                }

                return $self->queryJoinCollectionTransfer;
            });

        $this->plugin->expand(
            [$this->filterFieldTransferMock],
            $this->queryJoinCollectionTransfer
        );
    }
}
