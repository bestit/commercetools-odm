<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Category\CategoryReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductAddToCategoryAction;
use Commercetools\Core\Request\Products\Command\ProductRemoveFromCategoryAction;

class ChangeCategories extends ProductActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/categories$';

    /**
     * Creates the update actions for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
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
            $actions[] = ProductRemoveFromCategoryAction::ofCategory(CategoryReference::ofId(
                $oldData['masterData'][$productCatalogData]['categories'][$index]['id']
            ))->setStaged($isStaging);

            if (!is_null($categoryData)) {
                $actions[] = ProductAddToCategoryAction::ofCategory(CategoryReference::ofId($categoryData['id']))
                    ->setStaged($isStaging);
            }
        }

        return $actions;
    }
}
