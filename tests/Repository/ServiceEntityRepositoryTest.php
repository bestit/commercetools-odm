<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use BestIt\CommercetoolsODM\Repository\ServiceEntityRepository;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Model\Cart\Cart;
use PHPUnit\Framework\TestCase;

/**
 * Tests the service entity repository
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Repository
 */
class ServiceEntityRepositoryTest extends TestCase
{
    /**
     * Test that repository will be correct builded
     *
     * @return void
     */
    public function testConstruct()
    {
        $odm = $this->createMock(DocumentManagerInterface::class);
        $filterManager = $this->createMock(FilterManagerInterface::class);

        $odm
            ->expects(static::once())
            ->method('getClassMetadata')
            ->with(Cart::class)
            ->willReturn($meta = $this->createMock(ClassMetadataInterface::class));

        $meta
            ->expects(static::any())
            ->method('getName')
            ->willReturn(Cart::class);

        $odm
            ->expects(static::once())
            ->method('getQueryHelper')
            ->willReturn($queryHelper = new QueryHelper());

        $repository = new ServiceEntityRepository($odm, $filterManager, Cart::class);

        static::assertSame($filterManager, $repository->getFilterManager());
        static::assertSame($queryHelper, $repository->getQueryHelper());
        static::assertSame($odm, $repository->getDocumentManager());
        static::assertSame(Cart::class, $repository->getClassName());
    }

    /**
     * Test extends from default repository
     *
     * @return void
     */
    public function testExtends()
    {
        $odm = $this->createMock(DocumentManagerInterface::class);
        $filterManager = $this->createMock(FilterManagerInterface::class);

        $odm
            ->expects(static::once())
            ->method('getClassMetadata')
            ->with(Cart::class)
            ->willReturn($meta = $this->createMock(ClassMetadataInterface::class));

        $odm
            ->expects(static::once())
            ->method('getQueryHelper')
            ->willReturn($queryHelper = new QueryHelper());

        $repository = new ServiceEntityRepository($odm, $filterManager, Cart::class);

        static::assertInstanceOf(DefaultRepository::class, $repository);
    }
}
