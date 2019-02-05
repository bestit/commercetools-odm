<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Helper;

/**
 * Helper to get correct price tier.
 *
 * @author tkellner <tim.kellner@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Helper
 */
trait PriceHelperTrait
{
    /**
     * Get correct price tier.
     *
     * @param array $price Product price. Represents an array of a commercetools price object.
     * @param int $quantity Chosed quantity.
     *
     * @return array
     */
    private function getCorrectPrice(array $price, int $quantity): array
    {
        $matchedPrice = $price['value'];
        
        if (array_key_exists('tiers', $price)) {
            // Force that prices are sorted.
            usort(
                $price['tiers'],
                function (array $a, array $b) {
                    return $a['minimumQuantity'] <=> $b['minimumQuantity'];
                }
            );

            // Check if other matching price tiers exist
            array_walk(
                $price['tiers'],
                function (array $priceTier) use (&$matchedPrice, $quantity) {
                    if ($priceTier['minimumQuantity'] <= $quantity) {
                        $matchedPrice = $priceTier['value'];
                    }
                }
            );
        }

        return $matchedPrice;
    }
}
