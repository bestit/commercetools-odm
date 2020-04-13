<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductSetSkuAction;

/**
 * Sets the sku on variants.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 * @subpackage ActionBuilder\Product
 */
class SetSKU extends ProductActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/(masterVariant|variants)/([\d]*)/?sku$';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Product $sourceObject
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
        // TODO don't forget, masterVariant is id 1 but this $variantId is the numeric index in the variants array!
        list(, $dataContainer, , $variantId) = $this->getLastFoundMatch();

        $variantId = trim($variantId) !== '' ? $variantId + 2 : 1;

        if ($variantId !== 1 && $this->variantDoesNotExist($sourceObject, $variantId)) {
            return [];
        }

        return [
            ProductSetSkuAction::ofVariantId($variantId)
                ->setSku($changedValue)
                ->setStaged($dataContainer === 'staged')
        ];
    }

    /**
     * @param Product $product
     * @param int $variantId
     *
     * @return bool
     */
    private function variantDoesNotExist(Product $product, int $variantId): bool
    {
        $variants = $product->getMasterData()->getCurrent()->getVariants();

        /** @var ProductVariant $variant */
        foreach ($variants as $variant) {
            if ($variant->getId() === $variantId) {
                return false;
            }
        }

        return true;
    }
}
