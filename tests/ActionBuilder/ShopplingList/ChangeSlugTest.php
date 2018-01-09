<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\ActionBuilder\ShoppingList\ChangeSlug;
use BestIt\CommercetoolsODM\ActionBuilder\ShoppingList\ShoppingListActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\ShoppingList\ShoppingList;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListSetSlugAction;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangeSlugTest
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ShoppingList
 */
class ChangeSlugTest extends TestCase
{
    use SupportTestTrait;

    /**
     * @var ChangeSlug|void The tested class.
     */
    protected $fixture;

    /**
     * Returns an array with the assertions for the support method.
     *
     * The First Element is the field path, the second element is the reference class and the optional third value
     * indicates the return value of the support method.
     *
     * @return array
     */
    public function getSupportAssertions(): array
    {
        return [
            ['slug', ShoppingList::class, true],
            ['slugs', ShoppingList::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @retern void
     */
    protected function setUp()
    {
        $this->fixture = new ChangeSlug();
    }

    /**
     * Checks if the action is rendered correctly.
     *
     * @return void
     */
    public function testCreateUpdateActions()
    {
        $actions = $this->fixture->createUpdateActions(
            [
                'de' => $newGer = uniqid(),
                'fr' => null,
                'en' => $newEn = uniqid()
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'slug' => [
                    'de' => uniqid(),
                    'fr' => uniqid(),
                    'en' => uniqid()
                ]
            ],
            new ShoppingList()
        );

        static::assertCount(1, $actions, 'Wrong action count.');

        /** @var $action ShoppingListSetSlugAction */
        static::assertInstanceOf(
            ShoppingListSetSlugAction::class,
            $action = $actions[0],
            'Wrong instance.'
        );

        static::assertSame(
            ['de' => $newGer, 'en' => $newEn],
            $action->getSlug()->toArray(),
            'Wrong result array.'
        );
    }

    /**
     * Checks the instance type.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ShoppingListActionBuilder::class, $this->fixture);
    }
}
