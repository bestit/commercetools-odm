<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductRemoveVariantAction;
use function ucfirst;

/**
 * Action which removes variants from an existing product.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class RemoveVariants extends ProductActionBuilder
{
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
        $actions = [];

        if (!is_array($changedValue)) {
            return $actions;
        }

        list (, $catalog) = $this->getLastFoundMatch();

        $oldIds = array_map(function (array $variant) {
            return $variant['id'];
        }, $oldData['masterData'][$catalog]['variants']);

        $currentIds = array_map(
            function (ProductVariant $variant) {
                return $variant->getId();
            },
            iterator_to_array($sourceObject->getMasterData()->{'get' . ucfirst($catalog)}()->getVariants())
        );

        foreach ($oldIds as $oldId) {
            if (!in_array($oldId, $currentIds)) {
                $actions[] = ProductRemoveVariantAction::ofVariantId($oldId);
            }
        }

        return $actions;
    }

    /**
     * At which order should this builder be executed? Highest happens first.
     *
     * Must be before add variant/set sku action so we don't run into conflicts when removing & adding a new
     * variant at the same time
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 3;
    }
}
