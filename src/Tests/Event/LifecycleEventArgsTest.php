<?php

namespace BestIt\CommercetoolsODM\Tests\Event;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Event\LifecycleEventArgs;
use Commercetools\Core\Model\Product\Product;
use Doctrine\Common\EventArgs;
use PHPUnit\Framework\TestCase;

/**
 * Class LifecycleEventArgsTest
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage Event
 * @version $id$
 */
class LifecycleEventArgsTest extends TestCase
{
    /**
     * The used document in the fixture.
     * @var null
     */
    private $document = null;

    /**
     * The used document manager in the fixture.
     * @var DocumentManagerInterface
     */
    private $documentManager = null;

    /**
     * The tested class.
     * @var LifecycleEventArgs
     */
    private $fixture = null;

    /**
     * Returns the prop names for testing.
     * @return array
     */
    public function getGetterNames(): array
    {
        return [['document'], ['documentManager']];
    }

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new LifecycleEventArgs(
            $this->document = new Product(),
            $this->documentManager = static::createMock(DocumentManagerInterface::class)
        );
    }

    /**
     * Checks if the getter returns the correct value.
     * @dataProvider getGetterNames
     * @param string $propName
     */
    public function testGetter(string $propName)
    {
        static::assertSame($this->{$propName}, $this->fixture->{'get' . ucfirst($propName)}());
    }

    /**
     * Checks the instance.
     * @return void
     */
    public function testType()
    {
        static::assertInstanceOf(EventArgs::class, $this->fixture);
    }
}
