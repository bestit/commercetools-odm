<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductRemoveVariantAction;

/**
 * Action which removes variants from an existing product.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class RemoveVariants extends ProductActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/variants$';

    /**
     * @param array|mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Product|mixed $sourceObject
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
        if (!is_array($changedValue)) {
            return [];
        }

        return $this->getRemoveVariantActions($changedValue, $sourceObject);
    }

    /**
     * @param array $changedValue
     * @param Product $sourceObject
     *
     * @return ProductRemoveVariantAction[]
     */
    private function getRemoveVariantActions(array $changedValue, Product $sourceObject): array
    {
        $removedVariantIndexes = $this->findRemovedVariants($changedValue);

        $originalVariants = $sourceObject->getMasterData()->getCurrent()->getVariants();

        $actions = [];

        foreach ($removedVariantIndexes as $index) {
            $skuToRemove = $originalVariants->getAt($index)->getSku();

            $actions[] = ProductRemoveVariantAction::ofSku($skuToRemove);
        }

        return $actions;
    }

    /**
     * Removed variants are just `null` but with the correct index being set.
     *
     * @param array $changedValue
     *
     * @return int[]
     */
    private function findRemovedVariants(array $changedValue): array
    {
        $removedIndexes = [];

        foreach ($changedValue as $index => $variant) {
            if ($variant !== null) {
                continue;
            }

            $removedIndexes[] = $index;
        }

        return $removedIndexes;
    }
}
