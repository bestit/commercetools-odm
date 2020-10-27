<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Request\Carts\Command\CartChangeTaxModeAction;

/**
 * @package BestIt\CommercetoolsODM\ActionBuilder\Cart
 */
class SetTaxMode extends CartActionBuilder
{
    /**
     * @var string
     */
    protected $fieldName = 'taxMode';

    /**
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     *
     * @return array
     */
    public function createUpdateActions($changedValue, ClassMetadataInterface $metadata, array $changedData, array $oldData, $sourceObject): array
    {
        $actions = [];

        if (!isset($changedData['taxMode'])) {
            return $actions;
        }

        /** @var Cart $sourceObject */
        switch ($sourceObject->getTaxMode()) {
            case Cart::TAX_MODE_EXTERNAL_AMOUNT:
                $actions[] = CartChangeTaxModeAction::fromArray([
                    'taxMode' => Cart::TAX_MODE_EXTERNAL_AMOUNT,
                ]);

                break;
            default:
                break;
        }

        return $actions;
    }
}
