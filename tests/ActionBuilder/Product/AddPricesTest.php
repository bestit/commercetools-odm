<?php

declare(strict_types=1);

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
use Commercetools\Core\Model\Product\ProductVariantCollection;
use Commercetools\Core\Request\Products\Command\ProductAddPriceAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests AddPrices.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 */
class AddPricesTest extends TestCase
{
    /**
     * The test class.
     *
     * @var AddPrices|PHPUnit_Framework_MockObject_MockObject|null
     */
    private $fixture;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new AddPrices();
    }

    /**
     * Checks the return for the action builder if there is an added price for a variant.
     *
     * @return void
     */
    public function testCreateUpdateActionsWithChangesForAVariant()
    {
        $this->fixture->setLastFoundMatch([uniqid(), 'staged', 'variants', $variantIndex = 0]);

        $variantId = 2;

        $product = new Product();

        $product
            ->setMasterData(new ProductCatalogData())
            ->getMasterData()
            ->setStaged(new ProductData())
            ->getStaged()
            ->setMasterVariant(new ProductVariant())
            ->setVariants($variants = new ProductVariantCollection())
            ->getMasterVariant();

        $product
            ->getMasterData()
            ->setCurrent($product->getMasterData()->getStaged());

        $prices = new PriceCollection();

        $prices->add(Price::fromArray([
            'id' => $oldId = uniqid(),
        ]));

        $prices->add(Price::fromArray([
            'country' => $country = uniqid(),
        ]));

        $variant = new ProductVariant();

        $variant
            ->setId($variantId)
            ->setSku($sku = 'sku')
            ->setPrices($prices);

        $variants->setAt($variantIndex, $variant);

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
     * Checks the default return for the action builder.
     *
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
     * No prices should be added, if the given added array is empty.
     *
     * @return void
     */
    public function testCreateUpdateActionsIgnoreNullChanges()
    {
        $this->fixture->setLastFoundMatch([uniqid(), 'staged', 'masterVariant', '']);

        $product = new Product();

        $product
            ->setMasterData(new ProductCatalogData())
            ->getMasterData()
            ->setStaged(new ProductData())
            ->getStaged()
            ->setMasterVariant(new ProductVariant())
            ->getMasterVariant()
            ->setId(1)
            ->setPrices($prices = new PriceCollection());

        $prices->add(Price::fromArray([
            'id' => $oldId = uniqid(),
        ]));

        $prices->add(Price::fromArray([
            'country' => $country = uniqid(),
        ]));

        $actions = $this->fixture->createUpdateActions(
            [
                ['id' => $oldId],
                null,
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $product
        );

        /** @var ProductAddPriceAction $action */
        static::assertCount(0, $actions, 'Wrong action count.');
    }

    /**
     * Checks the default return for the action builder if there is no added price.
     *
     * @return void
     */
    public function testCreateUpdateActionsNoChanges()
    {
        $this->fixture->setLastFoundMatch([uniqid(), 'staged', 'masterVariant', '']);

        $product = new Product();

        $product
            ->setMasterData(new ProductCatalogData())
            ->getMasterData()
            ->setStaged(new ProductData())
            ->getStaged()
            ->setMasterVariant(new ProductVariant())
            ->getMasterVariant()
            ->setId(1)
            ->setPrices($prices = new PriceCollection());

        $prices->add(Price::fromArray([
            'id' => $oldId = uniqid(),
        ]));

        static::assertSame([], $this->fixture->createUpdateActions(
            [
                ['id' => $oldId],
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $product
        ));
    }

    /**
     * Checks the default return for the action builder if there is no added price.
     *
     * @return void
     */
    public function testCreateUpdateActionsNoPrices()
    {
        $this->fixture->setLastFoundMatch([uniqid(), 'staged', 'masterVariant', '']);

        $product = new Product();

        $product
            ->setMasterData(new ProductCatalogData())
            ->getMasterData()
            ->setStaged(new ProductData())
            ->getStaged()
            ->setMasterVariant(new ProductVariant())
            ->getMasterVariant()
            ->setId(1)
            ->setPrices($prices = new PriceCollection());

        static::assertSame([], $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $product
        ));
    }

    /**
     * Checks the return for the action builder if there is an added price.
     *
     * @return void
     */
    public function testCreateUpdateActionsWithChanges()
    {
        $this->fixture->setLastFoundMatch([uniqid(), 'staged', 'masterVariant', '']);

        $product = new Product();

        $product
            ->setMasterData(new ProductCatalogData())
            ->getMasterData()
            ->setStaged(new ProductData())
            ->getStaged()
            ->setMasterVariant(new ProductVariant())
            ->getMasterVariant()
            ->setId(1)
            ->setPrices($prices = new PriceCollection());

        $prices->add(Price::fromArray([
            'id' => $oldId = uniqid(),
        ]));

        $prices->add(Price::fromArray([
            'country' => $country = uniqid(),
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
        static::assertSame(1, $action->getVariantId(), 'Wrong variant id.');
        static::assertSame($country, $action->getPrice()->getCountry(), 'Wrong country.');
    }

    /**
     * @return void
     */
    public function testNoActionsAreReturnedIfThereIsNoLastFoundMatch()
    {
        $this->fixture->setLastFoundMatch([]);

        $actions = $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        );

        static::assertEmpty($actions, 'Wrong action count.');
    }

    /**
     * @return void
     */
    public function testNoActionsAreReturnedIfTheInputIsInvalid()
    {
        $this->fixture->setLastFoundMatch(['match']);

        $actions = $this->fixture->createUpdateActions(
            'invalid',
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        );

        static::assertEmpty($actions, 'Wrong action count.');
    }

    /**
     * @return void
     */
    public function testNoActionsAreReturnedIfTheVariantCouldNotBeFound()
    {
        $this->fixture->setLastFoundMatch([uniqid(), 'staged', 'masterVariant', '']);

        $product = new Product();

        $product
            ->setMasterData(new ProductCatalogData())
            ->getMasterData()
            ->setStaged(new ProductData())
            ->getStaged()
            ->setMasterVariant(new ProductVariant())
            ->setVariants(new ProductVariantCollection())
            ->getMasterVariant();

        $actions = $this->fixture->createUpdateActions(
            'invalid',
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $product
        );

        static::assertEmpty($actions, 'Wrong action count.');
    }

    /**
     * Checks the instance type for the action builder.
     *
     * @return void
     */
    public function testType()
    {
        static::assertInstanceOf(PriceActionBuilder::class, $this->fixture);
    }
}
