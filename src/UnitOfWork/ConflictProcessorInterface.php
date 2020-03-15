<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork;

/**
 * Handles the update conflict for a given object and merges it with the latest version.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork
 */
interface ConflictProcessorInterface
{
    /**
     * Refresh the given object with the latest version from the database.
     *
     * @param mixed $conflictingModel
     *
     * @return mixed The updated conflicting object.
     */
    public function handleConflict($conflictingModel);
}
