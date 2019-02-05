<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Order;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\DateTimeDecorator;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\CustomField\Command\SetCustomFieldAction;
use DateTime;

/**
 * Builds the action to add a custom field to an order.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Order
 * @subpackage ActionBuilder\ProductType
 */
class SetCustomField extends OrderActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = 'custom/fields/([^/]*)$';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Order $sourceObject
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
        list(, $field) = $this->getLastFoundMatch();

        if ($changedValue instanceof DateTime) {
            $changedValue = new DateTimeDecorator($changedValue);
        }

        return [SetCustomFieldAction::ofName($field)->setValue($changedValue)];
    }
}
