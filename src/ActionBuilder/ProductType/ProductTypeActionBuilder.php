<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use Commercetools\Core\Model\ProductType\ProductType;

/**
 * Base type for the product type action builders.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\ProductType
 * @version $id$
 */
abstract class ProductTypeActionBuilder extends ActionBuilderAbstract
{
    /**
     * For which class is this description used?
     * @var string
     */
    protected $modelClass = ProductType::class;
}
