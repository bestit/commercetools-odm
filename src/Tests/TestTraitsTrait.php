<?php

namespace BestIt\CommercetoolsODM\Tests;

/**
 * Makes it easier to test traits.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests
 * @subpackage Tests
 */
trait TestTraitsTrait
{
    use TraitAssertTrait;

    /**
     * The tested class.
     *
     * @var null|mixed
     */
    protected $fixture = null;

    /**
     * Returns the names of the used traits.
     *
     * @return array
     */
    abstract protected function getUsedTraitNames(): array;

    /**
     * Checks the used traits.
     *
     * @return void
     */
    public function testUsedTraits()
    {
        array_map(
            function (string $traitName) {
                static::assertTraitUsage($traitName, $this->fixture);
            },
            $this->getUsedTraitNames()
        );
    }
}
