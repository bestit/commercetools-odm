<?php

namespace BestIt\CommercetoolsODM\Tests;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderProcessorInterface;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Event\ListenersInvoker;
use BestIt\CommercetoolsODM\UnitOfWorkFactory;
use BestIt\CommercetoolsODM\UnitOfWorkFactoryInterface;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Doctrine\Common\EventManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * Class UnitOfWorkFactoryTest
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
class UnitOfWorkFactoryTest extends TestCase
{
    use TestTraitsTrait;

    /**
     * The tested class.
     * @var UnitOfWorkFactoryInterface
     */
    protected $fixture = null;

    /**
     * Returns the names of the used traits.
     * @return array
     */
    protected function getUsedTraitNames(): array
    {
        return [LoggerAwareTrait::class];
    }

    /**
     * Sets up the test.
     */
    protected function setUp()
    {
        $this->fixture = new UnitOfWorkFactory(
            $this->createMock(ActionBuilderProcessorInterface::class),
            $this->createMock(EventManager::class),
            $this->createMock(ListenersInvoker::class)
        );
    }

    /**
     * Checks the return value of the getter.
     */
    public function testGetUnitOfWorkNoLogger()
    {
        $uow = $this->fixture->getUnitOfWork($dm = $this->createMock(DocumentManagerInterface::class));

        $this->assertInstanceOf(UnitOfWorkInterface::class, $uow);
    }

    /**
     * Checks the return value of the getter.
     */
    public function testGetUnitOfWorkWithLogger()
    {
        $this->fixture->setLogger($logger = new NullLogger());

        $uow = $this->fixture->getUnitOfWork($dm = $this->createMock(DocumentManagerInterface::class));

        $this->assertInstanceOf(UnitOfWorkInterface::class, $uow);
    }

    /**
     * Checks the interface.
     * @return void
     */
    public function testInstance()
    {
        $this->assertInstanceOf(UnitOfWorkFactoryInterface::class, $this->fixture);
    }
}
