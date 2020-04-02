<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeChangeAttributeOrderByNameAction;
use function array_column;
use function class_exists;

/**
 * ActionBuilder to change order of attributes
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ProductType
 */
class ChangeOrderOfAttributes extends ProductTypeActionBuilder
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
        if (!class_exists(ProductTypeChangeAttributeOrderByNameAction::class, true)) {
            return [];
        }

        $oldOrder = array_column($oldData['attributes'], 'name');
        $newOrder = array_column($sourceObject->getAttributes()->toArray(), 'name');

        $oldOrder = $this->filterRemovedValues($oldOrder, $newOrder);

        if ($oldOrder === $newOrder) {
            return [];
        }

        foreach ($newOrder as $index => $new) {
            // Added values are not relevant if they're added to the end
            if (!isset($oldOrder[$index])) {
                continue;
            }

            if ($oldOrder[$index] !== $new) {
                return [new ProductTypeChangeAttributeOrderByNameAction([
                    'attributeNames' => $newOrder
                ])];
            }
        }

        return [];
    }

    /**
     * Must be the last action in our request (so all added and removed attributes are done)
     *
     * @return int
     */
    public function getPriority(): int
    {
        return -255;
    }

    /**
     * Filters out values from the old attributes that are not in the new attributes without changing the order.
     *
     * @param array $oldOrder
     * @param array $newOrder
     *
     * @return array
     */
    private function filterRemovedValues(array $oldOrder, array $newOrder): array
    {
        return array_values(array_filter($oldOrder, static function ($item) use ($oldOrder, $newOrder) {
            return !in_array($item, array_diff($oldOrder, $newOrder), true);
        }));
    }
}
