<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Repository;

use BestIt\CommercetoolsODM\Model\DefaultRepository;
use BestIt\CommercetoolsODM\Repository\ProductTypeRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductTypeRepositoryTest
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Repository
 */
class ProductTypeRepositoryTest extends TestCase
{
    use TestRepositoryTrait;

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
     * Checks the class interfaces.
     *
     * @return void
     */
    public function testInterfaces()
    {
        static::assertInstanceOf(DefaultRepository::class, $this->fixture);
    }
}
