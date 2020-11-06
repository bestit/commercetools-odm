<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Helper\VariantFinderTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\Price;
use Commercetools\Core\Model\Common\PriceDraft;
use Commercetools\Core\Model\Common\PriceDraftCollection;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductData;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductSetPricesAction;

/**
 * Set prices for the product (remove all and add new)
 *
 * @author Michel Chowanski <michel.chowanski@bestit.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 * @subpackage ActionBuilder\Product
 */
class SetPrices extends ProductActionBuilder
{
    use VariantFinderTrait;

    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/(masterVariant|variants)/([\d]*)/?prices$';

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
        list(, $dataId, $variantType, $variantId) = $this->getLastFoundMatch();

        if ($variantType === 'masterVariant') {
            $variantId = 1;
        } else {
            $variantId = $this->findVariantIdByVariantIndex($sourceObject, $variantId);
        }

        $actions = [];
        /** @var ProductData $productData */
        $productData = $sourceObject->getMasterData()->{'get' . ucfirst($dataId)}();
        $variant = $this->getVariantById($productData->getAllVariants(), $variantId);

        if ($variantId === null || $variant === null) {
            return $actions;
        }

        // We provide all _current_ prices to the action. Commercetools will decide which price should be added,
        // removed or changed. We don't have to handle it manually. But keep in mind, that changed
        // prices get an new UUID.

        $priceDrafts = [];
        foreach ($variant->getPrices() as $price) {
            if ($price instanceof Price) {
                $priceDrafts[] = PriceDraft::fromArray($price->toArray());
            }
        }

        return [ProductSetPricesAction::ofVariantIdAndPrices($variantId, PriceDraftCollection::fromArray($priceDrafts))];
    }
}
