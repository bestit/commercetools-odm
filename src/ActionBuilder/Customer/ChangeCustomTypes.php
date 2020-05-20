<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\DateTimeDecorator;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Model\CustomField\FieldContainer;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\CustomField\Command\SetCustomTypeAction;
use DateTime;

/**
 * Builds the action to change customer custom type and fields.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Customer
 * @subpackage ActionBuilder\Customer
 */
class ChangeCustomTypes extends CustomerActionBuilder
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
     * @param Customer $sourceObject
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
        $fields = array_filter($fields);

        foreach ($fields as $name => $value) {
            if ($value instanceof DateTime) {
                $value = new DateTimeDecorator($value);
            }

            $container->set($name, $value);
        }

        $action->setFields($container);

        return [
            $action,
        ];
    }
}
