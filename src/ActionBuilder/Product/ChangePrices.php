<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\PriceDraft;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductData;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductChangePriceAction;

/**
 * Changes prices for the product.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 * @subpackage ActionBuilder\Product
 */
class ChangePrices extends PriceActionBuilder
{
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
            $variantId += 2;
        }

        $actions = [];
        /** @var ProductData $productData */
        $productData = $sourceObject->getMasterData()->{'get' . ucfirst($dataId)}();
        $variant = $productData->getVariantById($variantId);

        if ($variant === null) {
            return $actions;
        }

        $variantPrices = $variant->getPrices();

        foreach ($changedValue as $index => $priceArray) {
            $foundPrice = $variantPrices->getAt($index);

            if ($foundPrice === null) {
                continue;
            }

            if ($priceId = $foundPrice->getId()) {
                $actions[] = ProductChangePriceAction::ofPriceIdAndPrice(
                    $priceId,
                    PriceDraft::fromArray($foundPrice->toArray())
                )->setStaged($dataId === 'staged');
            }
        }

        return $actions;
    }

    /**
     * Must be called before remove prices & before remove variants.
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 3;
    }
}
