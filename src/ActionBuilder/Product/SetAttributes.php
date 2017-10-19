<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\Attribute;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductSetAttributeAction;

/**
 * Sets the attributes for products.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
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
                // We can only check for the name/value structure for a nested attribute, because the attribute
                // defintion must not be set every time.
                if ((is_array($attrValue)) && (is_array(@$oldAttrs[$attrIndex]['value']))) {
                    $isNested = $this->isNestedAttribute($oldAttrs[$attrIndex]);

                    foreach ($attrValue as $subIndex => &$attrSubValue) {
                        if ($isNested) {
                            if (!@$attrSubValue['name']) {
                                $attrSubValue['name'] = $oldAttrs[$attrIndex]['value'][$subIndex]['name'];
                            }
                        }
                    }

                    $attrValue = array_filter($attrValue, function ($attrSubValue) {
                        return $attrSubValue !== null;
                    });

                    if ($isNested) {
                        $attrValue = $attrValue + $oldAttrs[$attrIndex]['value'];

                        ksort($attrValue);
                    }
                }

                $action->setValue($attrValue);
            }

            $actions[] = $action;
        }

        return $actions;
    }

    private function isNestedAttribute($attr): bool
    {
        $isNested = false;

        if (is_array($attr['value'])) {
            $firstElement = current($attr['value']);

            $isNested = is_array($firstElement) &&
                array_key_exists(Attribute::PROP_NAME, $firstElement) &&
                array_key_exists(Attribute::PROP_VALUE, $firstElement);
        }

        return $isNested;
    }
}
