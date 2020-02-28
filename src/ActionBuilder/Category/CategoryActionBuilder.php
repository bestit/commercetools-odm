<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Category;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use Commercetools\Core\Model\Category\Category;

/**
 * Base class for the category builder.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Category
 */
abstract class CategoryActionBuilder extends ActionBuilderAbstract
{
    /**
     * For which class is this description used?
     *
     * @var string
     */
    protected $modelClass = Category::class;
}
