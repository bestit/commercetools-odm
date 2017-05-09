<?php

namespace BestIt\CommercetoolsODM\Tests;

use BestIt\CommercetoolsODM\ClientAwareTrait;
use BestIt\CommercetoolsODM\DocumentManager;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Helper\QueryHelperAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataFactory;
use BestIt\CommercetoolsODM\MetadataFactoryAwareTrait;
use BestIt\CommercetoolsODM\RepositoryFactoryInterface;
use BestIt\CommercetoolsODM\UnitOfWorkFactoryInterface;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Product\Product;
use function Funct\Strings\toUpper;
use function Funct\Strings\underscore;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerAwareTrait;

/**
 * Testing of the document manager.
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
class DocumentManagerTest extends TestCase
{
    use TestTraitsTrait;

    /**
     * The document manager.
     * @var DocumentManagerInterface
     */
    protected $fixture = null;

    /**
     * The used unit of work factory.
     * @var UnitOfWorkFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $unitOfWorkFactory = null;

    /**
     * Returns some tests for the unit of work delegations.
     * @return array
     */
    public static function getUnitOfWorkDelegations(): array
    {
        return [
            // DocumentManager-Method, UnitOfWork-Method
            ['contains'],
            ['detach'],
            ['detachDeferred', 'detachDeferred'],
            ['flush', '', false],
            ['persist', 'scheduleSave'],
            ['refresh', 'refresh'],
            ['remove', 'scheduleRemove']
        ];
    }

    /**
     * Returns the used traits.
     * @return array
     */
    public static function getUsedTraitNames(): array
    {
        return [
            ClientAwareTrait::class,
            LoggerAwareTrait::class,
            MetadataFactoryAwareTrait::class,
            QueryHelperAwareTrait::class
        ];
    }

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new DocumentManager(
            $this->createMock(ClassMetadataFactory::class),
            $this->createMock(Client::class),
            $this->createMock(QueryHelper::class),
            $this->createMock(RepositoryFactoryInterface::class),
            $this->unitOfWorkFactory = $this->CreateMock(UnitOfWorkFactoryInterface::class)
        );
    }

    /**
     * Checks the constants of the doc manager.
     * @return void
     */
    public function testConstants()
    {
        $map = [
            'Create',
            'DeleteByContainerAndKey',
            'DeleteById',
            'DeleteByKey',
            'FindByContainerAndKey',
            'FindById',
            'FindByKey',
            'FindByCustomerId',
            'Query',
            'UpdateById',
            'UpdateByKey',
        ];

        array_walk($map, function (string $constValue) {
            $constName = toUpper(underscore($constValue));

            $this->assertSame(
                $constValue,
                constant(sprintf('%s::REQUEST_TYPE_%s', DocumentManager::class, $constName))
            );
        });
    }

    /**
     * Checks the return for the unit of work.
     * @covers DocumentManager::getUnitOfWork()
     * @return void
     */
    public function testGetUnitOfWork()
    {
        $unitOfWorkMock = $this->createMock(UnitOfWorkInterface::class);

        $this->unitOfWorkFactory
            ->method('getUnitOfWork')
            ->with($this->fixture)
            ->willReturn($unitOfWorkMock);

        $this->assertSame(
            $unitOfWorkMock,
            $first = $this->fixture->getUnitOfWork(),
            'Return was wrong.'
        );

        $this->assertSame($first, $this->fixture->getUnitOfWork(), 'Wrong instance.');
    }

    /**
     * Checks the interfaces for the document manager.
     * @return void
     */
    public function testInterfaces()
    {
        $this->assertInstanceOf(DocumentManagerInterface::class, $this->fixture);
    }
    
    /**
     * Checks if the unit of work is called correctly.
     * @dataProvider getUnitOfWorkDelegations
     * @param string $documentManagerMethod
     * @param string $unitOfWorkMethod
     * @param bool $withObject
     */
    public function testUnitOfWorkDelegation(
        string $documentManagerMethod,
        string $unitOfWorkMethod = '',
        bool $withObject = true
    ) {
        $unitOfWorkMock = $this->createMock(UnitOfWorkInterface::class);

        if ($withObject) {
            $mock = new Product();

            $unitOfWorkMock
                ->expects($this->once())
                ->method($unitOfWorkMethod ?: $documentManagerMethod)
                ->with($mock);
        } else {
            $unitOfWorkMock
                ->expects($this->once())
                ->method($unitOfWorkMethod ?: $documentManagerMethod);
        }

        $this->unitOfWorkFactory
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $this->fixture->{$documentManagerMethod}($mock ?? null);
    }
}
