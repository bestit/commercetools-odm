<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Product\LocalizedSearchKeywords;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductSetSearchKeywordsAction;
use function array_filter;

/**
 * Sets the search keywords on variants.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class SetSearchKeywords extends ProductActionBuilder
{
    /**
     * @var string A PCRE to match the hierarchical field path without delimiter.
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/searchKeywords$';

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
        list(, $dataContainer) = $this->getLastFoundMatch();

        $actions = [];

        if ($this->hasRealTextChange($changedValue)) {
            $actions[] = ProductSetSearchKeywordsAction::ofKeywords(LocalizedSearchKeywords::fromArray($changedValue))
                ->setStaged($dataContainer === 'staged');
        }

        return $actions;
    }

    /**
     * Returns true if an language array has search keywords where the text is changed too!
     *
     * @param $changedValue
     *
     * @return bool
     */
    protected function hasRealTextChange($changedValue): bool
    {
        // Has every language ...
        return (bool)array_filter($changedValue, function (array $langSearchKeywords): bool {
            // real search keywords changes with a text change
            return (bool)array_filter($langSearchKeywords, function (array $searchKeyword): bool {
                return (bool)@$searchKeyword['text'];
            });
        });
    }
}
