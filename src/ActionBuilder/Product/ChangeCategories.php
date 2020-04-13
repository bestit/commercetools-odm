<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Category\CategoryReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductAddToCategoryAction;
use Commercetools\Core\Request\Products\Command\ProductRemoveFromCategoryAction;

/**
 * Action Builder to change the categories of an product.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class ChangeCategories extends ProductActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/categories$';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
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

        list(, $productCatalogData) = $this->getLastFoundMatch();

        $isStaging = $productCatalogData === 'staged';

        foreach ($changedValue as $index => $categoryData) {
            if ((array_key_exists($index, $oldData['masterData'][$productCatalogData]['categories'])) &&
                ($oldData['masterData'][$productCatalogData]['categories'])
            ) {
                $actions[] = ProductRemoveFromCategoryAction::ofCategory(CategoryReference::ofId(
                    $oldData['masterData'][$productCatalogData]['categories'][$index]['id']
                ))->setStaged($isStaging);
            }

            if (!is_null($categoryData)) {
                if (!isset($categoryData['id']) || $categoryData['id'] === null) {
                    $actions[] = ProductAddToCategoryAction::ofCategory(CategoryReference::ofTypeAndKey(CategoryReference::TYPE_CATEGORY, $categoryData['key']))
                        ->setStaged($isStaging);
                } else {
                    $actions[] = ProductAddToCategoryAction::ofCategory(CategoryReference::ofId($categoryData['id']))
                        ->setStaged($isStaging);
                }
            }
        }

        return $actions;
    }
}
