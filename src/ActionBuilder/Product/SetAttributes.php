<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductSetAttributeAction;

/**
 * Sets the attributes for products.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Product
 * @version $id$
 */
class SetAttributes extends ProductActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/(masterVariant|variants)/([\d]*)/?attributes$';

    /**
     * Creates the update actions for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     * @return AbstractAction[]
     * @todo Check if the name of the attr matches the oldattr if you just use the attrindex.
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        // TODO don't forget, masterVariant is id 1 but this $variantIndex is the numeric index in the variants array!
        list(, $productCatalogContainer, $variantContainer, $variantIndex) = $this->getLastFoundMatch();

        $actions = [];
        $oldProductCatalogData = $oldData['masterData'][$productCatalogContainer];

        foreach ($changedValue as $attrIndex => $attr) {
            if ($variantContainer === 'masterVariant') {
                $oldAttrs = $oldProductCatalogData['masterVariant']['attributes'];
                $variantId = 1;
            } else {
                $oldAttrs = $oldProductCatalogData[$variantContainer][$variantIndex]['attributes'];
                $variantId = $oldProductCatalogData[$variantContainer][$variantIndex]['id'];
            }

            $action = ProductSetAttributeAction::ofVariantIdAndName(
                $variantId,
                $attr['name'] ?? $oldAttrs[$attrIndex]['name']
            )->setStaged($productCatalogContainer === 'staged');

            if ($attr && isset($attr['value'])) {
                $attrValue = $attr['value'];

                // TODO: Refactor this and enable more levels.
                if ((is_array($attrValue)) && (is_array($oldAttrs[$attrIndex]['value']))) {
                    foreach ($attrValue as $index => &$attrSubValue) {
                        if (@$oldAttrs[$attrIndex]['value'][$index]['name'] &&
                            @$oldAttrs[$attrIndex]['value'][$index]['value'] && !@$attrSubValue['name']
                        ) {
                            $attrSubValue['name'] = $oldAttrs[$attrIndex]['value'][$index]['name'];
                        }
                    }
                }

                $action->setValue($attrValue);
            }

            $actions[] = $action;
        }

        return $actions;
    }
}
