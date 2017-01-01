<?php

namespace BestIt\CommercetoolsODM\ActionBuilder;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;

/**
 * Provides action builders for the source object and its metadata.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder
 * @version $id$
 */
interface ActionBuilderFactoryInterface
{
    /**
     * Gets the action builders for the given object and its field name.
     * @param ClassMetadataInterface $classMetadata
     * @param string $fieldName
     * @param $sourceObject
     * @return ActionBuilderInterface[]
     */
    public function getActionBuilders(ClassMetadataInterface $classMetadata, string $fieldName, $sourceObject): array;
}
