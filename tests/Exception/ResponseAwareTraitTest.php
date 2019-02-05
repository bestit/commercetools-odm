<?php

namespace BestIt\CommercetoolsODM\Tests\Exception;

use BestIt\CommercetoolsODM\Exception\ResponseAwareTrait;
use Commercetools\Core\Response\ErrorResponse;
use PHPUnit\Framework\TestCase;

/**
 * Class ResponseAwareTraitTest
 *
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\Exception
 * @subpackage Exception
 */
class ResponseAwareTraitTest extends TestCase
{
    /**
     * The tested class.
     *
     * @var ResponseAwareTrait
     */
    private $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = static::getMockForTrait(ResponseAwareTrait::class);
    }

    /**
     * Checks the getter and setter.
     *
     * @return void
     */
    public function testSetAndGetCorrelationId()
    {
        static::assertSame(
            $this->fixture,
            $this->fixture->setCorrelationId($mock = uniqid()),
            'Fluent interface failed.'
        );

        static::assertSame($mock, $this->fixture->getCorrelationId(), 'Object not persisted.');
    }

    /**
     * Checks the getter and setter.
     *
     * @return void
     */
    public function testSetAndGetResponse()
    {
        static::assertSame(
            $this->fixture,
            $this->fixture->setResponse($mock = static::createMock(ErrorResponse::class)),
            'Fluent interface failed.'
        );

        static::assertSame($mock, $this->fixture->getResponse(), 'Object not persisted.');
    }
}
