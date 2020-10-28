<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\CustomField\FieldContainer;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Carts\Command\CartSetLineItemCustomTypeAction;

/**
 * Builds the action to change cart item custom fields
 *
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Cart
 * @subpackage ActionBuilder\Cart
 */
class SetLineItemCustomField extends CartActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = 'lineItems/([\d]*)/custom';

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

        // Only process if custom fields was changed
        if (!isset($changedValue['fields'])) {
            return $actions;
        }

        $offset = $this->getLastFoundMatch()[1];
        $lineItemId = $sourceObject->getLineItems()->getAt($offset)->getId();

        // Use changed type or old type if not changed
        $type = TypeReference::fromArray($changedValue['type'] ?? $oldData['lineItems'][$offset]['custom']['type']);

        // Do not process on added items
        if (!$lineItemId) {
            return $actions;
        }

        $action = new CartSetLineItemCustomTypeAction();
        $action->setLineItemId($lineItemId);
        $action->setType($type);
        $container = new FieldContainer();

        foreach ($changedValue['fields'] as $name => $value) {
            $container->set($name, $value);
        }

        $action->setFields($container);

        $actions[] = $action;

        return $actions;
    }
}
