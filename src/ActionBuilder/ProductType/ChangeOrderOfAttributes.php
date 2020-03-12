<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeChangeAttributeOrderByNameAction;

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
        $oldOrder = array_column($oldData['attributes'], 'name');
        $newOrder = array_column($sourceObject->getAttributes()->toArray(), 'name');

        // If amount and values same but not the order
        if ($oldOrder !== $newOrder) {
            return [new ProductTypeChangeAttributeOrderByNameAction([
                'attributeNames' => $newOrder
            ])];
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
}
