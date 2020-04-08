<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Pagination;

use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use BestIt\CommercetoolsODM\RepositoryAwareTrait;
use Commercetools\Core\Model\Common\Resource;
use IteratorAggregate;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Traversable;
use function count;
use function sprintf;

/**
 * Paginator for object repositories.
 *
 * @package BestIt\CommercetoolsODM\Pagination
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 */
class FindByPaginator implements IteratorAggregate, LoggerAwareInterface
{
    use LoggerAwareTrait;
    use RepositoryAwareTrait;

    /**
     * Default limit for commercetools queries.
     *
     * @internal
     * @var int
     */
    const DEFAULT_PAGE_SIZE = 500;
    /**
     * The default sorting for the query.
     *
     * @internal
     * @var array
     */
    const DEFAULT_SORTING = ['id' => 'ASC'];
    /**
     * The used page size.
     *
     * @var int $pageSize
     */
    private $pageSize;

    /**
     * Should the element be detached from the unit of work at once?
     *
     * @var bool
     */
    private $withDetach;

    /**
     * FindByPaginator constructor.
     *
     * @param ObjectRepository $repository Repository which is used for queries.
     * @param bool $withDetach Should the element be detached from the unit of work at once?
     * @param int $pageSize Used page size. Default value is 20.
     */
    public function __construct(
        ObjectRepository $repository,
        bool $withDetach = true,
        int $pageSize = self::DEFAULT_PAGE_SIZE
    ) {
        $this->logger = new NullLogger();
        $this->repository = $repository;
        $this->pageSize = $pageSize;
        $this->withDetach = $withDetach;
    }

    /**
     * Get iterator to walk all elements.
     *
     * @param array $baseCriteria
     *
     * @param array $sorting
     * @return Traversable
     */
    public function getIterator(array $baseCriteria = [], array $sorting = []): Traversable
    {
        $documentManager = $this->repository->getDocumentManager();
        $lastId = '';

        do {
            $criteria = $baseCriteria;

            if ($lastId) {
                $criteria[] = sprintf('id > "%s"', $lastId);
            }

            /** @var Resource[] $elements */
            $elements = $this->repository->findBy(
                $criteria,
                array_merge(static::DEFAULT_SORTING, $sorting),
                $this->pageSize
            );

            $count = count($elements);

            foreach ($elements as $element) {
                $lastId = $element->getId();

                if ($this->withDetach) {
                    $documentManager->detach($element);
                }

                yield $element;
            }
        } while ($count === $this->pageSize);
    }
}
