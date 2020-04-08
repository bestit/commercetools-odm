<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Pagination;

use function array_merge;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Pagination\FindByPaginator;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use Commercetools\Core\Model\Customer\Customer;
use PHPUnit\Framework\TestCase;
use function iterator_to_array;

/**
 * Tests for the FindByPaginator.
 *
 * @package BestIt\CommercetoolsODM\Tests\Pagination
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 */
class FindByPaginatorTest extends TestCase
{
    /**
     * Test that paginator works properly and detaches its elements.
     *
     * @param bool $withDetach
     *
     * @return void
     */
    public function testPagination(bool $withDetach = true)
    {
        $fixture = new FindByPaginator(
            $repository = $this->createMock(ObjectRepository::class),
            $withDetach,
            3
        );

        $repository
            ->method('getDocumentManager')
            ->willReturn($documentManager = $this->createMock(DocumentManagerInterface::class));

        $repository
            ->method('findBy')
            ->withConsecutive(
                [$baseCriteria = ['TEST = TEST'], array_merge(['id' => 'ASC'], $sorting = ['orderHint' => 'ASC'])],
                [['TEST = TEST', 'id > "A3"'], array_merge(['id' => 'ASC'], $sorting = ['orderHint' => 'ASC'])],
                [['TEST = TEST', 'id > "B6"'], array_merge(['id' => 'ASC'], $sorting = ['orderHint' => 'ASC'])]
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

        if ($withDetach) {
            $documentManager
                ->expects(static::exactly(8))
                ->method('detach')
                ->withConsecutive(
                    [$element1],
                    [$element2],
                    [$element3],
                    [$element4],
                    [$element5],
                    [$element6],
                    [$element7],
                    [$element8]
                );
        } else {
            $documentManager
                ->expects(static::never())
                ->method('detach');
        }

        $elements = iterator_to_array($fixture->getIterator($baseCriteria, $sorting));

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

    /**
     * Test that paginator works properly and does not detach its elements.
     *
     * @return void
     */
    public function testPaginationWithoutDetach()
    {
        $this->testPagination(false);
    }
}
