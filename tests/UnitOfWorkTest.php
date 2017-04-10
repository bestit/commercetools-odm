<?php

namespace BestIt\CommercetoolsODM\Tests;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderProcessorInterface;
use BestIt\CommercetoolsODM\ActionBuilderProcessorAwareTrait;
use BestIt\CommercetoolsODM\ClientAwareTrait;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Event\ListenersInvoker;
use BestIt\CommercetoolsODM\Helper\EventManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\ListenerInvokerAwareTrait;
use BestIt\CommercetoolsODM\Mapping\Annotations\Field;
use BestIt\CommercetoolsODM\Mapping\ClassMetadata;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\UnitOfWork\TestCustomEntity;
use BestIt\CommercetoolsODM\UnitOfWork;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Commercetools\Core\Model\Cart\LineItem;
use Commercetools\Core\Model\Common\Address;
use Commercetools\Core\Model\Common\AddressCollection;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Model\Order\Order;
use Doctrine\Common\EventManager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionClass;
use RuntimeException;

/**
 * Class UnitOfWorkTest
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
class UnitOfWorkTest extends TestCase
{
    /**
     * The used document manager.
     * @var ActionBuilderProcessorInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $actionBuilderProcessor = null;

    /**
     * The used document manager.
     * @var DocumentManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $documentManager = null;

    /**
     * The fixture.
     * @var UnitOfWork
     */
    private $fixture = null;

    /**
     * Returns the used traits.
     * @return array
     */
    public static function getUsedTraits(): array
    {
        return [
            [ActionBuilderProcessorAwareTrait::class],
            [ClientAwareTrait::class],
            [EventManagerAwareTrait::class],
            [ListenerInvokerAwareTrait::class]
        ];
    }

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new UnitOfWork(
            $this->actionBuilderProcessor = static::createMock(ActionBuilderProcessorInterface::class),
            $this->documentManager = static::createMock(DocumentManagerInterface::class),
            static::createMock(EventManager::class),
            static::createMock(ListenersInvoker::class)
        );
    }

    /**
     * Checks the default return for the count function.
     * @return void
     */
    public function testCountDefault()
    {
        static::assertCount(0, $this->fixture);
    }

    /**
     * Checks the default return for the count method.
     * @return void
     */
    public function testCountManagedObjectsDefault()
    {
        static::assertSame(0, $this->fixture->countManagedObjects());
    }

    /**
     * Checks if an array for a custom entity is parsed correctly.
     * @covers UnitOfWork::createDocument()
     * @return void
     */
    public function testCreateDocumentParseCustomEntitiesArrayProperty()
    {
        $this->documentManager
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with(TestCustomEntity::class)
            ->will($this->returnValue($metadata = new ClassMetadata(TestCustomEntity::class)));

        $metadata
            ->setFieldMappings(['addresses' => $field = new Field()])
            ->setReflectionClass(new ReflectionClass(TestCustomEntity::class));

        $field->collection = AddressCollection::class;
        $field->type = 'array';

        /** @var TestCustomEntity $createdDoc */
        $createdDoc = $this->fixture->createDocument(
            TestCustomEntity::class,
            Customer::fromArray([
                'addresses' => [
                    [
                        'id' => $addressId = uniqid(),
                        'salutation' => 'mr',
                        'firstName' => 'Björn',
                        'lastName' => 'Lange',
                        'streetName' => 'Rekener Str',
                        'streetNumber' => '60',
                        'additionalStreetInfo' => 'CTO',
                        'postalCode' => '46342',
                        'city' => 'Velen',
                        'region' => 'Nordrhein-Westfalen',
                        'state' => 'Nordrhein-Westfalen',
                        'country' => 'DE',
                        'company' => 'best it',
                        'department' => 'Management',
                        'apartment' => 'best it GmbH & Co. KG',
                        'phone' => '+49 2863 38362773',
                        'mobile' => '+49 160 91084976',
                        'email' => 'lange@bestit-online.de'
                    ]
                ]
            ])
        );

        /** @var $address Address */
        static::assertCount(1, $addresses = $createdDoc->getAddresses(), 'Wrong address count.');
        static::assertInstanceOf(Address::class, $address = $addresses[0], 'Wrong address instance.');
        static::assertSame($addressId, $address->getId(), 'Wrong address id.');
    }

    /**
     * Checks if an array for a custom entity is parsed correctly even if its null.
     * @covers UnitOfWork::createDocument()
     * @return void
     */
    public function testCreateDocumentParseCustomEntitiesArrayPropertyParseNull()
    {
        $this->documentManager
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with(TestCustomEntity::class)
            ->will($this->returnValue($metadata = new ClassMetadata(TestCustomEntity::class)));

        $metadata
            ->setFieldMappings(['addresses' => $field = new Field()])
            ->setReflectionClass(new ReflectionClass(TestCustomEntity::class));

        $field->collection = AddressCollection::class;
        $field->type = 'array';

        /** @var TestCustomEntity $createdDoc */
        $createdDoc = $this->fixture->createDocument(
            TestCustomEntity::class,
            Customer::fromArray([])
        );

        /** @var $address Address */
        static::assertCount(0, $addresses = $createdDoc->getAddresses());
    }

    /**
     * Checks if the changes are extracted correctly.
     * @covers UnitOfWork::extractChanges()
     * @return void
     */
    public function testExtractChangesFull()
    {
        static::expectException(RuntimeException::class);

        $metadata = $this->getOneMockedMetadata($className = Order::class, false);

        /** @var Order $order */
        $this->fixture->registerAsManaged(
            $order = $className::fromArray($oldData = [
                'id' => $oldOrderId = uniqid(),
                'billingAddress' => [
                    'id' => $oldAddressId = uniqid()
                ],
                'lineItems' => $lineItems = [
                    ['id' => $lineItemId1 = uniqid()],
                    ['id' => $lineItemId2 = uniqid()]
                ]
            ]),
            uniqid(),
            5
        );


        $order
            ->setId($newOrderId = uniqid())
            ->getBillingAddress()->setStreetName($streeName = uniqid());

        $order->getLineItems()->add(LineItem::fromArray(['id' => $lineItemId3 = uniqid()]));
        unset($order->getLineItems()[0]);

        $this->actionBuilderProcessor
            ->expects(static::once())
            ->method('createUpdateActions')
            ->with(
                static::isInstanceOf(ClassMetadataInterface::class),
                [
                    'id' => $newOrderId,
                    'lineItems' => [
                        0 => null,
                        2 => [
                            'id' => $lineItemId3
                        ]
                    ],
                    'billingAddress' => [
                        'streetName' => $streeName
                    ]
                ],
                $oldData,
                $order
            )
            ->willThrowException(new RuntimeException('Controlled stop.'));

        $this->fixture->scheduleSave($order);
        $this->fixture->flush();
    }

    /**
     * Checks the correct class instance.
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(UnitOfWorkInterface::class, $this->fixture);
    }

    /**
     * Checks if a managed object is registered correctly.
     * @return void
     */
    public function testRegisterAsManaged()
    {
        $this->getOneMockedMetadata(Order::class);

        static::assertCount(0, $this->fixture, 'Start count failed.');

        static::assertSame(
            $this->fixture,
            $this->fixture->registerAsManaged($order = new Order(), uniqid(), 5),
            'Fluent interface failed.'
        );

        static::assertCount(1, $this->fixture, 'The object should be saved.');

        static::assertSame(
            0,
            $this->fixture->countNewObjects(),
            'The object should not be saved as new.'
        );

        static::assertSame(
            1,
            $this->fixture->countManagedObjects(),
            'The object should be saved as managed.'
        );

        $this->fixture->registerAsManaged($order, uniqid(), 6);

        static::assertCount(1, $this->fixture, 'The object should be saved only once.');

        static::assertSame(
            1,
            $this->fixture->countManagedObjects(),
            'The object should be saved as managed only once.'
        );
    }

    /**
     * Checks if a managed object is registered correctly.
     * @return void
     */
    public function testRegisterAsManagedNew()
    {
        static::assertCount(0, $this->fixture, 'Start count failed.');

        static::assertSame(
            $this->fixture,
            $this->fixture->registerAsManaged($order = new Order()),
            'Fluent interface failed.'
        );

        static::assertCount(1, $this->fixture, 'The object should be saved.');

        static::assertSame(
            1,
            $this->fixture->countNewObjects(),
            'The object should be saved as new.'
        );

        static::assertSame(
            0,
            $this->fixture->countManagedObjects(),
            'The object should not be saved as managed.'
        );

        $this->fixture->registerAsManaged($order);

        static::assertCount(1, $this->fixture, 'The object should be saved only once.');

        static::assertSame(
            1,
            $this->fixture->countNewObjects(),
            'The object should be saved as new only once.'
        );
    }

    /**
     * Checks if the trait is implemented.
     * @dataProvider getUsedTraits
     * @param string $trait
     */
    public function testTraits(string $trait)
    {
        static::assertContains($trait, class_uses($this->fixture));
    }

    /**
     * Adds a mocked call for metadata for the given model.
     * @param string $model
     * @param bool $once Is the metadata only reguested once.
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getOneMockedMetadata(string $model, bool $once = true): PHPUnit_Framework_MockObject_MockObject
    {
        $this->documentManager
            ->expects($once ? static::once() : static::any())
            ->method('getClassMetadata')
            ->with($model)
            ->willReturn($classMetadata = static::createMock(ClassMetadataInterface::class));

        return $classMetadata;
    }
}
