<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Helper;

use Commercetools\Core\Model\Common\Attribute;
use Commercetools\Core\Model\Common\AttributeCollection;

/**
 * Helper to get attribute by name
 *
 * @package BestIt\CommercetoolsODM\Helper
 */
trait AttributeFinderTrait
{
    /**
     * Get attribute by name or null if not found
     * Alternative for the sdk "->getByName" method which can throw exceptions
     *
     * @param AttributeCollection $collection
     * @param string $name
     *
     * @return Attribute|null
     */
    private function getAttributeByName(AttributeCollection $collection, string $name)
    {
        foreach ($collection as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }

        return null;
    }
}
