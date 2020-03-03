<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Category;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Model\CustomField\FieldContainer;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\CustomField\Command\SetCustomTypeAction;

/**
 * Builds the action to change category custom type and fields.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Category
 * @subpackage ActionBuilder\Category
 */
class ChangeCustomTypes extends CategoryActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $fieldName = 'custom';

    /**
     * Creates the update action for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Category $sourceObject
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
        if (!isset($changedValue['fields'], $changedValue['type']['key'])) {
            return [];
        }

        $action = new SetCustomTypeAction();
        $action->setType(TypeReference::ofKey($changedValue['type']['key']));

        $container = new FieldContainer();
        $fields = array_merge(
            $sourceObject->getCustom()->getFields()->toArray(),
            $changedValue['fields']
        );

        foreach ($fields as $name => $value) {
            $container->set($name, $value);
        }

        $action->setFields($container);

        return [
            $action,
        ];
    }
}
