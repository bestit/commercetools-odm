<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use Commercetools\Core\Model\Customer\Customer;

/**
 * The ase class for the customer action builders.s
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Customer
 * @subpackage ActionBuilder\Customer
 */
abstract class CustomerActionBuilder extends ActionBuilderAbstract
{
    /**
     * For which class is this description used?
     *
     * @var string
     */
    protected $modelClass = Customer::class;
}
