<?php

namespace BestIt\CommercetoolsODM\ActionBuilder;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;

/**
 * Processes the action builders for a changed object.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder
 * @subpackage ActionBuilder
 */
interface ActionBuilderProcessorInterface
{
    /**
     * Creates the update action for the gievn class and data.
     *
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     *
     * @return ActionBuilderInterface[]
     */
    public function createUpdateActions(
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array;
}
