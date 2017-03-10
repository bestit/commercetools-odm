<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\CustomField\FieldContainer;
use Commercetools\Core\Model\ShippingMethod\ShippingMethodReference;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Carts\Command\CartSetShippingMethodAction;
use Commercetools\Core\Request\CustomField\Command\SetCustomTypeAction;

/**
 * Builds the action to change cart custom type and fields
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Cart
 * @version $id$
 */
class SetShippingMethod extends ActionBuilderAbstract
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     * @var string
     */
    protected $complexFieldFilter = 'shippingInfo/shippingMethod/(.*)';

    /**
     * For which class is this description used?
     * @var string
     */
    protected $modelClass = Cart::class;

    /**
     * Creates the update action for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Cart $sourceObject
     * @param string $subFieldName If you work on attributes etc. this is the name of the specific attribute.
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

        // Only process if we have the shipping method id
        if ($changedValue && is_string($changedValue)) {
            $action = new CartSetShippingMethodAction();
            $action->setShippingMethod(ShippingMethodReference::ofId($changedValue));
            $actions[] = $action;
        }

        return $actions;
    }
}
