<?php
/**
 * Created by PhpStorm.
 * User: bjoern.lange
 * Date: 11.04.2017
 * Time: 17:03
 */

namespace BestIt\CommercetoolsODM\Tests\Exception;

use BestIt\CommercetoolsODM\Exception\ConflictException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use PHPUnit\Framework\TestCase;

/**
 * Class ConflictExceptionTest
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage Exception
 * @version $id$
 */
class ConflictExceptionTest extends TestCase
{
    /**
     * The tested class.
     * @var ConflictException
     */
    private $fixture = null;

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new ConflictException(uniqid(), mt_rand(1, 10000));
    }

    /**
     * Tests the instance.
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ResponseException::class, $this->fixture);
    }
}
