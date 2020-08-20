<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Mapping;

use BestIt\CommercetoolsODM\Mapping\Annotations\Field;
use BestIt\CommercetoolsODM\Mapping\ClassMetadata;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\Mapping\ClassMetadata\TestClass;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Model\Channel\Channel;
use Commercetools\Core\Model\CustomObject\CustomObject;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Model\Inventory\InventoryEntry;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductProjection;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Model\ShippingMethod\ShippingMethod;
use Commercetools\Core\Model\ShoppingList\ShoppingList;
use Commercetools\Core\Model\Zone\Zone;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use function uniqid;

/**
 * Class ClassMetadataTest.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Mapping
 */
class ClassMetadataTest extends TestCase
{
    /**
     * @var ClassMetadata|void The tested class.
     */
    private $fixture;

    /**
     * @var string|void The used object name.
     */
    private $objectName;

    /**
     * Returns the classes which should be a standard model.
     *
     * @return array
     */
    public function getStandardModels(): array
    {
        return [
            [Cart::class],
            [Category::class],
            [Channel::class],
            [CustomObject::class],
            [Customer::class],
            [InventoryEntry::class],
            [Order::class],
            [Product::class],
            [ProductProjection::class],
            [ProductType::class],
            [ShippingMethod::class],
            [ShoppingList::class],
            [Zone::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new ClassMetadata($this->objectName = TestClass::class);
    }

    /**
     * Checks the getter and setter.
     *
     * @return void
     */
    public function testGetAndSetFieldMappings()
    {
        $this->assertSame([], $this->fixture->getFieldMappings(), 'Wrong default return.');

        $this->assertSame(
            $this->fixture,
            $this->fixture->setFieldMappings($fields = [uniqid() => uniqid()]),
            'Fluent interface broken.'
        );

        $this->assertSame(
            $fields,
            $this->fixture->getFieldMappings(),
            'Wrong persistent.'
        );
    }

    /**
     * Checks the getter and setter.
     *
     * @return void
     */
    public function testGetAndSetReflectionClass()
    {
        $this->assertInstanceOf(ReflectionClass::class, $this->fixture->getReflectionClass());

        $this->assertSame(
            $this->fixture,
            $this->fixture->setReflectionClass($mock = new ReflectionClass(Order::class)),
            'Fluent interface broken.'
        );

        $this->assertSame($mock, $this->fixture->getReflectionClass(), 'Object not saved.');
    }

    /**
     * Checks the field name getter for the standard model.
     *
     * @return void
     */
    public function testGetFieldNamesStandard()
    {
        $this->fixture = new ClassMetadata($this->objectName = Order::class);

        $this->assertSame(
            array_keys((new $this->objectName())->fieldDefinitions()),
            $this->fixture->getFieldNames()
        );
    }

    /**
     * Checks the name getter.
     *
     * @return void
     */
    public function testGetName()
    {
        $this->assertSame($this->objectName, $this->fixture->getName());
    }

    /**
     * Checks if the ignore marker is used correctly for the given field.
     *
     * @return void
     */
    public function testIgnoreFieldOnEmptyFalse()
    {
        $this->fixture->setFieldMappings([
            ($fieldName = uniqid()) => $fieldObject = new Field(),
        ]);

        $this->assertFalse($this->fixture->ignoreFieldOnEmpty($fieldName));
    }

    /**
     * Checks if the ignore marker falls back correctly on a missing field.
     *
     * @return void
     */
    public function testIgnoreFieldOnEmptyMissing()
    {
        $this->fixture->setFieldMappings([
            ($fieldName = uniqid()) => $fieldObject = new Field(),
        ]);

        $this->assertFalse($this->fixture->ignoreFieldOnEmpty($fieldName . uniqid()));
    }

    /**
     * Checks if the ignore marker is used correctly for the given field.
     *
     * @return void
     */
    public function testIgnoreFieldOnEmptyTrue()
    {
        $this->fixture->setFieldMappings([
            ($fieldName = uniqid()) => $fieldObject = new Field(),
        ]);

        $fieldObject->ignoreOnEmpty = true;

        $this->assertTrue($this->fixture->ignoreFieldOnEmpty($fieldName));
    }

    /**
     * Checks the used interface.
     *
     * @return void
     */
    public function testInterface()
    {
        $this->assertInstanceOf(ClassMetadataInterface::class, $this->fixture);
    }

    /**
     * The registered class is no standard model.
     *
     * @return void
     */
    public function testIsCTStandardModelFalse()
    {
        static::assertFalse($this->fixture->isCTStandardModel());
    }

    /**
     * The given class should be a standard model.
     *
     * @dataProvider getStandardModels
     *
     * @param string $class
     * @param bool $isStandard
     *
     * @return void
     */
    public function testIsCTStandardModelSuccess(string $class, bool $isStandard = true)
    {
        $this->fixture = new ClassMetadata($class);

        static::assertSame($isStandard, $this->fixture->isCTStandardModel());
    }
}
