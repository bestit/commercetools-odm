<?php

namespace BestIt\CommercetoolsODM\Tests\Helper;

use BestIt\CommercetoolsODM\Helper\PriceHelperTrait;
use PHPUnit\Framework\TestCase;

/**
 * Test for price helper trait.
 *
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Helper
 */
class PriceHelperTraitTest extends TestCase
{
    /**
     * Test that function return correct price.
     * @return void
     */
    public function testGetCorrectPriceTier()
    {
        $fixture = $this->getMockForTrait(PriceHelperTrait::class);

        $method = (new \ReflectionObject($fixture))->getMethod('getCorrectPrice');
        $method->setAccessible(true);

        $currencyCode = 'EUR';

        $price = [
            'value' => [
                'currencyCode' => $currencyCode,
                'centAmount' => $centAmountDefault = 50000
            ],
            'tiers' => [
                [
                    'minimumQuantity' => $minimumQuantity1 = 10,
                    'value' => [
                        'currencyCode' => $currencyCode,
                        'centAmount' => $centAmount1 = 40000
                    ]
                ],
                [
                    'minimumQuantity' => $minimumQuantity3 = 30,
                    'value' => [
                        'currencyCode' => $currencyCode,
                        'centAmount' => $centAmount3 = 20000
                    ]
                ],
                [
                    'minimumQuantity' => $minimumQuantity2 = 20,
                    'value' => [
                        'currencyCode' => $currencyCode,
                        'centAmount' => $centAmount2 = 30000
                    ]
                ]
            ]
        ];

        self::assertEquals(
            ['currencyCode' => 'EUR', 'centAmount' => $centAmountDefault],
            $method->invoke($fixture, $price, 5)
        );
        self::assertEquals(
            ['currencyCode' => 'EUR', 'centAmount' => $centAmount1],
            $method->invoke($fixture, $price, 10)
        );
        self::assertEquals(
            ['currencyCode' => 'EUR', 'centAmount' => $centAmount1],
            $method->invoke($fixture, $price, 11)
        );
        self::assertEquals(
            ['currencyCode' => 'EUR', 'centAmount' => $centAmount2],
            $method->invoke($fixture, $price, 20)
        );
        self::assertEquals(
            ['currencyCode' => 'EUR', 'centAmount' => $centAmount2],
            $method->invoke($fixture, $price, 22)
        );
        self::assertEquals(
            ['currencyCode' => 'EUR', 'centAmount' => $centAmount3],
            $method->invoke($fixture, $price, 33)
        );
    }
}
