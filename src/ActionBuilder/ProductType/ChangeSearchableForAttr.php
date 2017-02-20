<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeChangeIsSearchableAction;

/**
 * Change the searchable value for an attribute def.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\ProductType
 * @version $id$
 */
class ChangeSearchableForAttr extends ProductTypeActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     * @var string
     */
    protected $complexFieldFilter = 'attributes/(\d)+/isSearchable$';

    /**
     * Creates the update actions for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param ProductType $sourceObject
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

        list(, $attrIndex) = $this->getLastFoundMatch();

        // If there is an old entry, then there should be just the update change.
        if (@$oldData['attributes'][$attrIndex]['name']) {
            $actions[] = ProductTypeChangeIsSearchableAction::ofAttributeNameAndIsSearchable(
                $oldData['attributes'][$attrIndex]['name'],
                $changedValue
            );
        }

        return $actions;
    }
}
