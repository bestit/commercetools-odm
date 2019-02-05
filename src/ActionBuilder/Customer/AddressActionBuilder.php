<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

/**
 * Abstracts the support of the address array.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Customer
 */
abstract class AddressActionBuilder extends CustomerActionBuilder
{
    /**
     * Matches to the address element.
     *
     * @var string
     */
    protected $complexFieldFilter = '^addresses/(\d*)$';
}
