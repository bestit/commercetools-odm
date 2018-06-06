<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Pagination;

use BestIt\CommercetoolsODM\Pagination\FindByPaginator;
use Commercetools\Core\Model\Customer\Customer;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use function iterator_to_array;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the FindByPaginator.
 *
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Pagination
 */
class FindByPaginatorTest extends TestCase
{
    /**
     * Test that paginator works properly.
     *
     * @return void
     */
    public function testPagination()
    {
        $fixture = new FindByPaginator(
            $repository = $this->createMock(ObjectRepository::class),
            $criteria = ['TEST = TEST'],
            $pageSize = 3
        );

        $repository
            ->method('findBy')
            ->withConsecutive(
                [['TEST = TEST']],
                [['TEST = TEST', 'id > "A3"']],
                [['TEST = TEST', 'id > "B6"']]
            )
            ->willReturnOnConsecutiveCalls(
                [
                    $element1 = Customer::of()->setId('A1'),
                    $element2 = Customer::of()->setId('A2'),
                    $element3 = Customer::of()->setId('A3')
                ],
                [
                    $element4 = Customer::of()->setId('B4'),
                    $element5 = Customer::of()->setId('B5'),
                    $element6 = Customer::of()->setId('B6')
                ],
                [
                    $element7 = Customer::of()->setId('C7'),
                    $element8 = Customer::of()->setId('C8')
                ]
            );

        $elements = iterator_to_array($fixture->getIterator());

        self::assertCount(8, $elements);
        self::assertSame($element1, $elements[0]);
        self::assertSame($element2, $elements[1]);
        self::assertSame($element3, $elements[2]);
        self::assertSame($element4, $elements[3]);
        self::assertSame($element5, $elements[4]);
        self::assertSame($element6, $elements[5]);
        self::assertSame($element7, $elements[6]);
        self::assertSame($element8, $elements[7]);
    }
}
