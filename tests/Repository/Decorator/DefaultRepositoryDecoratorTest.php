<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Repository\Decorator;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryInterface;
use BestIt\CommercetoolsODM\Repository\Decorator\DefaultRepositoryDecorator;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Response\ApiResponseInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use function Funct\Strings\lowerCaseFirst;
use function uniqid;

/**
 * Checks the default repository decorator.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Repository
 * @version $id$
 */
class DefaultRepositoryDecoratorTest extends TestCase
{
    /**
     * @var DefaultRepositoryDecorator
     */
    private $fixture;

    /**
     * @var ByKeySearchRepositoryInterface|PHPUnit_Framework_MockObject_MockObject The decorated original repository.
     */
    protected $originalRepository;

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
     * Mocks the original repository method.
     *
     * @param string $method The mocked method.
     * @param array $arguments The arguments for the method call of the original method.
     * @param mixed $return
     */
    private function mockOriginalRepoMethod(string $method, array $arguments = [], $return = null)
    {
        $this->originalRepository
            ->expects(static::once())
            ->method($method)
            ->with(...$arguments)
            ->willReturn(func_num_args() <= 2 ? $this->fixture : $return);
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new DefaultRepositoryDecorator(
            $this->originalRepository = $this->createMock(ByKeySearchRepositoryInterface::class)
        );
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testClearExpandAfterQuery()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [$argument = true],
            true
        );

        static::assertTrue($this->fixture->$function($argument));
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testFilter()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $arguments = [uniqid(), uniqid()]
        );

        static::assertSame($this->fixture, $this->fixture->$function(...$arguments));
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testFind()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [$productId = uniqid()],
            $return = new Product()
        );

        static::assertSame($return, $this->fixture->$function($productId));
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testFindAll()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [],
            $return = [new Product(), new Product()]
        );

        static::assertSame($return, $this->fixture->$function());
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testFindAsync()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [$productId = uniqid()],
            $return = $this->createMock(ApiResponseInterface::class)
        );

        static::assertSame($return, $this->fixture->$function($productId));
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testFindBy()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $arguments = [[uniqid()], [uniqid()], 5, 100],
            $return = [new Product(), new Product()]
        );

        static::assertSame($return, $this->fixture->$function(...$arguments));
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testFindByAsync()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $arguments = [[uniqid()], [uniqid()], 5, 100],
            $return = $this->createMock(ApiResponseInterface::class)
        );

        static::assertSame($return, $this->fixture->$function(...$arguments));
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testFindByKey()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [$productId = uniqid()],
            $return = new Product()
        );

        static::assertSame($return, $this->fixture->$function($productId));
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testFindByKeyAsync()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [$productId = uniqid()],
            $return = $this->createMock(ApiResponseInterface::class)
        );

        static::assertSame($return, $this->fixture->$function($productId));
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testFindOneBy()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $arguments = [[uniqid()]],
            $return = new Product()
        );

        static::assertSame($return, $this->fixture->$function(...$arguments));
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testFindOneByAsync()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            $arguments = [[uniqid()]],
            $return = $this->createMock(ApiResponseInterface::class)
        );

        static::assertSame($return, $this->fixture->$function(...$arguments));
    }

    /**
     * Checks if the class name by the original repo is returned.
     *
     * @return void
     */
    public function testGetClassName()
    {
        $return = uniqid();

        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [],
            $return
        );

        static::assertSame($return, $this->fixture->$function());
    }

    /**
     * Checks if the document manager is returned.
     *
     * @return void
     */
    public function testGetDocumentManager()
    {
        $return = $this->createMock(DocumentManagerInterface::class);

        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [],
            $return
        );

        static::assertSame($return, $this->fixture->$function());
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testGetExpands()
    {
        $return = [uniqid()];

        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [],
            $return
        );

        static::assertSame($return, $this->fixture->$function());
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testGetFilters()
    {
        $return = [uniqid(), uniqid()];

        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [],
            $return
        );

        static::assertSame($return, $this->fixture->$function());
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testSave()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [$argument = $return = new Product()],
            $return
        );

        static::assertSame($return, $this->fixture->$function($argument));
    }

    /**
     * Checks if the call is delegated to the original class.
     *
     * @return void
     */
    public function testSetExpands()
    {
        $this->mockOriginalRepoMethod(
            $function = $this->extractOriginalRepoMethodName(__FUNCTION__),
            [$arguments = ['productType']]
        );

        static::assertSame($this->fixture, $this->fixture->$function($arguments));
    }
}
