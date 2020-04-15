<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\PriceDraft;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductData;
use Commercetools\Core\Request\AbstractAction;
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
        $match = $this->getLastFoundMatch();

        if (!$match || !is_array($changedValue)) {
            return [];
        }

        list(, $dataId, $variantType, $variantId) = $this->getLastFoundMatch();

        if ($variantType === 'masterVariant') {
            $variantId = 1;
        } else {
            $variantId += 2;
        }

        /** @var ProductData $productData */
        $productData = $sourceObject->getMasterData()->{'get' . ucfirst($dataId)}();
        $variant = $productData->getVariantById($variantId);

        if ($variant === null) {
            return [];
        }

        $variantPrices = $variant->getPrices();

        $actions = [];

        foreach ($changedValue as $index => $priceArray) {
            if ($priceArray && !$variantPrices->getAt($index)->getId()) {
                $actions[] = ProductAddPriceAction::ofVariantIdAndPrice(
                    $variant->getId(),
                    PriceDraft::fromArray($priceArray)
                )->setStaged($dataId === 'staged');
            }
        }

        return $actions;
    }
}
