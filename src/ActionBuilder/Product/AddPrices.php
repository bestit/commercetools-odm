<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\PriceDraft;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Request\Products\Command\ProductAddPriceAction;

/**
 * Adds prices to the product.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class AddPrices extends PriceActionBuilder
{
    /**
     * Creates the update actions for the given class and data.
     *
     * @todo Add Variants.
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
        $actions = [];

        if ($match = $this->getLastFoundMatch()) {
            list(, $dataId, $variantId) = $match;

            if ($changedValue) {
                /** @var ProductVariant $variant */
                $variant = $sourceObject->getMasterData()->{'get' . ucfirst($dataId)}()->getMasterVariant();
                $variantPrices = $variant->getPrices();

                foreach ($changedValue as $index => $priceArray) {
                    if ($priceArray && !$variantPrices->getAt($index)->getId()) {
                        $actions[] = ProductAddPriceAction::ofVariantIdAndPrice(
                            $variant->getId(),
                            PriceDraft::fromArray($priceArray)
                        )->setStaged($dataId === 'staged');
                    }
                }
            }
        }

        return $actions;
    }
}
