<?php

namespace BestIt\CommercetoolsODM\Tests\Helper;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Helper\DocumentManagerAwareTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class DocumentManagerAwareTraitTest
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage Helper
 * @version $id$
 */
class DocumentManagerAwareTraitTest extends TestCase
{
    /**
     * The tested class.
     * @var DocumentManagerAwareTrait
     */
    private $fixture = null;

    /**
     * Sets up the test.
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = static::getMockForTrait(DocumentManagerAwareTrait::class);
    }

    /**
     * Checks the getter and setter.
     * @return void
     */
    public function testSetAndGetDocumentManager()
    {
        static::assertSame(
            $this->fixture,
            $this->fixture->setDocumentManager(
                $mock = static::createMock(DocumentManagerInterface::class)
            ),
            'Fluent interface failed.'
        );

        static::assertSame($mock, $this->fixture->getDocumentManager(), 'Object not persisted.');
    }
}
