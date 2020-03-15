<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork;

use Countable;

/**
 * Checks if a model is changed and provides a consumer with changed data.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork
 */
interface ChangeManagerInterface extends Countable
{
    /**
     * Is the given mocdel managed with this object?
     *
     * @param mixed $model
     *
     * @return bool
     */
    public function contains($model): bool;

    /**
     * Remove the status of the given model from this object.
     *
     * @param mixed $model
     *
     * @return void
     */
    public function detach($model);

    /**
     * Returns the changes for the given model.
     *
     * @param mixed $model
     *
     * @return array
     */
    public function getChanges($model): array;

    /**
     * Returns the original status for the given model.
     *
     * @param mixed $model
     *
     * @return array
     */
    public function getOriginalStatus($model): array;

    /**
     * Was the given model changed?
     *
     * This method checks if there is a managed original status and checks its hash against the given model.
     *
     * @param mixed $model
     *
     * @return bool
     */
    public function isChanged($model): bool;

    /**
     * Registers the data status of the given model with this manager.
     *
     * @param mixed $model
     *
     * @return void
     */
    public function registerStatus($model);
}
