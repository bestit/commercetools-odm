<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Helper\AttributeFinderTrait;
use BestIt\CommercetoolsODM\Helper\VariantFinderTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\Attribute;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductData;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Request\Products\Command\ProductSetAttributeAction;
use Commercetools\Core\Request\Products\Command\ProductSetAttributeInAllVariantsAction;
use function array_filter;
use function array_key_exists;
use function count;
use function current;
use function Funct\Strings\upperCaseFirst;
use function is_array;
use function iterator_to_array;
use function ksort;

/**
 * Sets the attributes for products.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class SetAttributes extends ProductActionBuilder
{
    use ResolveAttributeValueTrait;
    use AttributeFinderTrait;
    use VariantFinderTrait;

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

        $variantData = $this->getOldAttributesAndIdOfVariant($oldData, $sourceObject);

        if ($variantData === null) {
            return $actions;
        }

        list($oldAttrs, $variantId) = $variantData;

        foreach ($changedValue as $attrIndex => $attr) {
            $attributeName = $attr['name'] ?? $oldAttrs[$attrIndex]['name'];

            $action = $this->startAction($sourceObject, $variantId, $attributeName);

            // If $attr is null, the given attribute has no value.
            // We have to create an action without to set a value. Commercetools will remove the attribute.
            if ($attr && isset($attr['value'])) {
                // ENUM and LENUM Attributes could contain an empty label (=null) because we only set value and no label.
                // Therefor, ODM detects a change for the label, which is uninteresting for us.
                // We don't want any action if the only changed field is the 'null label'.
                if (is_array($attr['value']) && count($attr['value']) === 1) {
                    if (array_key_exists('label', $attr['value']) && $attr['value']['label'] === null) {
                        continue;
                    }
                }

                $action->setValue($this->loadActionValue($attr, $oldAttrs, $attrIndex));
            }

            $actions[] = $action;
        }

        return $actions;
    }

    /**
     * Returns the attributes and the id of the changed variant.
     *
     * @param array|null $oldData
     * @param Product $sourceObject
     *
     * @return array|null The first value is the old attribute collection and the second value is the variant id.
     */
    private function getOldAttributesAndIdOfVariant(array $oldData, Product $sourceObject)
    {
        // TODO don't forget, masterVariant is id 1 but this $variantIndex is the numeric index in the variants array!
        list(, $productCatalogContainer, $variantContainer, $variantOffset) = $this->getLastFoundMatch();

        $oldProductCatalogData = $oldData['masterData'][$productCatalogContainer];
        $newProductData = $sourceObject->getMasterData()->{'get' . ucfirst($productCatalogContainer)}();

        if ($variantContainer === 'masterVariant') {
            return $this->getOldAttributesForMasterVariant($oldProductCatalogData, $newProductData);
        }

        return $this->getOldAttributesForVariant(
            $oldProductCatalogData,
            $this->findVariantIdByVariantIndex($sourceObject, $variantOffset, $productCatalogContainer)
        );
    }

    /**
     * Gets the master variant id and returns the old attributes for this id and the id.
     *
     * @param array $oldProductCatalogData
     * @param ProductData $productData
     *
     * @return array|null
     */
    private function getOldAttributesForMasterVariant(array $oldProductCatalogData, ProductData $productData): ?array
    {
        $masterVariantId = $productData->getMasterVariant()->getId();

        if ($masterVariantId === null) {
            return null;
        }

        $oldAttrs = null;

        // if master variant did not change
        if ($masterVariantId !== $oldProductCatalogData['masterVariant']['id']) {
            $oldAttrs = $oldProductCatalogData['masterVariant']['attributes'];
        }

        // if it changed search for the old variant with the master id
        if ($masterVariantId !== $oldProductCatalogData['masterVariant']['id']) {
            foreach ($oldProductCatalogData['variants'] as $variant) {
                if ($variant['id'] === $masterVariantId) {
                    $oldAttrs = $variant['attributes'];

                    break;
                }
            }
        }

        if ($oldAttrs === null) {
            return null;
        }

        return [$oldAttrs, $masterVariantId];
    }

    /**
     * @param array $oldProductCatalogData
     * @param int $variantId
     *
     * @return array|null
     */
    public function getOldAttributesForVariant(array $oldProductCatalogData, ?int $variantId): ?array
    {
        if ($variantId === null) {
            return null;
        }

        $oldAttrs = null;

        foreach ($oldProductCatalogData['variants'] as $variant) {
            if ($variant['id'] === $variantId) {
                $oldAttrs = $variant['attributes'];

                break;
            }
        }

        if ($oldAttrs === null) {
            return null;
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

        return $this->resolveAttributeValue($attrValue);
    }

    /**
     * Starts the change action for this variant.
     *
     * @param Product $product
     * @param int $variantId
     * @param string $attributeName
     *
     * @return ProductSetAttributeAction|ProductSetAttributeInAllVariantsAction
     */
    private function startAction(Product $product, int $variantId, string $attributeName)
    {
        // TODO don't forget, masterVariant is id 1 but this $variantIndex is the numeric index in the variants array!
        list(, $productCatalogContainer) = $this->getLastFoundMatch();

        $variants = array_merge(
            [$product->getMasterData()->{'get' . upperCaseFirst($productCatalogContainer)}()->getMasterVariant()],
            iterator_to_array($product->getMasterData()->{'get' . upperCaseFirst($productCatalogContainer)}()->getVariants())
        );

        if ($variants && count($variants) && !$this->valueIsSameForAllVariants($attributeName, $variants)) {
            $action = ProductSetAttributeAction::ofVariantIdAndName(
                $variantId,
                $attributeName
            );
        } else {
            // Workaround against "variant duplication" error on a single master variant with no attr constraint
            $action = ProductSetAttributeInAllVariantsAction::ofName(
                $attributeName
            );
        }

        return $action->setStaged($productCatalogContainer === 'staged');
    }

    /**
     * Checks if the given attribute has the same value in all variants.
     *
     * @param string $attributeName
     * @param ProductVariant[] $variants
     *
     * @return bool
     */
    private function valueIsSameForAllVariants(string $attributeName, array $variants): bool
    {
        $previousValue = null;

        foreach ($variants as $variant) {
            $attribute = $this->getAttributeByName($variant->getAttributes(), $attributeName);

            if (!$attribute instanceof Attribute) {
                continue;
            }

            if ($previousValue !== null && $attribute->toArray()['value'] !== $previousValue) {
                return false;
            }

            $previousValue = $attribute->toArray()['value'];
        }

        return true;
    }
}
