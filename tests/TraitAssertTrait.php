<?php

namespace BestIt\CommercetoolsODM\Tests;

/**
 * Helps checking the usage of traits.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Tests
 * @version $id$
 */
trait TraitAssertTrait
{
    /**
     * Asserts that a haystack contains a needle.
     * @param mixed $needle
     * @param mixed $haystack
     * @param string $message
     * @param bool $ignoreCase
     * @param bool $checkForObjectIdentity
     * @param bool $checkForNonObjectIdentity
     * @since Method available since Release 2.1.0
     */
    abstract public static function assertContains(
        $needle,
        $haystack,
        $message = '',
        $ignoreCase = false,
        $checkForObjectIdentity = true,
        $checkForNonObjectIdentity = false
    );

    /**
     * Checks if the trait is used.
     * @param string $traitName
     * @param $object
     * @return mixed
     */
    static public function assertTraitUsage(string $traitName, $object)
    {
        return static::assertContains($traitName, class_uses($object));
    }
}
