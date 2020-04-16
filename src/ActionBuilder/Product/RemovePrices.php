<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductData;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductRemovePriceAction;

/**
 * Creates the actions to remove prices.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class RemovePrices extends PriceActionBuilder
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
        list(, $dataId, $variantType, $variantIndex) = $this->getLastFoundMatch();

        if ($variantType === 'masterVariant') {
            $variantId = 1;
        } else {
            $variantId = $this->findVariantIdByVariantIndex($sourceObject, $variantIndex);
        }

        /** @var ProductData $productData */
        $productData = $sourceObject->getMasterData()->{'get' . ucfirst($dataId)}();
        $variant = $productData->getVariantById($variantId);

        if ($variant === null) {
            return [];
        }

        $variantPrices = $variant->getPrices();

        $actions = [];

        if ($variantType === 'masterVariant') {
            $oldPrices = $oldData['masterData'][$dataId][$variantType]['prices'] ?? [];
        } else {
            $oldPrices = $oldData['masterData'][$dataId][$variantType][$variantIndex]['prices'] ?? [];
        }

        foreach ($oldPrices as $priceArray) {
            if ($priceArray && ($priceId = $priceArray['id']) && (!$variantPrices->getById($priceId))) {
                $actions[] = ProductRemovePriceAction::ofPriceId($priceId);
            }
        }

        return $actions;
    }

    /**
     * Remove prices should be called after change prices.
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 2;
    }
}
