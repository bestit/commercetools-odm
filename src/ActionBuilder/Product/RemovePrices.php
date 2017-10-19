<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\PriceCollection;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductRemovePriceAction;

/**
 * Creates the actions to remove prices.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class RemovePrices extends PriceActionBuilder
{
    /**
     * Creates the update actions for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Product $sourceObject
     * @return AbstractAction[]
     * @todo Add Variants.
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        list(, $dataId, $variantId) = $this->getLastFoundMatch();

        $actions = [];
        $variant = $sourceObject->getMasterData()->{'get' . ucfirst($dataId)}()->getMasterVariant();

        /** @var PriceCollection $variantPrices */
        $variantPrices = $variant->getPrices();

        foreach ($oldData['masterData'][$dataId]['masterVariant']['prices'] as $priceArray) {
            if ($priceArray && ($priceId = $priceArray['id']) && (!$variantPrices->getById($priceId))) {
                $actions[] = ProductRemovePriceAction::ofPriceId($priceId);
            }
        }

        return $actions;
    }
}
