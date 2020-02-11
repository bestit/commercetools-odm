<?php

namespace BestIt\CommercetoolsODM\Tests\Exception;

use BestIt\CommercetoolsODM\Exception\RemoveCategoryException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use PHPUnit\Framework\TestCase;

/**
 * Class RemoveCategoryExceptionTest
 *
 * @author Martin Knoop <martin.knoop@bestit.de>
 * @package BestIt\CommercetoolsODM\Tests\Exception
 */
class RemoveCategoryExceptionTest extends TestCase
{
    /**
     * Test extends base exception
     *
     * @return void
     */
    public function testExtends()
    {
        static::assertInstanceOf(ResponseException::class, new RemoveCategoryException());
    }
}
