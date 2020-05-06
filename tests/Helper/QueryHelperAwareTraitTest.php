<?php

namespace BestIt\CommercetoolsODM\Tests\Helper;

use BestIt\CommercetoolsODM\Helper\GeneratorQueryHelper;
use BestIt\CommercetoolsODM\Helper\QueryHelperAwareTrait;
use Commercetools\Commons\Helper\QueryHelper;
use PHPUnit\Framework\TestCase;

/**
 * Class QueryHelperAwareTraitTest
 *
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\Helper
 * @subpackage Helper
 */
class QueryHelperAwareTraitTest extends TestCase
{
    /**
     * The tested class.
     *
     * @var QueryHelperAwareTrait
     */
    private $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = static::getMockForTrait(QueryHelperAwareTrait::class);
    }

    /**
     * Checks the getter and setter.
     *
     * @return void
     */
    public function testSetAndGetQueryHelper()
    {
        static::assertSame(
            $this->fixture,
            $this->fixture->setQueryHelper($mock = static::createMock(QueryHelper::class)),
            'Fluent interface failed.'
        );

        static::assertSame($mock, $this->fixture->getQueryHelper(), 'Object not persisted.');
    }

    /**
     * Checks the getter and setter.
     *
     * @return void
     */
    public function testSetAndGetGeneratorQueryHelper()
    {
        static::assertSame(
            $this->fixture,
            $this->fixture->setGeneratorQueryHelper($mock = static::createMock(GeneratorQueryHelper::class)),
            'Fluent interface failed.'
        );

        static::assertSame($mock, $this->fixture->getGeneratorQueryHelper(), 'Object not persisted.');
    }
}
