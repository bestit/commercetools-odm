<?php

/**
 * Created by PhpStorm.
 * User: bjoern.lange
 * Date: 11.04.2017
 * Time: 17:03
 */

namespace BestIt\CommercetoolsODM\Tests\Exception;

use BestIt\CommercetoolsODM\Exception\NotFoundException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use PHPUnit\Framework\TestCase;

/**
 * Class NotFoundExceptionTest
 *
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\Exception
 * @subpackage Exception
 */
class NotFoundExceptionTest extends TestCase
{
    /**
     * The tested class.
     *
     * @var NotFoundException
     */
    private $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new NotFoundException(uniqid(), mt_rand(1, 10000));
    }

    /**
     * Tests the instance.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ResponseException::class, $this->fixture);
    }
}
