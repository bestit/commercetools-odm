<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\Attribute;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductSetAttributeAction;
use Commercetools\Core\Request\Products\Command\ProductSetAttributeInAllVariantsAction;
use function array_filter;
use function array_key_exists;
use function count;
use function current;
use function Funct\Strings\upperCaseFirst;
use function is_array;
use function ksort;

/**
 * Sets the attributes for products.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class SetAttributes extends ProductActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/(masterVariant|variants)/([\d]*)/?attributes$';

    /**
     * Creates the update actions for the given class and data.
     *
     * @todo Check if the name of the attr matches the oldattr if you just use the attrindex.
     *
     * @param mixed|array $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Product $sourceObject The full product object.
     *
     * @return ProductSetAttributeAction[]|ProductSetAttributeInAllVariantsAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        $actions = [];

        list($oldAttrs, $variantId) = $this->getAttributesAndIdOfVariant($oldData);

        foreach ($changedValue as $attrIndex => $attr) {
            $action = $this->startAction($sourceObject, $variantId, $attr, $oldAttrs, $attrIndex);

            if ($attr && isset($attr['value'])) {
                $action->setValue($this->loadActionValue($attr, $oldAttrs, $attrIndex));
            }

            $actions[] = $action;
        }

        return $actions;
    }

    /**
     * Returns the attributes and the id of the changed variant.
     *
     * @param array $oldData
     *
     * @return array The first value is the old attribute collection and the second value is the variant id.
     */
    private function getAttributesAndIdOfVariant(array $oldData): array
    {
        // TODO don't forget, masterVariant is id 1 but this $variantIndex is the numeric index in the variants array!
        list(, $productCatalogContainer, $variantContainer, $variantIndex) = $this->getLastFoundMatch();

        $oldProductCatalogData = $oldData['masterData'][$productCatalogContainer];
        if ($variantContainer === 'masterVariant') {
            $oldAttrs = $oldProductCatalogData['masterVariant']['attributes'];
            $variantId = 1;
        } else {
            $oldAttrs = $oldProductCatalogData[$variantContainer][$variantIndex]['attributes'];
            $variantId = $oldProductCatalogData[$variantContainer][$variantIndex]['id'];
        }

        return [$oldAttrs, $variantId];
    }

    /**
     * Returns true if the given attribute is a nested attribute.
     *
     * @param array|mixed $attr The checked attribute.
     *
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

    /**
     * Loads the action value based on the old data of the variant.
     *
     * @param array $attr
     * @param array $oldAttrs
     * @param int $attrIndex
     *
     * @return array|mixed
     */
    private function loadActionValue(array $attr, array $oldAttrs, int $attrIndex)
    {
        $attrValue = $attr['value'];

        // TODO: Refactor this and enable more levels.
        // We can only check for the name/value structure for a nested attribute, because the attribute
        // definition must not be set every time.
        if ((is_array($attrValue)) && (is_array(@$oldAttrs[$attrIndex]['value']))) {
            if ($this->isNestedAttribute($oldAttrs[$attrIndex])) {
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

        return $attrValue;
    }

    /**
     * Starts the change action for this variant.
     *
     * @param Product $product
     * @param int $variantId
     * @param array $attr
     * @param array $oldVarAttrs
     * @param int $attrIndex
     *
     * @return ProductSetAttributeAction|ProductSetAttributeInAllVariantsAction
     */
    private function startAction(Product $product, int $variantId, array $attr, array $oldVarAttrs, int $attrIndex)
    {
        // TODO don't forget, masterVariant is id 1 but this $variantIndex is the numeric index in the variants array!
        list(, $productCatalogContainer) = $this->getLastFoundMatch();

        $variants = $product->getMasterData()->{'get' . upperCaseFirst($productCatalogContainer)}()->getVariants();

        if ($variants && count($variants)) {
            $action = ProductSetAttributeAction::ofVariantIdAndName(
                $variantId,
                $attr['name'] ?? $oldVarAttrs[$attrIndex]['name']
            );
        } else {
            // Workaround against "variant duplication" error on a single master variant with no attr constraint
            $action = ProductSetAttributeInAllVariantsAction::ofName(
                $attr['name'] ?? $oldVarAttrs[$attrIndex]['name']
            );
        }

        return $action->setStaged($productCatalogContainer === 'staged');
    }
}
