<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\AssetCollection;
use Commercetools\Core\Model\Common\AttributeCollection;
use Commercetools\Core\Model\Common\ImageCollection;
use Commercetools\Core\Model\Common\PriceDraftCollection;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Model\Product\ProductVariantCollection;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductAddVariantAction;

use function iterator_to_array;

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

        list (, $catalog) = $this->getLastFoundMatch();

        return $this->getAddVariantActions($sourceObject->getMasterData()->{'get' . ucfirst($catalog)}()->getVariants());
    }

    /**
     * @param array $changedValue
     *
     * @return ProductAddVariantAction[]
     */
    private function getAddVariantActions(ProductVariantCollection $variants): array
    {
        $addedVariants = $this->findAddedVariants($variants);

        $actions = [];

        foreach ($addedVariants as $variant) {
            $attributes = array_map(function (array $attribute) {
                $attribute['value'] = $this->resolveAttributeValue($attribute['value']);

                return $attribute;
            }, $variant['attributes']);

            $action = (new ProductAddVariantAction())
                ->setPrices(PriceDraftCollection::fromArray($variant['prices']))
                ->setAttributes(AttributeCollection::fromArray($attributes))
                ->setImages(ImageCollection::fromArray($variant['images']))
                ->setAssets(AssetCollection::fromArray($variant['assets']));

            if (isset($variant['sku'])) {
                $action->setSku($variant['sku']);
            }

            $actions[] = $action;
        }

        return $actions;
    }

    /**
     * New variants must not have an ID set.
     *
     * @param ProductVariantCollection $variants
     *
     * @return array
     */
    private function findAddedVariants(ProductVariantCollection $variants): array
    {
        $addedVariants = [];

        foreach (iterator_to_array($variants) as $variant) {
            /** @var ProductVariant $variant */
            if ($variant->getId() !== null) {
                continue;
            }

            $addedVariants[] = $variant->toArray();
        }

        return $addedVariants;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return -1;
    }
}