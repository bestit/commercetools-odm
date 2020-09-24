<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

/**
 * Resolve attribute values.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
trait ResolveAttributeValueTrait
{
    /**
     * Return an resolved attribute value
     *
     * @param mixed|null $value
     *
     * @return mixed|null
     */
    protected function resolveAttributeValue($value)
    {
        // If value is an array with a key field, it must be a ENUM or LENUM.
        // Therefor, Commercetools expect the key as value only.
        if (is_array($value) && array_key_exists('key', $value)) {
            return $value['key'];
        }

        return $value;
    }
}
