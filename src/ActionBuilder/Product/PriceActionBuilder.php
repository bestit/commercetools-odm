<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

/**
 * Builder for price actions of a product.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Product
 * @version $id$
 */
abstract class PriceActionBuilder extends ProductActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/(masterVariant)/prices$';
}
