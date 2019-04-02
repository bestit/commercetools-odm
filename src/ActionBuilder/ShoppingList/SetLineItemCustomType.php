<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\CustomField\FieldContainer;
use Commercetools\Core\Model\ShoppingList\ShoppingList;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListSetLineItemCustomTypeAction;

/**
 * Builds the action to change cart item custom fields
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ShoppingList
 */
class SetLineItemCustomType extends ShoppingListActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = 'lineItems/([^/]+)/custom$';

    /**
     * Creates the update action for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param ShoppingList $sourceObject
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

        $offset = $this->getLastFoundMatch()[1];
        $lineItemId = $sourceObject->getLineItems()->getAt($offset)->getId();

        // Only process if custom fields were changed and the line item already exists
        if ($lineItemId !== null && isset($changedValue['fields'])) {
            $actions[] = $this->createCustomTypeAction($lineItemId, $changedValue);
        }

        return $actions;
    }

    /**
     * Creates a commerce tools shopping list set line item action
     *
     * @param string $lineItemId the line item id
     * @param array $customType the data of the custom type and fields
     *
     * @return ShoppingListSetLineItemCustomTypeAction the action
     */
    private function createCustomTypeAction(string $lineItemId, array $customType): ShoppingListSetLineItemCustomTypeAction
    {
        $action = new ShoppingListSetLineItemCustomTypeAction();
        $action->setLineItemId($lineItemId);
        $action->setType(TypeReference::ofKey($customType['type']['key']));

        $container = new FieldContainer();
        foreach ($customType['fields'] as $name => $value) {
            $container->set($name, $value);
        }

        $action->setFields($container);

        return $action;
    }
}
