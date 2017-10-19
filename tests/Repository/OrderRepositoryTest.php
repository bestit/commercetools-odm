<?php

namespace BestIt\CommercetoolsODM\Tests\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use BestIt\CommercetoolsODM\Repository\OrderRepository;
use BestIt\CommercetoolsODM\Repository\OrderRepositoryInterface;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use BestIt\CTAsyncPool\PoolInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Request\Orders\OrderCreateFromCartRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderRepositoryTest
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 */
class OrderRepositoryTest extends TestCase
{
    use TestRepositoryTrait;

    /**
     * The tested class.
     * @var OrderRepositoryInterface
     */
    protected $fixture = null;

    /**
     * Returns the class name for the repository.
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return OrderRepository::class;
    }

    /**
     * Checks if a cart is created.
     * @return void
     */
    public function testCreateFromCart()
    {
        $cart = new Cart();
        $order = new Order();
        $this->fixture = $this->getMockBuilder(OrderRepository::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['processQuery'])
            ->setConstructorArgs([
                $metadata = static::createMock(ClassMetadataInterface::class),
                $this->documentManager = static::createMock(DocumentManagerInterface::class),
                static::createMock(QueryHelper::class),
                static::createMock(FilterManagerInterface::class),
                static::createMock(PoolInterface::class)
            ])
            ->getMock();

        $cart
            ->setId($cartId = uniqid())
            ->setVersion($cartVersion = mt_rand(1, 10000));

        $metadata
            ->expects(static::once())
            ->method('getName')
            ->willReturn(Order::class);

        $order
            ->setId($orderId = uniqid())
            ->setVersion($orderVersion = mt_rand(1, 10000));

        $this->documentManager
            ->expects(static::once())
            ->method('createRequest')
            ->with(
                Order::class,
                OrderCreateFromCartRequest::class,
                $cartId,
                $cartVersion
            )
            ->willReturn($request = OrderCreateFromCartRequest::ofCartIdAndVersion($cartId, $cartVersion));

        $this->documentManager
            ->expects(static::once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWork = static::createMock(UnitOfWorkInterface::class));

        $this->fixture
            ->expects(static::once())
            ->method('processQuery')
            ->with($request)
            ->willReturn([$order]);

        $unitOfWork
            ->expects(static::once())
            ->method('registerAsManaged')
            ->with($order, $orderId, $orderVersion);

        static::assertSame($order, $this->fixture->createFromCart($cart));
    }

    /**
     * Checks the used interfaces.
     * @return void
     */
    public function testInterfaces()
    {
        static::assertInstanceOf(OrderRepositoryInterface::class, $this->fixture);
    }

    /**
     * Checks the parent class.
     * @return void
     */
    public function testParent()
    {
        static::assertInstanceOf(DefaultRepository::class, $this->fixture);
    }

    /**
     * Checks if the order will be saved.
     * @param bool $direct
     */
    public function testSave(bool $direct = true)
    {
        $order = new Order();

        $this->documentManager
            ->expects(static::once())
            ->method('persist')
            ->with($order);

        $this->documentManager
            ->expects($direct ? static::once() : static::never())
            ->method('flush');

        $this->fixture->save($order, $direct);
    }

    /**
     * Checks if the save is not flushed.
     * @return void
     */
    public function testSaveIndirect()
    {
        $this->testSave(false);
    }
}
