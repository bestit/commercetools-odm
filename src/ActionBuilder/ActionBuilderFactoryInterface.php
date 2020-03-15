<?php

namespace BestIt\CommercetoolsODM\ActionBuilder;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;

/**
 * Provides action builders for the source object and its metadata.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder
 * @subpackage ActionBuilder
 */
interface ActionBuilderFactoryInterface
{
    /**
     * Gets the action builders for the given object and its field name.
     *
     * @param ClassMetadataInterface $classMetadata
     * @param string $fieldPath The hierarchical path of the fields.
     * @param mixed $sourceObject
     *
     * @return ActionBuilderInterface[]
     */
    public function getActionBuilders(ClassMetadataInterface $classMetadata, string $fieldPath, $sourceObject): array;
}
