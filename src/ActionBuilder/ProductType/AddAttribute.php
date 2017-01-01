<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
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
class AddAttribute extends ActionBuilderAbstract
{
    /**
     * The field name.
     * @var string
     */
    protected $fieldName = 'attributes';

    /**
     * For which class is this description used?
     * @var string
     */
    protected $modelClass = ProductType::class;

    /**
     * Creates the update action for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param ProductType $sourceObject
     * @return AbstractAction|void
     */
    public function createUpdateAction(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ) {
        /** @var AttributeDefinition $attribute */
        foreach ($sourceObject->getAttributes() as $attribute) {
            $searchName = $attribute->getName();

            $foundAttr = array_filter($oldData['attributes'] ?? [], function (array $oldAttr) use ($searchName) {
                return @$oldAttr['name'] === $searchName;
            });

            if (!$foundAttr) {
                return ProductTypeAddAttributeDefinitionAction::ofAttribute($attribute);
            }
        }
    }
}