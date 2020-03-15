<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Helper\DocumentManagerAwareTrait;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use function get_class;

/**
 * Handles the update conflict for a given object and merges it with the latest version.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork
 */
class ConflictProcessor implements ConflictProcessorInterface, LoggerAwareInterface
{
    use DocumentManagerAwareTrait;
    use LoggerAwareTrait;

    /**
     * The used unit of work.
     *
     * @var UnitOfWorkInterface
     */
    private $unitOfWork;

    /**
     * ConflictProcessor constructor.
     *
     * @param DocumentManagerInterface $documentManager
     */
    public function __construct(DocumentManagerInterface $documentManager)
    {
        $this->setDocumentManager($documentManager);

        $this->logger = new NullLogger();
    }

    /**
     * Refresh the given object with the latest version from the database.
     *
     * @param mixed $conflictingModel
     * @todo Add identifier from metadata.
     *
     * @return mixed The updated conflicting object.
     */
    public function handleConflict($conflictingModel)
    {
        $className = get_class($conflictingModel);
        $repo = $this->documentManager->getRepository($className);
        $newVersion = $repo->findAndCreateObject($conflictingModel->getId(), false);

        $this->logger
            ->debug(
                'Handles a conflict for the given object.',
                [
                    'class' => get_class($conflictingModel),
                    'id' => $conflictingModel->getId(),
                ]
            );

        if ($newVersion) {
            $this->logger
                ->debug(
                    'Refreshes the given object with new data after a conflict.',
                    [
                        'class' => get_class($conflictingModel),
                        'id' => $conflictingModel->getId(),
                        'newVersion' => $newVersion->getVersion(),
                        'oldVersion' => $conflictingModel->getVersion(),
                    ]
                );
            
            $this->documentManager->refresh($conflictingModel, $newVersion);
            $this->documentManager->merge($conflictingModel);
        } else {
            $this->logger
                ->debug(
                    'Resets the data after the other version was deleted and inserts it again.',
                    [
                        'class' => get_class($conflictingModel),
                        'id' => $conflictingModel->getId(),
                        'oldVersion' => $conflictingModel->getVersion(),
                    ]
                );
            
            $this->documentManager->detach($conflictingModel);
            $this->documentManager->persist($conflictingModel);
        }

        return $conflictingModel;
    }
}
