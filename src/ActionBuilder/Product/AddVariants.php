<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\AssetCollection;
use Commercetools\Core\Model\Common\AttributeCollection;
use Commercetools\Core\Model\Common\ImageCollection;
use Commercetools\Core\Model\Common\PriceDraftCollection;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductData;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductAddVariantAction;
use function ucfirst;

/**
 * Action which adds new variants to an existing product.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class AddVariants extends ProductActionBuilder
{
    use ResolveAttributeValueTrait;

    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/variants$';

    /**
     * @param array|mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Product|mixed $sourceObject
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
        if (!is_array($changedValue)) {
            return [];
        }

        // Filter out removed variants
        $changedValue = array_filter($changedValue);

        list (, $catalog) = $this->getLastFoundMatch();

        $productData = $sourceObject->getMasterData()->{'get' . ucfirst($catalog)}();

        $oldVariants = array_merge(
            $oldData['masterData'][$catalog]['variants'],
            [$oldData['masterData'][$catalog]['masterVariant']]
        );

        $oldSkuCollection = array_map(function (array $variant) {
            return $variant['sku'];
        }, $oldVariants);

        $currentVariants = [];

        /** @var ProductVariant $variant */
        foreach (iterator_to_array($productData->getVariants()) as $variant) {
            $currentVariants[$variant->getSku()] = $variant->toArray();
        }

        $addedVariants = [];

        // compare old and new variants
        foreach (array_keys($currentVariants) as $currentSku) {
            if (!in_array($currentSku, $oldSkuCollection)) {
                $addedVariants[] = $currentVariants[$currentSku];
            }
        }

        $actions = [];

        foreach ($addedVariants as $variant) {
            $attributes = array_map(function (array $attribute) {
                $attribute['value'] = $this->resolveAttributeValue($attribute['value']);

                return $attribute;
            }, $variant['attributes']);

            $actions[] = (new ProductAddVariantAction())
                ->setSku($variant['sku'])
                ->setPrices(PriceDraftCollection::fromArray($variant['prices']))
                ->setAttributes(AttributeCollection::fromArray($attributes))
                ->setImages(ImageCollection::fromArray($variant['images']))
                ->setAssets(AssetCollection::fromArray($variant['assets']));
        }

        return $actions;
    }
}
