<?php

namespace BestIt\CommercetoolsODM\Tests;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderProcessorInterface;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Event\ListenersInvoker;
use BestIt\CommercetoolsODM\Mapping\Annotations\Field;
use BestIt\CommercetoolsODM\Mapping\ClassMetadata;
use BestIt\CommercetoolsODM\Tests\UnitOfWork\TestCustomEntity;
use BestIt\CommercetoolsODM\UnitOfWork;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Commercetools\Core\Model\Common\Address;
use Commercetools\Core\Model\Common\AddressCollection;
use Commercetools\Core\Model\Customer\Customer;
use Doctrine\Common\EventManager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionClass;

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
     * @var DocumentManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $documentManager = null;

    /**
     * The fixture.
     * @var UnitOfWork
     */
    private $fixture = null;

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new UnitOfWork(
            static::createMock(ActionBuilderProcessorInterface::class),
            $this->documentManager = static::createMock(DocumentManagerInterface::class),
            static::createMock(EventManager::class),
            static::createMock(ListenersInvoker::class)
        );
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
     * Checks the correct class instance.
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(UnitOfWorkInterface::class, $this->fixture);
    }
}
