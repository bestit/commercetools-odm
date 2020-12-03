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
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductAddVariantAction;
use Commercetools\Core\Request\Products\Command\ProductChangeMasterVariantAction;

/**
 * Action which changes the master variant on an existing product.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class ChangeMasterVariant extends ProductActionBuilder
{
    use ResolveAttributeValueTrait;

    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/masterVariant$';

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
        $actions = [];

        list(, $productCatalogContainer) = $this->getLastFoundMatch();

        /** @var ProductData $productData */
        $productData = $sourceObject->getMasterData()->{'get' . ucfirst($productCatalogContainer)}();
        $variantId = $productData->getMasterVariant()->getId();

        // if it is a new variant it needs an sku to be changed master variant after adding it.
        if (!isset($changedValue['sku'])) {
            return $actions;
        }

        // if it is a new master variant, then add it and make it the master variant
        if ($variantId === null) {
            $oldVariants = array_merge(
                $oldData['masterData'][$productCatalogContainer]['variants'],
                [$oldData['masterData'][$productCatalogContainer]['masterVariant']]
            );

            $needsToBeAdded = true;

            foreach ($oldVariants as $oldVariant) {
                if ($changedValue['sku'] === $oldVariant['sku']) {
                    $needsToBeAdded = false;

                    break;
                }
            }

            if ($needsToBeAdded === true) {
                $actions[] = $this->createAddAction($productData->getMasterVariant()->toArray());
            }

            $actions[] = ProductChangeMasterVariantAction::ofSku($changedValue['sku']);

            return $actions;
        }

        $actions[] = ProductChangeMasterVariantAction::ofVariantId($variantId);

        return $actions;
    }

    /**
     * Make sure this is executed before delete of any variant
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 2;
    }

    /**
     * @param array $variant
     * @return ProductAddVariantAction
     */
    private function createAddAction(array $variant): ProductAddVariantAction
    {
        $attributes = array_map(function (array $attribute) {
            $attribute['value'] = $this->resolveAttributeValue($attribute['value']);

            return $attribute;
        }, $variant['attributes']);

        return (new ProductAddVariantAction())
            ->setSku($variant['sku'])
            ->setPrices(PriceDraftCollection::fromArray($variant['prices']))
            ->setAttributes(AttributeCollection::fromArray($attributes))
            ->setImages(ImageCollection::fromArray($variant['images']))
            ->setAssets(AssetCollection::fromArray($variant['assets']));
    }
}