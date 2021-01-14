<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderComposite;
use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderFactoryInterface;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Product\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Tests the ActionBuilderComposite.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder
 * @todo For now just a test against the last error in the action builder composte. We should test more!
 */
class ActionBuilderCompositeTest extends TestCase
{
    /**
     * The injected action builder factory.
     *
     * @var MockObject|null|ActionBuilderFactoryInterface
     */
    private $factory;

    /**
     * The tested class.
     *
     * @var ActionBuilderComposite|null
     */
    private $fixture;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new ActionBuilderComposite(
            $this->factory = $this->createMock(ActionBuilderFactoryInterface::class)
        );
    }

    /**
     * Checks that no php warning is triggered if there are no actions.
     *
     * @return void
     */
    public function testCreateUpdateActionsEmptyNoWarning()
    {
        static::assertSame(
            [],
            $this->fixture->createUpdateActions(
                $this->createMock(ClassMetadataInterface::class),
                [],
                [],
                Product::fromArray([])
            )
        );
    }
}
