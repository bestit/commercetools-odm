<?php

namespace BestIt\CommercetoolsODM\Tests\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryInterface;
use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryTrait;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use BestIt\CommercetoolsODM\Repository\ProductTypeRepository;
use BestIt\CommercetoolsODM\Repository\ProjectRepository;
use BestIt\CommercetoolsODM\Repository\ProjectRepositoryInterface;
use BestIt\CommercetoolsODM\Tests\TestTraitsTrait;
use BestIt\CTAsyncPool\PoolInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Model\ProductType\ProductType;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class ProjectRepositoryTest
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 * @version $id$
 */
class ProjectRepositoryTest extends TestCase
{
    use TestRepositoryTrait;

    /**
     * The tested class.
     * @var ProjectRepository
     */
    protected $fixture = null;

    /**
     * Returns the class name for the repository.
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return ProjectRepository::class;
    }

    /**
     * Checks the class interfaces.
     * @return void
     */
    public function testInterfaces()
    {
        static::assertInstanceOf(ObjectRepository::class, $this->fixture);
        static::assertInstanceOf(ProjectRepositoryInterface::class, $this->fixture);
    }
}
