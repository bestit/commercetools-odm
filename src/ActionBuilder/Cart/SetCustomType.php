<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\CustomField\FieldContainer;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\CustomField\Command\SetCustomTypeAction;

/**
 * Builds the action to change cart custom type and fields
 *
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Cart
 * @subpackage ActionBuilder\Cart
 */
class SetCustomType extends CartActionBuilder
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
     * @param Cart $sourceObject
     * @param string $subFieldName If you work on attributes etc. this is the name of the specific attribute.
     *
     * @return AbstractAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject,
        string $subFieldName = ''
    ): array {
        $actions = [];

        // Only process if we have fields and type key
        if (isset($changedValue['fields'], $changedValue['type']['key'])) {
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

            $actions[] = $action;
        }

        return $actions;
    }
}
