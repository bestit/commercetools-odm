<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use Commercetools\Core\Model\Product\Product;

/**
 * Builder for price actions of a product.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Product
 * @version $id$
 */
abstract class PriceActionBuilder extends ActionBuilderAbstract
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staging)/(masterVariant)/prices$';

    /**
     * For which class is this description used?
     * @var string
     */
    protected $modelClass = Product::class;
}
