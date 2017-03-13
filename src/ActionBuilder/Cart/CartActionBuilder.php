<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use Commercetools\Core\Model\Cart\Cart;

/**
 * The ase class for the customer action builders.s
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Cart
 * @version $id$
 */
abstract class CartActionBuilder extends ActionBuilderAbstract
{
    /**
     * For which class is this description used?
     * @var string
     */
    protected $modelClass = Cart::class;
}
