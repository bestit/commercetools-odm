<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeChangeLabelAction;

/**
 * ActionBuilder to change a label of a product type.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ProductType
 */
class ChangeAttributeLabel extends ProductTypeActionBuilder
{
    /**
     * @var string The field name.
     */
    protected $fieldName = 'attributes';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param ProductType $sourceObject
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
        $oldLabels = [];
        foreach ($oldData['attributes'] as $attribute) {
            $oldLabels[$attribute['name']] = $attribute['label'];
        }

        $actions = [];
        foreach ($sourceObject->getAttributes()->toArray() as $attribute) {
            $attributeName = $attribute['name'];

            if (!array_key_exists($attributeName, $oldLabels)) {
                continue;
            }

            if ($attribute['label'] != $oldLabels[$attributeName]) {
                $actions[] = ProductTypeChangeLabelAction::ofAttributeNameAndLabel(
                    $attributeName,
                    LocalizedString::fromArray(array_filter($attribute['label']))
                );
            }
        }

        return $actions;
    }
}
