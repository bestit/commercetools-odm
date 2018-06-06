<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Pagination;

use BestIt\CommercetoolsODM\RepositoryAwareTrait;
use Commercetools\Core\Model\Common\Resource;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use \Generator;
use \IteratorAggregate;
use function sprintf;
use function count;

/**
 * Paginator for object repositories.
 *
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Pagination
 */
class FindByPaginator implements IteratorAggregate
{
    use RepositoryAwareTrait;

    /**
     * Default limit for commercetools queries.
     *
     * @var int DEFAULT_PAGE_SIZE
     */
    const DEFAULT_PAGE_SIZE = 20;

    /**
     * Used filter criteria.
     *
     * @var array $criteria
     */
    private $criteria;

    /**
     * The used page size.
     *
     * @var int $pageSize
     */
    private $pageSize;

    /**
     * The last queried id.
     *
     * @var string|null
     */
    private $lastId;

    /**
     * FindByPaginator constructor.
     *
     * @param ObjectRepository $repository Repository which is used for queries.
     * @param array $criteria The filter criteria.
     * @param int $pageSize Used page size. Default value is 20.
     */
    public function __construct(
        ObjectRepository $repository,
        array $criteria = [],
        int $pageSize = self::DEFAULT_PAGE_SIZE
    ) {
        $this->setRepository($repository);
        $this->criteria = $criteria;
        $this->pageSize = $pageSize;
    }

    /**
     * Get iterator to walk all elements.
     *
     * @return Generator
     */
    public function getIterator(): Generator
    {
        do {
            $criteria = $this->criteria;

            if ($this->lastId) {
                $criteria[] = sprintf('id > "%s"', $this->lastId);
            }

            /**
             * @var Resource[] $elements
             */
            $elements = $this->repository->findBy($criteria, ['id' => 'ASC'], $this->pageSize);
            $count = count($elements);

            foreach ($elements as $element) {
                $this->lastId = $element->getId();
                yield $element;
            }
        } while ($count === $this->pageSize);
    }
}
