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
use Commercetools\Core\Request\Products\Command\ProductChangeMasterVariantAction;
use Commercetools\Core\Request\Products\Command\ProductRemoveVariantAction;

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

        list(, $productCatalogContainer) = $this->getLastFoundMatch();

        /** @var ProductData $productData */
        $productData = $sourceObject->getMasterData()->{'get' . ucfirst($productCatalogContainer)}();
        $variantId = $productData->getMasterVariant()->getId();

        $oldMasterVariant = $oldData['masterData'][$productCatalogContainer]['masterVariant'];

        // handle new master variant (has no id)
        if ($variantId === null) {
            return $this->handleNewMasterVariant(
                $productData->getMasterVariant(),
                $oldData['masterData'][$productCatalogContainer]['masterVariant'],
                $changedValue
            );
        }

        // if master variant id changed (different variant id), then create an update action
        if ($variantId !== $oldMasterVariant['id']) {
            return [ProductChangeMasterVariantAction::ofVariantId($variantId)];
        }

        return [];
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
     * A new master variant was added.
     *
     * - Add the variant
     * - Change it to master variant
     * - Remove the old master variant
     *
     * @param ProductVariant $currentMasterVariant
     * @param array $oldMasterVariant
     * @param array $changedValue
     *
     * @return array
     */
    private function handleNewMasterVariant(
        ProductVariant $currentMasterVariant,
        array $oldMasterVariant,
        array $changedValue
    ): array {
        // New variants do not have an ID so we need to use the sku to set them as master variant.
        if (!isset($changedValue['sku'])) {
            return [];
        }

        return [
            $this->createAddAction($currentMasterVariant->toArray()),
            ProductChangeMasterVariantAction::ofSku($changedValue['sku']),
            ProductRemoveVariantAction::ofVariantId($oldMasterVariant['id'])
        ];
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