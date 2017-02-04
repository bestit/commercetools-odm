<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeRemoveAttributeDefinitionAction;

class RemoveAttributes extends ActionBuilderAbstract
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
        foreach ($oldData['attributes'] ?? [] as $oldAttr) {
            $searchName = $oldAttr['name'];

            $foundAttr = array_filter(
                ($attrs = $sourceObject->getAttributes()) ? $attrs->toArray() : [],
                function (array $newAttr) use ($searchName) {
                    return @$newAttr['name'] === $searchName;
                }
            );

            if (!$foundAttr) {
                $attrActions[] = ProductTypeRemoveAttributeDefinitionAction::ofName($searchName);
            }
        }

        return $attrActions;
    }
}
