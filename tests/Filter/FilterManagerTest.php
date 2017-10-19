<?php

namespace BestIt\CommercetoolsODM\Tests\Filter;

use BestIt\CommercetoolsODM\Exception\FilterException;
use BestIt\CommercetoolsODM\Filter\FilterInterface;
use BestIt\CommercetoolsODM\Filter\FilterManager;
use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;
use Commercetools\Core\Request\AbstractApiRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class FilterManagerTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Filter
 */
class FilterManagerTest extends TestCase
{
    /**
     * The filter manager
     *
     * @var FilterManager
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->fixture = new FilterManager();
    }

    /**
     * Test that manager implement the interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        static::assertInstanceOf(FilterManagerInterface::class, $this->fixture);
    }

    /**
     * Test add and get filter
     *
     * @return void
     */
    public function testAddFilter()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter
            ->expects(static::once())
            ->method('getKey')
            ->willReturn('foo');

        $this->fixture->add($filter);
        static::assertSame($filter, $this->fixture->all()['foo']);
    }

    /**
     * Test apply filter
     *
     * @return void
     */
    public function testApply()
    {
        $request = $this->createMock(AbstractApiRequest::class);

        $filter = $this->createMock(FilterInterface::class);
        $filter
            ->expects(static::once())
            ->method('getKey')
            ->willReturn('foo');

        $filter
            ->expects(static::once())
            ->method('apply')
            ->with($request);

        $this->fixture->add($filter);

        $this->fixture->apply('foo', $request);
    }

    /**
     * Test that filter not found
     *
     * @return void
     */
    public function testFilterNotFound()
    {
        $this->expectException(FilterException::class);

        $request = $this->createMock(AbstractApiRequest::class);

        $filter = $this->createMock(FilterInterface::class);
        $filter
            ->expects(static::once())
            ->method('getKey')
            ->willReturn('foo');

        $filter
            ->expects(static::never())
            ->method('apply');

        $this->fixture->add($filter);

        $this->fixture->apply('bar', $request);
    }
}
