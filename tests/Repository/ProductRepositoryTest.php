<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\Repository\ProductRepository;
use BestIt\CommercetoolsODM\Repository\ProductRepositoryInterface;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\ProductImageUploadRequest;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SplFileInfo;
use function file_get_contents;

/**
 * Test ProductRepository.
 *
 * @author b3nl <code@b3nl.de>
 * @package BestIt\CommercetoolsODM\Tests\Repository
 */
class ProductRepositoryTest extends TestCase
{
    use TestRepositoryTrait {
        TestRepositoryTrait::testInterfaces as testInterfacesInTrait;
    }

    /**
     * The used repository class.
     *
     * @var ProductRepository|null
     */
    protected $fixture = null;

    /**
     * Returns the class name for the repository.
     *
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return ProductRepository::class;
    }

    /**
     * Checks that the image upload uses the correct mime type for the given file.
     *
     * @throws ResponseException
     *
     * @return void
     */
    public function testAddImageToProductWithMimeType()
    {
        static::expectException(RuntimeException::class);
        static::expectExceptionMessage($abortMessage = 'Skip the rest.');

        $fileInfo = new SplFileInfo(__DIR__ . '/ProductRepository/_fixtures/15743_13579481-832.jpg');

        $this->fixture->setDocumentManager(
            $documentManager = $this->createMock(DocumentManagerInterface::class)
        );

        $documentManager
            ->expects(static::once())
            ->method('getClient')
            ->willReturn($client = $this->createMock(Client::class));

        $client
            ->expects(static::once())
            ->method('execute')
            ->with(
                static::callback(function (ProductImageUploadRequest $request) use ($fileInfo) {
                    $httpRequest = $request->httpRequest();

                    static::assertSame(
                        file_get_contents($fileInfo->getRealPath()),
                        $httpRequest->getBody()->getContents(),
                        'The file content of the image was not given'
                    );

                    static::assertSame(
                        'image/jpeg',
                        $httpRequest->getHeaderLine('Content-Type'),
                        'Wrong mime type of the image.'
                    );


                    return true;
                })
            )
            ->willThrowException(new RuntimeException($abortMessage));

        $this->fixture->addImageToProduct(
            $fileInfo,
            $this->createMock(Product::class)
        );
    }

    /**
     * Checks the required interfaces.
     *
     * @return void
     */
    public function testInterfaces()
    {
        static::assertInstanceOf(ProductRepositoryInterface::class, $this->fixture);
    }
}
