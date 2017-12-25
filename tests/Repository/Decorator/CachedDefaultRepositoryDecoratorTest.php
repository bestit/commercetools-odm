<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Repository\Decorator;

use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryInterface;
use BestIt\CommercetoolsODM\Repository\Decorator\CachedDefaultRepositoryDecorator;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Response\ApiResponseInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use function Funct\Strings\lowerCaseFirst;

/**
 * Class CachedDefaultRepositoryDecoratorTest.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Repository\Decorator
 */
class CachedDefaultRepositoryDecoratorTest extends TestCase
{
    /**
     * @var CacheItemPoolInterface|PHPUnit_Framework_MockObject_MockObject Thhe used cache.
     */
    private $cache;

    /**
     * @var int The cache time in seconds.
     */
    private $cacheTTL;

    /**
     * @var CachedDefaultRepositoryDecorator
     */
    private $fixture;

    /**
     * @var ObjectRepository|PHPUnit_Framework_MockObject_MockObject The decorated original repository.
     */
    private $originalRepository;

    /**
     * Returns the function name from the given test function name.
     *
     * @param string $function The test class function name.
     * @return string
     */
    private function extractOriginalRepoMethodName(string $function): string
    {
        return lowerCaseFirst(substr($function, 4));
    }

    /**
     * Returns false and bool to switch the cached and uncached mode.
     *
     * @return array
     */
    public function getCacheMarkers(): array
    {
        return [
            [false],
            [true]
        ];
    }

    /**
     * Mocks the original repository method with a cache proxy.
     *
     * @param string $method The mocked method.
     * @param bool $isCached
     * @param mixed $return
     * @param array $arguments The arguments for the method call of the original method.
     */
    private function mockOriginalRepoMethod(string $method, bool $isCached, $return, array $arguments = [])
    {
        $this->cache
            ->expects(static::once())
            ->method('getItem')
            ->with(sha1($method . '|' . serialize([]) . '|' . serialize([]) . '|' . serialize($arguments)))
            ->willReturn($cacheItem = $this->createMock(CacheItemInterface::class));

        $cacheItem
            ->expects(static::once())
            ->method('get')
            ->willReturn($return);

        $cacheItem
            ->expects(static::once())
            ->method('isHit')
            ->willReturn($isCached);

        if (!$isCached) {
            $cacheItem
                ->expects(static::once())
                ->method('set')
                ->with($return)
                ->willReturnSelf();

            $cacheItem
                ->expects(static::once())
                ->method('expiresAfter')
                ->with($this->cacheTTL)
                ->willReturnSelf();

            $this->cache
                ->expects(static::once())
                ->method('save')
                ->with($cacheItem);

            $this->originalRepository
                ->expects(static::once())
                ->method($method)
                ->with(...$arguments)
                ->willReturn($return);
        }
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new CachedDefaultRepositoryDecorator(
            $this->cache = $this->createMock(CacheItemPoolInterface::class),
            $this->originalRepository = $this->createMock(ByKeySearchRepositoryInterface::class),
            $this->cacheTTL = mt_rand(1, 1000)
        );
    }

    /**
     * Checks if the call to the original class is cached or not and called directly.
     *
     * @dataProvider getCacheMarkers
     * @param bool $isCached
     * @return void
     */
    public function testFind(bool $isCached)
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $isCached,
            $return = new Product(),
            [$productId = uniqid()]
        );

        static::assertSame($return, $this->fixture->$function($productId));
    }

    /**
     * Checks if the call to the original class is cached or not and called directly.
     *
     * @dataProvider getCacheMarkers
     * @param bool $isCached
     * @return void
     */
    public function testFindAll(bool $isCached)
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $isCached,
            $return = [new Product(), new Product()],
            []
        );

        static::assertSame($return, $this->fixture->$function());
    }

    /**
     * Checks if the call to the original class is cached or not and called directly.
     *
     * @dataProvider getCacheMarkers
     * @param bool $isCached
     * @return void
     * @todo Needs to be fixed.
     */
    public function testFindAsync(bool $isCached)
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $isCached,
            $return = $this->createMock(ApiResponseInterface::class),
            [$productId = uniqid()]
        );

        static::assertSame($return, $this->fixture->$function($productId));
    }

    /**
     * Checks if the call to the original class is cached or not and called directly.
     *
     * @dataProvider getCacheMarkers
     * @param bool $isCached
     * @return void
     */
    public function testFindBy(bool $isCached)
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $isCached,
            $return = [new Product(), new Product()],
            $arguments = [[uniqid()], [uniqid()], 5, 100]
        );

        static::assertSame($return, $this->fixture->$function(...$arguments));
    }

    /**
     * Checks if the call to the original class is cached or not and called directly.
     *
     * @dataProvider getCacheMarkers
     * @param bool $isCached
     * @return void
     * @todo Needs to be fixed.
     */
    public function testFindByAsync(bool $isCached)
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $isCached,
            $return = $this->createMock(ApiResponseInterface::class),
            $arguments = [[uniqid()], [uniqid()], 5, 100]
        );

        static::assertSame($return, $this->fixture->$function(...$arguments));
    }

    /**
     * Checks if the call to the original class is cached or not and called directly.
     *
     * @dataProvider getCacheMarkers
     * @param bool $isCached
     * @return void
     */
    public function testFindByKey(bool $isCached)
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $isCached,
            $return = new Product(),
            [$productId = uniqid()]
        );

        static::assertSame($return, $this->fixture->$function($productId));
    }

    /**
     * Checks if the call to the original class is cached or not and called directly.
     *
     * @dataProvider getCacheMarkers
     * @param bool $isCached
     * @return void
     * @todo Needs to be fixed.
     */
    public function testFindByKeyAsync(bool $isCached)
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $isCached,
            $return = $this->createMock(ApiResponseInterface::class),
            [$productId = uniqid()]
        );

        static::assertSame($return, $this->fixture->$function($productId));
    }

    /**
     * Checks if the call to the original class is cached or not and called directly.
     *
     * @dataProvider getCacheMarkers
     * @param bool $isCached
     * @return void
     */
    public function testFindOneBy(bool $isCached)
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $isCached,
            $return = new Product(),
            $arguments = [[uniqid()]]
        );

        static::assertSame($return, $this->fixture->$function(...$arguments));
    }

    /**
     * Checks if the call to the original class is cached or not and called directly.
     *
     * @dataProvider getCacheMarkers
     * @param bool $isCached
     * @return void
     * @todo Needs to be fixed.
     */
    public function testFindOneByAsync(bool $isCached)
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $isCached,
            $return = $this->createMock(ApiResponseInterface::class),
            $arguments = [[uniqid()]]
        );

        static::assertSame($return, $this->fixture->$function(...$arguments));
    }
}
