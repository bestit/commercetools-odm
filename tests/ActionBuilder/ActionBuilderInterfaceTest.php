<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests ActionBuilderInterface.
 *
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder
 * @subpackage ActionBuilder
 */
class ActionBuilderInterfaceTest extends TestCase
{
    /**
     * Checks the constants of the interface.
     *
     * @return void
     */
    public function testConstants()
    {
        static::assertSame('~', ActionBuilderInterface::FILTER_DELIMITER);
    }
}
