<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\DateTimeDecorator;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\CustomField\Command\SetCustomFieldAction;
use DateTime;

/**
 * The basic builder to set a custom field.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder
 */
abstract class SetCustomFieldBuilderAbstract extends ActionBuilderAbstract
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^custom/fields/([^/]*)$';

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

        $action = SetCustomFieldAction::ofName($field);

        if ($changedValue !== null) {
            $action->setValue($changedValue);
        }

        return [$action];
    }
}
