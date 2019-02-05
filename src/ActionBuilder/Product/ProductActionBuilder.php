<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use Commercetools\Core\Model\Product\Product;

/**
 * Base class for the product builder.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
abstract class ProductActionBuilder extends ActionBuilderAbstract
{
    /**
     * For which class is this description used?
     *
     * @var string
     */
    protected $modelClass = Product::class;
}
