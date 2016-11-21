<?php

namespace BestIt\CommercetoolsODM;

use Commercetools\Commons\Helper\QueryHelper;
use Doctrine\Common\Persistence\ObjectRepository;
use UnexpectedValueException;

abstract class AbstractRepository implements ObjectRepository
{
    /**
     * The query helper for/from commercetools.
     * @var QueryHelper
     */
    private $queryHelper = null;

    /**
     * Finds an object by its primary key / identifier.
     * @param mixed $id The identifier.
     * @return mixed The object.
     */
    public function find($id)
    {
    }

    /**
     * Finds all objects in the repository.
     * @return array The objects.
     */
    public function findAll()
    {
        return $this->getQueryHelper()->getAll();
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array The objects.
     * @throws UnexpectedValueException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
    }

    /**
     * Finds a single object by a set of criteria.
     * @param array $criteria The criteria.
     * @return mixed The object.
     */
    public function findOneBy(array $criteria)
    {
    }

    /**
     * Returns the query helper for/from commercetools.
     * @return QueryHelper
     */
    protected function getQueryHelper(): QueryHelper
    {
        return $this->queryHelper;
    }

    /**
     * Sets the query helper for/from commercetools.
     * @param QueryHelper $queryHelper
     * @return AbstractRepository
     */
    protected function setQueryHelper(QueryHelper $queryHelper): AbstractRepository
    {
        $this->queryHelper = $queryHelper;

        return $this;
    }
}
