<?php

namespace BestIt\CommercetoolsODM\Tests\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use BestIt\CTAsyncPool\PoolInterface;
use Commercetools\Commons\Helper\QueryHelper;
use PHPUnit_Framework_Exception;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Helps test the repository.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Tests\Repository
 * @version $id$
 */
trait TestRepositoryTrait
{
    /**
     * The used document manager.
     * @var DocumentManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $documentManager = null;

    /**The used repository class.
     * @var string
     */
    protected $repositoryClass = '';

    /**
     * The used repository class.
     * @var ObjectRepository
     */
    protected $fixture = null;

    /**
     * Returns a test double for the specified class.
     * @param string $originalClassName
     * @return PHPUnit_Framework_MockObject_MockObject
     * @throws PHPUnit_Framework_Exception
     * @since Method available since Release 5.4.0
     */
    abstract protected function createMock($originalClassName);

    /**
     * Returns the class name for the repository.
     * @return string
     */
    abstract protected function getRepositoryClass(): string;

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $repoClass = $this->getRepositoryClass();

        $this->fixture = new $repoClass(
            $this->createMock(ClassMetadataInterface::class),
            $this->documentManager = $this->createMock(DocumentManagerInterface::class),
            $this->createMock(QueryHelper::class),
            $this->createMock(FilterManagerInterface::class),
            $this->createMock(PoolInterface::class)
        );
    }

    /**
     * Checks the used interfaces.
     * @return void
     */
    public function testInterfaces()
    {
        static::assertInstanceOf(ObjectRepository::class, $this->fixture);
    }
}
