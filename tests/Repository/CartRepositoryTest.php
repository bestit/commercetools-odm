<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Repository;

use BestIt\CTAsyncPool\PoolInterface;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Repository\CartRepository;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Request\Carts\CartUpdateRequest;
use Commercetools\Core\Request\Carts\Command\CartRecalculateAction;
use Commercetools\Core\Response\ApiResponseInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class CartRepositoryTest.
 *
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Repository
 */
class CartRepositoryTest extends TestCase
{
    use TestRepositoryTrait;

    /**
     * Returns the class name for the repository.
     *
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return CartRepository::class;
    }

    /**
     * Test the recalculate cart function.
     *
     * @return void
     */
    public function testRecalculateCartWithSuccess()
    {
        /** @var CartRepository $fixture */
        $fixture = new CartRepository(
            $this->createMock(ClassMetadataInterface::class),
            $documentManager = $this->createMock(DocumentManagerInterface::class),
            $this->createMock(QueryHelper::class),
            $this->createMock(FilterManagerInterface::class),
            $this->createMock(PoolInterface::class)
        );

        $id = (string) random_int(1000, 9999);
        $version = random_int(1000, 9999);

        $cart = Cart::of()
            ->setId($id)
            ->setVersion($version);

        $documentManager
            ->method('getClient')
            ->willReturn($client = $this->createMock(Client::class));

        $response = $this->createMock(ApiResponseInterface::class);

        $response
            ->method('toArray')
            ->willReturn($cart->toArray());

        $client
            ->expects(self::once())
            ->method('execute')
            ->with(self::callback(function (CartUpdateRequest $request) use ($id, $version) {
                self::assertSame($id, $request->getId());
                self::assertSame($version, $request->getVersion());
                self::assertArrayHasKey(0, $request->getActions());
                self::assertInstanceOf(CartRecalculateAction::class, $request->getActions()[0]);

                return true;
            }))
            ->willReturn($response);

        $cart = $this->createMock(Cart::class);

        $cart
            ->method('__call')
            ->willReturnCallback(
                function (string $name) use ($id, $version) {
                    switch ($name) {
                        case 'getId':
                            return $id;
                            break;
                        case 'getVersion':
                            return $version;
                            break;
                        default:
                            return null;
                            break;
                    }
                }
            );

        $documentManager
            ->expects(static::once())
            ->method('getUnitOfWork')
            ->willReturn($uow = $this->createMock(UnitOfWorkInterface::class));

        $uow
            ->expects(static::once())
            ->method('registerAsManaged')
            ->with(static::isInstanceOf(Cart::class), $id, $version);

        self::assertInstanceOf(Cart::class, $fixture->recalculateCart($cart));
    }

    /**
     * Test the exception of the recalculate cart function.
     *
     * @return void
     */
    public function testRecalculateCartWithoutSuccess()
    {
        /** @var CartRepository $fixture */
        $fixture = new CartRepository(
            $this->createMock(ClassMetadataInterface::class),
            $documentManager = $this->createMock(DocumentManagerInterface::class),
            $this->createMock(QueryHelper::class),
            $this->createMock(FilterManagerInterface::class),
            $this->createMock(PoolInterface::class)
        );

        $id = (string) random_int(1000, 9999);
        $version = random_int(1000, 9999);

        $documentManager
            ->method('getClient')
            ->willReturn($client = $this->createMock(Client::class));

        $response = $this->createMock(ApiResponseInterface::class);

        $response
            ->method('toArray')
            ->willReturn([]);

        $client
            ->expects(self::once())
            ->method('execute')
            ->with(self::callback(function (CartUpdateRequest $request) use ($id, $version) {
                self::assertSame($id, $request->getId());
                self::assertSame($version, $request->getVersion());
                self::assertArrayHasKey(0, $request->getActions());
                self::assertInstanceOf(CartRecalculateAction::class, $request->getActions()[0]);

                return true;
            }))
            ->willReturn($response);

        $cart = $this->createMock(Cart::class);

        $cart
            ->method('__call')
            ->willReturnCallback(
                function (string $name) use ($id, $version) {
                    switch ($name) {
                        case 'getId':
                            $result = $id;
                            break;
                        case 'getVersion':
                            $result = $version;
                            break;
                        default:
                            $result = null;
                            break;
                    }

                    return $result;
                }
            );

        $this->expectException(RuntimeException::class);
        $fixture->recalculateCart($cart);
    }
}
