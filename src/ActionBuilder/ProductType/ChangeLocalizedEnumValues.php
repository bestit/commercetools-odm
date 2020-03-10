<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeAddLocalizedEnumValueAction;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeChangeLocalizedEnumLabelAction;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeRemoveEnumValuesAction;

/**
 * ActionBuilder to add, remove and modify localized enum values
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ProductType
 */
class ChangeLocalizedEnumValues extends ProductTypeActionBuilder
{
    /**
     * @var string The field name.
     */
    protected $complexFieldFilter = '^attributes\/(\w+)\/type\/values$';

    /**
     * Add new values / new values are items _without_ known key
     *
     * @param array $changeValues
     * @param array $oldValues
     * @param string $attributeName
     *
     * @return array
     */
    private function addedValues(array $changeValues, array $oldValues, string $attributeName): array
    {
        $actions = [];

        foreach ($changeValues as $item) {
            if (!array_key_exists($item['key'], $oldValues)) {
                $actions[] = ProductTypeAddLocalizedEnumValueAction::fromArray([
                    'attributeName' => $attributeName,
                    'value' => $item
                ]);
            }
        }

        return $actions;
    }

    /**
     * Collect old enum values
     *
     * @param array $oldAttribute
     *
     * @return array
     */
    private function collectOldValues(array $oldAttribute): array
    {
        $oldValues = [];

        foreach ($oldAttribute['type']['values'] as $item) {
            $oldValues[$item['key']] = $item;
        }

        return $oldValues;
    }

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
        list(, $attrIndex) = $this->getLastFoundMatch();
        $attribute = $sourceObject->getAttributes()->toArray()[$attrIndex];

        // Only apply on _localized_ enums
        if ($attribute['type']['name'] !== 'lenum') {
            return [];
        }

        $oldAttribute = $this->getOldAttribute($oldData, $attribute['name']);

        // Do not apply on new attributes
        if ($oldAttribute === null) {
            return [];
        }

        $oldValues = $this->collectOldValues($oldAttribute);

        // We don't need values which hasn't a key or label
        // Has key: Updated or added field
        // Has no key: Deleted field (which will be collected without this value)
        // Has no label: Item has no relevant changes
        $changedValue = array_filter($changedValue, function ($value) {
            return isset($value['key'], $value['label']);
        });

        $actions = array_merge(
            $this->addedValues($changedValue, $oldValues, $attribute['name']),
            $this->updatedValues($changedValue, $oldValues, $attribute['name']),
            $this->deletedValues($attribute, $oldValues)
        );

        return $actions;
    }

    /**
     * Delete old values / old values are items which does not exists in current attribute collection
     *
     * @param array $attribute
     * @param array $oldValues
     *
     * @return array
     */
    private function deletedValues(array $attribute, array $oldValues): array
    {
        $actions = [];

        $allCurrentKeys = array_column($attribute['type']['values'], 'key');
        $removedKeys = array_values(array_diff(array_keys($oldValues), $allCurrentKeys));

        if (count($removedKeys) > 0) {
            $actions[] = ProductTypeRemoveEnumValuesAction::fromArray([
                'attributeName' => $attribute['name'],
                'keys' => $removedKeys
            ]);
        }

        return $actions;
    }

    /**
     * Get old attribute data or null if attribute is new
     *
     * @param array $oldData
     * @param string $attributeName
     *
     * @return array|null
     */
    private function getOldAttribute(array $oldData, string $attributeName)
    {
        foreach ($oldData['attributes'] as $item) {
            if ($item['name'] === $attributeName) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Updated known values / updated values are items _with_ known key
     *
     * @param array $changeValues
     * @param array $oldValues
     * @param string $attributeName
     *
     * @return array
     */
    private function updatedValues(array $changeValues, array $oldValues, string $attributeName): array
    {
        $actions = [];

        foreach ($changeValues as $item) {
            if (array_key_exists($item['key'], $oldValues) && $oldValues[$item['key']] !== $item) {
                $actions[] = ProductTypeChangeLocalizedEnumLabelAction::fromArray([
                    'attributeName' => $attributeName,
                    'newValue' => $item
                ]);
            }
        }

        return $actions;
    }
}
