<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\AssetCollection;
use Commercetools\Core\Model\Common\AttributeCollection;
use Commercetools\Core\Model\Common\ImageCollection;
use Commercetools\Core\Model\Common\PriceDraftCollection;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductAddVariantAction;

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

        return $this->getAddVariantActions($changedValue);
    }

    /**
     * @param array $changedValue
     *
     * @return ProductAddVariantAction[]
     */
    private function getAddVariantActions(array $changedValue): array
    {
        $addedVariants = $this->findAddedVariants($changedValue);

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

    /**
     * New variants must have a SKU set and not have an ID set.
     *
     * @param array $changedValue
     *
     * @return array
     */
    private function findAddedVariants(array $changedValue): array
    {
        $addedVariants = [];

        foreach ($changedValue as $value) {
            if (array_key_exists('id', $value)) {
                continue;
            }

            if (!isset($value['sku']) || empty($value['sku'])) {
                continue;
            }

            $addedVariants[] = $value;
        }

        return $addedVariants;
    }
}
