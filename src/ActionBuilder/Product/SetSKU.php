<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Helper\VariantFinderTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Product\Product;
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
    use VariantFinderTrait;

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
        list(, $dataContainer, $variantType, $variantOffset) = $this->getLastFoundMatch();

        if ($variantType === 'masterVariant') {
            $variantId = $sourceObject->getMasterData()->{'get' . ucfirst($dataContainer)}()->getMasterVariant()->getId();
        } else {
            $variantId = $this->findVariantIdByVariantIndex($sourceObject, $variantOffset, $dataContainer);
        }

        if ($variantId === null) {
            return [];
        }

        return [
            ProductSetSkuAction::ofVariantId($variantId)
                ->setSku($changedValue)
                ->setStaged($dataContainer === 'staged'),
        ];
    }
}
