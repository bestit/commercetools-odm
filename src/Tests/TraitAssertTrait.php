<?php

namespace BestIt\CommercetoolsODM\Tests;

/**
 * Helps checking the usage of traits.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests
 * @subpackage Tests
 */
trait TraitAssertTrait
{
    /**
     * Asserts that a haystack contains a needle.
     *
     * @since Method available since Release 2.1.0
     *
     * @param mixed $needle
     * @param mixed $haystack
     * @param string $message
     * @param bool $ignoreCase
     * @param bool $checkForObjectIdentity
     * @param bool $checkForNonObjectIdentity
     *
     * @return void
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
     *
     * @param string $traitName
     * @param mixed $object
     *
     * @return mixed
     */
    public static function assertTraitUsage(string $traitName, $object)
    {
        return static::assertContains($traitName, class_uses($object));
    }
}
