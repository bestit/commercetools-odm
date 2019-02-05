<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Product\Publish;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductPublishAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class PublishTest
 *
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 * @subpackage ActionBuilder\Product
 */
class PublishTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var Publish|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

    /**
     * Returns an array with the assertions for the support method.
     *
     * The First Element is the field path, the second element is the reference class and the optional third value
     * indicates the return value of the support method.
     *
     * @return array
     */
    public function getSupportAssertions(): array
    {
        return [
            ['masterData/published', Product::class, true],
            ['masterData/unpublished', Product::class],
            ['published', Product::class],
            ['masterdata/published', Product::class],
            ['masterData/psdublished', Product::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new Publish();
    }

    /**
     * Checks if the product is published thru this action.
     *
     * @return void
     */
    public function testCreateUpdateActions()
    {
        $actions = $this->fixture->createUpdateActions(
            true,
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        );

        /** @var ProductPublishAction $action */
        static::assertCount(1, $actions, 'Action count was wrong.');
        static::assertInstanceOf(ProductPublishAction::class, $action = $actions[0], 'Wrong type.');
    }

    /**
     * Checks if the product is not unpublished thru this action.
     *
     * @return void
     */
    public function testCreateUpdateActionsIgnoreOnUnpublish()
    {
        $actions = $this->fixture->createUpdateActions(
            false,
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        );

        static::assertCount(0, $actions, 'Action count was wrong.');
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ProductActionBuilder::class, $this->fixture);
    }
}
