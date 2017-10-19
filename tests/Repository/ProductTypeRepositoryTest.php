<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryInterface;
use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryTrait;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use BestIt\CommercetoolsODM\Repository\ProductTypeRepository;
use BestIt\CommercetoolsODM\Tests\TestTraitsTrait;
use BestIt\CTAsyncPool\PoolInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Model\ProductType\ProductType;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class ProductTypeRepositoryTest
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Repository
 */
class ProductTypeRepositoryTest extends TestCase
{
    use TestRepositoryTrait;
    use TestTraitsTrait;

    /**
     * The tested class.
     * @var ProductTypeRepository|null
     */
    protected $fixture;

    /**
     * Returns the class name for the repository.
     *
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return ProductTypeRepository::class;
    }

    /**
     * Returns the names of the used traits.
     *
     * @return array
     */
    protected function getUsedTraitNames(): array
    {
        return [
            ByKeySearchRepositoryTrait::class
        ];
    }

    /**
     * Checks the class interfaces.
     *
     * @return void
     */
    public function testInterfaces()
    {
        static::assertInstanceOf(ObjectRepository::class, $this->fixture);
        static::assertInstanceOf(ByKeySearchRepositoryInterface::class, $this->fixture);
    }
}
