<?php

namespace BestIt\CommercetoolsODM\Tests\Exception;

use BestIt\CommercetoolsODM\Exception\FilterException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use PHPUnit\Framework\TestCase;

/**
 * Class FilterExceptionTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Exception
 */
class FilterExceptionTest extends TestCase
{
    /**
     * Test extands base exception
     *
     * @return void
     */
    public function testExtends()
    {
        static::assertInstanceOf(ResponseException::class, new FilterException());
    }
}
