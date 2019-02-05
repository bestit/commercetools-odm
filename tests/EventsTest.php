<?php

namespace BestIt\CommercetoolsODM\Tests;

use BestIt\CommercetoolsODM\Events;
use PHPUnit\Framework\TestCase;
use function Funct\Strings\toUpper;
use function Funct\Strings\underscore;

/**
 * Class EventsTest
 *
 * @author blange <lange@bestit-online.de>
 * @categeory Tests
 * @package BestIt\CommercetoolsODM\Tests
 */
class EventsTest extends TestCase
{
    /**
     * Checks the constants of the class.
     *
     * @return void
     */
    public function testConstants()
    {
        $map = [
            'onConflict',
            'onFlush',
            'prePersist',
            'preRemove',
            'preUpdate',
            'postRemove',
            'postPersist',
            'postLoad'
        ];

        array_walk($map, function (string $constValue) {
            $constName = toUpper(underscore($constValue));

            static::assertSame($constValue, constant(sprintf('%s::%s', Events::class, $constName)));
        });
    }
}
