<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\PriceDataFeed\Persistence;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\PriceDataFeedTransfer;
use Orm\Zed\Price\Persistence\Base\SpyPriceProductQuery;
use Spryker\Zed\PriceDataFeed\Persistence\PriceDataFeedQueryContainer;
use Spryker\Zed\Price\Persistence\PriceQueryContainer;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group PriceDataFeed
 * @group Persistence
 * @group PriceDataFeedQueryContainerTest
 */
class PriceDataFeedQueryContainerTest extends Test
{

    /**
     * @var \Spryker\Zed\PriceDataFeed\Persistence\PriceDataFeedQueryContainer
     */
    protected $priceDataFeedQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\PriceDataFeedTransfer
     */
    protected $priceDataFeedTransfer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->priceDataFeedQueryContainer = $this->createPriceDataFeedQueryContainer();
        $this->priceDataFeedTransfer = $this->createPriceDataFeedTransfer();
    }

    /**
     * @return void
     */
    public function testGetPriceDataFeedQuery()
    {
        $query = $this->priceDataFeedQueryContainer
            ->queryPriceDataFeed($this->priceDataFeedTransfer);

        $expectedJoinedTables = $this->getDefaultJoinedTables();
        $joinedTables = $this->getJoinedTablesNames($query);

        $this->assertTrue($query instanceof SpyPriceProductQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testGetPriceDataFeedQueryWithJoinedTypes()
    {
        $this->priceDataFeedTransfer->setIsJoinPriceType(true);
        $query = $this->priceDataFeedQueryContainer
            ->queryPriceDataFeed($this->priceDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getTypeJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertTrue($query instanceof SpyPriceProductQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return \Spryker\Zed\PriceDataFeed\Persistence\PriceDataFeedQueryContainer
     */
    protected function createPriceDataFeedQueryContainer()
    {
        $priceQueryContainer = new PriceQueryContainer();
        $priceDataFeedQueryContainer = new PriceDataFeedQueryContainer($priceQueryContainer);

        return $priceDataFeedQueryContainer;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceDataFeedTransfer
     */
    protected function createPriceDataFeedTransfer()
    {
        $priceDataFeedTransfer = new PriceDataFeedTransfer();

        return $priceDataFeedTransfer;
    }

    /**
     * @param \Orm\Zed\Price\Persistence\Base\SpyPriceProductQuery $query
     *
     * @return array
     */
    protected function getJoinedTablesNames(SpyPriceProductQuery $query)
    {
        $tablesNames = [];
        $joins = $query->getJoins();

        foreach ($joins as $join) {
            $tablesNames[] = $join->getRightTableName();
        }
        asort($tablesNames);
        $tablesNames = array_values($tablesNames);

        return $tablesNames;
    }

    /**
     * @param array $tablesArray
     *
     * @return array
     */
    protected function getSortedExpectedJoinedTables($tablesArray)
    {
        asort($tablesArray);
        $tablesArray = array_values($tablesArray);

        return $tablesArray;
    }

    /**
     * @return array
     */
    protected function getDefaultJoinedTables()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getTypeJoinedTables()
    {
        return [
            'spy_price_type',
        ];
    }

}
