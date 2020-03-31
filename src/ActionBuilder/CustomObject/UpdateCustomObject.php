<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\CustomObject;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\CustomObject\CustomObject;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\CustomObjects\CustomObjectCreateRequest;

/**
 * Updates custom objects.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\CustomObject
 */
class UpdateCustomObject extends ActionBuilderAbstract
{
    /**
     * For which class is this description used?
     *
     * @var string
     */
    protected $modelClass = CustomObject::class;

    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^value$';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     *
     * @return AbstractAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        if (empty($changedValue)) {
            return [];
        }

        return [
            CustomObjectCreateRequest::ofObject($sourceObject),
        ];
    }
}
