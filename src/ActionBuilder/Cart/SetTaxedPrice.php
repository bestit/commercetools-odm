<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Request\Carts\Command\CartSetCartTotalTaxAction;

/**
 * @package BestIt\CommercetoolsODM\ActionBuilder\Cart
 */
class SetTaxedPrice extends CartActionBuilder
{
    /**
     * @var string
     */
    protected $fieldName = 'taxedPrice';

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

        if (
            !isset(
                $changedData['taxedPrice'],
                $changedData['taxedPrice']['taxPortions'],
                $changedData['taxedPrice']['totalGross']
            )
        ) {
            return $actions;
        }

        /** @var Cart $sourceObject */
        switch ($sourceObject->getTaxMode()) {
            case Cart::TAX_MODE_EXTERNAL_AMOUNT:
                $actions[] = CartSetCartTotalTaxAction::fromArray([
                    'externalTotalGross' => $changedData['taxedPrice']['totalGross'],
                    'externalTaxPortions' => $changedData['taxedPrice']['taxPortions'],
                ]);

                break;
            default:
                break;
        }

        return $actions;
    }
}
