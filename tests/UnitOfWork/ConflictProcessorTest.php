<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Helper\DocumentManagerAwareTrait;
use BestIt\CommercetoolsODM\Repository\ProductRepository;
use BestIt\CommercetoolsODM\Tests\TestTraitsTrait;
use BestIt\CommercetoolsODM\UnitOfWork\ConflictProcessor;
use BestIt\CommercetoolsODM\UnitOfWork\ConflictProcessorInterface;
use Commercetools\Core\Model\Product\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function uniqid;

/**
 * Class ConflictProcessorTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\UnitOfWork
 */
class ConflictProcessorTest extends TestCase
{
    use TestTraitsTrait;

    /**
     * The injected document manager.
     *
     * @var MockObject|null|DocumentManagerInterface
     */
    private $documentManager;

    /**
     * The tested class.
     *
     * @var ConflictProcessor|null
     */
    protected $fixture;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new ConflictProcessor(
            $this->documentManager = $this->createMock(DocumentManagerInterface::class)
        );
    }

    /**
     * Checks if the processor merges the data correctly.
     *
     * @return void
     */
    public function testHandleConflictMissingNewVersion()
    {
        $product = Product::fromArray(['id' => $id = uniqid(), 'version' => 5]);

        $this->documentManager
            ->expects(static::once())
            ->method('getRepository')
            ->with(Product::class)
            ->willReturn($repo = $this->createMock(ProductRepository::class));

        $repo
            ->expects(static::once())
            ->method('findAndCreateObject')
            ->with($id)
            ->willReturn(null);

        $this->documentManager
            ->expects(static::once())
            ->method('detach')
            ->with($product);

        $this->documentManager
            ->expects(static::once())
            ->method('persist')
            ->with($product);

        static::assertSame($product, $this->fixture->handleConflict($product));
    }

    /**
     * Checks if the processor merges the data correctly.
     *
     * @return void
     */
    public function testHandleConflictSuccessful()
    {
        $product = Product::fromArray(['id' => $id = uniqid(), 'version' => 5]);

        $this->documentManager
            ->expects(static::once())
            ->method('getRepository')
            ->with(Product::class)
            ->willReturn($repo = $this->createMock(ProductRepository::class));

        $repo
            ->expects(static::once())
            ->method('findAndCreateObject')
            ->with($id)
            ->willReturn($newProduct = Product::fromArray(['id' => 'id', 'version' => 7]));

        $this->documentManager
            ->expects(static::once())
            ->method('refresh')
            ->with($product, $newProduct);

        static::assertSame($product, $this->fixture->handleConflict($product));
    }

    /**
     * Checks if the needed interface is implemented.
     *
     * @return void
     */
    public function testInterface()
    {
        static::assertInstanceOf(ConflictProcessorInterface::class, $this->fixture);
    }

    /**
     * Returns the traits which should be used?
     *
     * @return array
     */
    protected function getUsedTraitNames(): array
    {
        return [
            DocumentManagerAwareTrait::class,
        ];
    }
}
