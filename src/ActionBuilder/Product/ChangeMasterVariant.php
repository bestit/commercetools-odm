<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Helper\VariantFinderTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\AssetCollection;
use Commercetools\Core\Model\Common\AttributeCollection;
use Commercetools\Core\Model\Common\ImageCollection;
use Commercetools\Core\Model\Common\PriceDraftCollection;
use Commercetools\Core\Model\Product\Product;
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

        if (!isset($changedValue['sku'])) {
            return $actions;
        }

        list(, $mode) = $this->getLastFoundMatch();

        $oldVariants = array_merge(
            $oldData['masterData'][$mode]['variants'],
            [$oldData['masterData'][$mode]['masterVariant']]
        );

        $needsToBeAdded = true;

        foreach ($oldVariants as $oldVariant) {
            if ($changedValue['sku'] === $oldVariant['sku']) {
                $needsToBeAdded = false;

                break;
            }
        }

        if ($needsToBeAdded === true) {
            $actions[] = $this->createAddAction($changedValue);
        }

        $actions[] = ProductChangeMasterVariantAction::ofSku($changedValue['sku']);

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
     * @param array $changedValue
     *
     * @return ProductAddVariantAction
     */
    private function createAddAction(array $changedValue): ProductAddVariantAction
    {
        $attributes = array_map(function (array $attribute) {
            $attribute['value'] = $this->resolveAttributeValue($attribute['value']);

            return $attribute;
        }, $changedValue['attributes']);

        return (new ProductAddVariantAction())
            ->setSku($changedValue['sku'])
            ->setPrices(PriceDraftCollection::fromArray($changedValue['prices']))
            ->setAttributes(AttributeCollection::fromArray($attributes))
            ->setImages(ImageCollection::fromArray($changedValue['images']))
            ->setAssets(AssetCollection::fromArray($changedValue['assets']));
    }
}
