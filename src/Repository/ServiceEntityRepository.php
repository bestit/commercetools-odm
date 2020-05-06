<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;
use BestIt\CommercetoolsODM\Model\DefaultRepository;

/**
 * Optional DefaultRepository base class with a simplified constructor (for autowiring).
 *
 * To use in your class, inject the "document manager" and "filter manager" service and call
 * the parent constructor. For example:
 *
 * class YourEntityRepository extends ServiceEntityRepository
 * {
 *     public function __construct(DocumentManagerInterface $documentManager, FilterManagerInterface $filterManager)
 *     {
 *         parent::__construct($documentManager, $filterManager, YourEntity::class);
 *     }
 * }
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository
 */
class ServiceEntityRepository extends DefaultRepository
{
    /**
     * ServiceEntityRepository constructor.
     *
     * @param DocumentManagerInterface $documentManager
     * @param FilterManagerInterface $filterManager
     * @param string $entityClass
     */
    public function __construct(
        DocumentManagerInterface $documentManager,
        FilterManagerInterface $filterManager,
        string $entityClass
    ) {
        $this->setFilterManager($filterManager);
        $metadata = $documentManager->getClassMetadata($entityClass);

        parent::__construct(
            $metadata,
            $documentManager,
            $documentManager->getQueryHelper(),
            $documentManager->getGeneratorQueryHelper(),
            $this->getFilterManager()
        );
    }
}
