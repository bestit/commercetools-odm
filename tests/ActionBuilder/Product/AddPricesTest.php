<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\AddPrices;
use BestIt\CommercetoolsODM\ActionBuilder\Product\PriceActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\Price;
use Commercetools\Core\Model\Common\PriceCollection;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductCatalogData;
use Commercetools\Core\Model\Product\ProductData;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Request\Products\Command\ProductAddPriceAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests AddPrices.
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Product
 * @version $id$
 */
class AddPricesTest extends TestCase
{
    /**
     * The test class.
     * @var AddPrices|PHPUnit_Framework_MockObject_MockObject
     */
    private $fixture = null;

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new AddPrices();
    }

    /**
     * Checks the default return for the action builder.
     * @return void
     */
    public function testCreateUpdateActionsEmpty()
    {
        static::assertSame([], $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        ));
    }

    /**
     * Checks the default return for the action builder if there is no added price.
     * @return void
     */
    public function testCreateUpdateActionsNoChanges()
    {
        $this->fixture->setLastFoundMatch([uniqid(), 'staged', 'masterVariant']);

        $product = new Product();

        $product
            ->setMasterData(new ProductCatalogData())
            ->getMasterData()
            ->setStaged(new ProductData())
            ->getStaged()
            ->setMasterVariant(new ProductVariant())
            ->getMasterVariant()
            ->setPrices($prices = new PriceCollection());

        $prices->add(Price::fromArray([
            'id' => $oldId = uniqid()
        ]));

        static::assertSame([], $this->fixture->createUpdateActions(
            [
                ['id' => $oldId]
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $product
        ));
    }

    /**
     * Checks the return for the action builder if there is an added price.
     * @return void
     */
    public function testCreateUpdateActionsWithChanges()
    {
        $this->fixture->setLastFoundMatch([uniqid(), 'staged', 'masterVariant']);

        $product = new Product();

        $product
            ->setMasterData(new ProductCatalogData())
            ->getMasterData()
            ->setStaged(new ProductData())
            ->getStaged()
            ->setMasterVariant(new ProductVariant())
            ->getMasterVariant()
            ->setId($variantId = mt_rand(1, 10000))
            ->setPrices($prices = new PriceCollection());

        $prices->add(Price::fromArray([
            'id' => $oldId = uniqid()
        ]));

        $prices->add(Price::fromArray([
            'country' => $country = uniqid()
        ]));

        $actions = $this->fixture->createUpdateActions(
            [
                ['id' => $oldId],
                ['country' => $country],
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $product
        );

        /** @var ProductAddPriceAction $action */
        static::assertCount(1, $actions, 'Wrong action count.');
        static::assertInstanceOf(ProductAddPriceAction::class, $action = $actions[0], 'Wrong action type.');
        static::assertSame($variantId, $action->getVariantId(), 'Wrong variant id.');
        static::assertSame($country, $action->getPrice()->getCountry(), 'Wrong country.');
    }

    /**
     * Checks the default return for the action builder if there is no added price.
     * @return void
     */
    public function testCreateUpdateActionsNoPrices()
    {
        $this->fixture->setLastFoundMatch([uniqid(), 'staging', 'masterVariant']);

        static::assertSame([], $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        ));
    }

    /**
     * Checks the instance type for the action builder.
     * @return void
     */
    public function testType()
    {
        static::assertInstanceOf(PriceActionBuilder::class, $this->fixture);
    }
}
