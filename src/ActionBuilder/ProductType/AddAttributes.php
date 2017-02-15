<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\ProductType\AttributeDefinition;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeAddAttributeDefinitionAction;

/**
 * Builds the action to add an attribute to a product type.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\ProductType
 * @version $id$
 */
class AddAttributes extends ProductTypeActionBuilder
{
    /**
     * The field name.
     * @var string
     */
    protected $fieldName = 'attributes';

    /**
     * Creates the update action for the given class and data.
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
        $attrActions = [];

        /** @var AttributeDefinition $attribute */
        if ($actualAttr = $sourceObject->getAttributes()) {
            foreach ($actualAttr as $attribute) {
                $searchName = $attribute->getName();

                $foundAttr = array_filter($oldData['attributes'] ?? [], function (array $oldAttr) use ($searchName) {
                    return @$oldAttr['name'] === $searchName;
                });

                if (!$foundAttr) {
                    $attrActions[] = ProductTypeAddAttributeDefinitionAction::ofAttribute($attribute);
                }
            }
        }

        return $attrActions;
    }
}
