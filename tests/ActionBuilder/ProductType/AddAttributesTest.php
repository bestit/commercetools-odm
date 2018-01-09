<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\ProductType\AddAttributes;
use BestIt\CommercetoolsODM\ActionBuilder\ProductType\ProductTypeActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\ProductType\AttributeDefinitionCollection;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeAddAttributeDefinitionAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests AddAttributes.
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\ProductType
 * @version $id$
 */
class AddAttributesTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     * @var AddAttributes|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

    /**
     * Returns an array with the assertions for the support method.
     *
     * The First Element is the field path, the second element is the reference class and the optional third value
     * indicates the return value of the support method.
     * @return array
     */
    public function getSupportAssertions(): array
    {
        return [
            ['attributes', ProductType::class, true],
            ['attributss', ProductType::class],
            ['attributes', Product::class],
        ];
    }

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new AddAttributes();
    }

    /**
     * Checks the default return for the creation method.
     * @return void
     */
    public function testCreateUpdateActionsEmpty()
    {
        $object = new ProductType();

        static::assertSame(
            [],
            $this->fixture->createUpdateActions(
                uniqid(),
                $this->createMock(ClassMetadataInterface::class),
                [],
                [],
                $object
            )
        );
    }

    /**
     * Checks the default return for the creation method.
     * @return void
     */
    public function testCreateUpdateActionsFilled()
    {
        $object = new ProductType();

        $object->setAttributes(AttributeDefinitionCollection::fromArray([
            [
                'name' => 'attr1'
            ],
            [
                'name' => 'attr3'
            ]
        ]));

        $actions = $this->fixture->createUpdateActions(
            uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'attributes' => [
                    [
                        'name' => 'attr1'
                    ]
                ]
            ],
            $object
        );

        static::assertSame(1, count($actions));
        static::assertInstanceOf(ProductTypeAddAttributeDefinitionAction::class, $actions[0]);
        static::assertSame('attr3', $actions[0]->getAttribute()->getName());
    }

    /**
     * Checks the instance type for the action builder.
     * @return void
     */
    public function testType()
    {
        static::assertInstanceOf(ProductTypeActionBuilder::class, $this->fixture);
    }
}
