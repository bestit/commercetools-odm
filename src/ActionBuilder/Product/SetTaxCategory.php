<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\TaxCategory\TaxCategoryReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductSetTaxCategoryAction;

/**
 * Sets the tax category.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 * @subpackage ActionBuilder\Product
 */
class SetTaxCategory extends ProductActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^taxCategory$';

    /**
     * Creates the update actions for the given class and data.
     *
     * @todo The documentation does not tell of the staged param in the action. what does that mean?
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
        $action = new ProductSetTaxCategoryAction();

        if (!is_array($changedValue)) {
            return [];
        }

        if (isset($changedValue['id']) && !empty($changedValue['id'])) {
            $action->setTaxCategory(TaxCategoryReference::ofId($changedValue['id']));
        } elseif (isset($changedValue['key']) && !empty($changedValue['key'])) {
            $action->setTaxCategory(TaxCategoryReference::ofKey($changedValue['key']));
        }

        return [$action];
    }
}
