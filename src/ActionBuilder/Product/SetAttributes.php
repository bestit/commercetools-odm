<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\Attribute;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductSetAttributeAction;
use function array_filter;
use function array_key_exists;
use Commercetools\Core\Request\Products\Command\ProductSetAttributeInAllVariantsAction;
use function count;
use function current;
use function is_array;
use function ksort;
use function Funct\Strings\upperCaseFirst;

/**
 * Sets the attributes for products.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class SetAttributes extends ProductActionBuilder
{
    /**
     * @var string A PCRE to match the hierarchical field path without delimiter.
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/(masterVariant|variants)/([\d]*)/?attributes$';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Product $sourceObject The full product object.
     * @return ProductSetAttributeAction[]|ProductSetAttributeInAllVariantsAction[]
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
        $variants = $sourceObject->getMasterData()->{'get' . upperCaseFirst($productCatalogContainer)}()->getVariants();

        foreach ($changedValue as $attrIndex => $attr) {
            if ($variantContainer === 'masterVariant') {
                $oldAttrs = $oldProductCatalogData['masterVariant']['attributes'];
                $variantId = 1;
            } else {
                $oldAttrs = $oldProductCatalogData[$variantContainer][$variantIndex]['attributes'];
                $variantId = $oldProductCatalogData[$variantContainer][$variantIndex]['id'];
            }

            /** @var ProductSetAttributeAction|ProductSetAttributeInAllVariantsAction $action */
            if ($variants && count($variants)) {
                $action = ProductSetAttributeAction::ofVariantIdAndName(
                    $variantId,
                    $attr['name'] ?? $oldAttrs[$attrIndex]['name']
                );
            } else {
                // Workaround against "variant duplication" error on a single master variant with no attr constraint
                $action = ProductSetAttributeInAllVariantsAction::ofName(
                    $attr['name'] ?? $oldAttrs[$attrIndex]['name']
                );
            }

            $action->setStaged($productCatalogContainer === 'staged');

            if ($attr && isset($attr['value'])) {
                $attrValue = $attr['value'];

                // TODO: Refactor this and enable more levels.
                // We can only check for the name/value structure for a nested attribute, because the attribute
                // definition must not be set every time.
                if ((is_array($attrValue)) && (is_array(@$oldAttrs[$attrIndex]['value']))) {
                    $isNested = $this->isNestedAttribute($oldAttrs[$attrIndex]);

                    if ($isNested) {
                        foreach ($attrValue as $subIndex => &$attrSubValue) {
                            if (!@$attrSubValue['name']) {
                                $attrSubValue['name'] = $oldAttrs[$attrIndex]['value'][$subIndex]['name'];
                            }
                        }
                    }

                    $attrValue = $attrValue + $oldAttrs[$attrIndex]['value'];

                    $attrValue = array_filter($attrValue, function ($attrSubValue) {
                        return $attrSubValue !== null;
                    });

                    ksort($attrValue);
                }

                $action->setValue($attrValue);
            }

            $actions[] = $action;
        }

        return $actions;
    }

    /**
     * Returns true if the given attribute is a nested attribute.
     *
     * @param array|mixed $attr The checked attribute.
     * @return bool
     */
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
