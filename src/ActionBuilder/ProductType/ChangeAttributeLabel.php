<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeChangeLabelAction;

/**
 * ActionBuilder to change a label of a product type.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ProductType
 */
class ChangeAttributeLabel extends ProductTypeActionBuilder
{
    /**
     * @var string The field name.
     */
    protected $complexFieldFilter = '^attributes\/(\w+)\/label$';

    /**
     * Creates the update actions for the given class and data.
     *
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
        list(, $attrIndex) = $this->getLastFoundMatch();

        $actions = [];

        if ((@$oldData['attributes'][$attrIndex]) && ($def = $sourceObject->getAttributes()->getAt($attrIndex))) {
            $actions[] = ProductTypeChangeLabelAction::ofAttributeNameAndLabel(
                $def->getName(),
                LocalizedString::fromArray(array_filter($changedValue))
            );
        }

        return $actions;
    }
}
