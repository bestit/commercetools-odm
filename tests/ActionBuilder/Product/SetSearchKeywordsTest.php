<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Product\SetSearchKeywords;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductSetSearchKeywordsAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use function sprintf;

/**
 * Checks if the setsearchkeywords action is built.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class SetSearchKeywordsTest extends TestCase
{
    use SupportTestTrait;

    /**
     * @var SetSearchKeywords|PHPUnit_Framework_MockObject_MockObject|null
     */
    protected $fixture;

    /**
     * Returns assertions for the create call.
     *
     * @return array
     */
    public function getCreateAssertions(): array
    {
        // isStaging, given value, success value
        return [
            // staged value where only the tokenizer was "changed" but we need the text as well.
            [
                true,
                [
                    'de' => [
                        [
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ]
                ],
                []
            ],
            // current value where only the tokenizer was "changed" but we need the text as well.
            [
                false,
                [
                    'de' => [
                        [
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ]
                ],
                []
            ],
            // staged correct value
            [
                true,
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ]
                ],
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ]
                ]
            ],
            // current correct value
            [
                false,
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ]
                ],
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ]
                ]
            ],
            // staged correct value, with deleted old values
            [
                true,
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ],
                        null,
                        null
                    ],
                ],
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ]
                ]
            ],
            // current correct value, with deleted old values
            [
                true,
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ],
                        null,
                        null
                    ],
                ],
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ]
                ]
            ],
            // staged correct value, with deleted languages
            [
                true,
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ],
                    null,
                    null
                ],
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ]
                ]
            ],
            // current correct value, with deleted old values
            [
                true,
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ],
                    null,
                    null
                ],
                [
                    'de' => [
                        [
                            'text' => 'foobar',
                            'suggestTokenizer' => ['type' => 'whitespace']
                        ]
                    ]
                ]
            ],

        ];
    }

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
            ['masterData/current/searchKeywords', Product::class, true],
            ['masterData/staged/searchKeywords', Product::class, true],
            ['masterData/current/variants/foo/searchKeywords', Product::class],
            ['masterData/staged/variants/foo/searchKeywords', Product::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new SetSearchKeywords();
    }

    /**
     * Checks if the action is rendered correctly.
     *
     * @dataProvider getCreateAssertions
     *
     * @param bool $isForStaging
     * @param array $givenValue
     * @param array $resultValue
     *
     * @return void
     */
    public function testCreateUpdateActions(bool $isForStaging, array $givenValue, array $resultValue = [])
    {
        $this->fixture->supports(
            sprintf('masterData/%s/searchKeywords', $isForStaging ? 'staged' : 'current'),
            Product::class
        );

        $actions = $this->fixture->createUpdateActions(
            $givenValue,
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        );

        if (!$resultValue) {
            static::assertSame([], $actions, 'There should be no action.');
        } else {
            /** @var $action ProductSetSearchKeywordsAction */
            static::assertCount(1, $actions, 'Wrong action count.');

            static::assertInstanceOf(
                ProductSetSearchKeywordsAction::class,
                $action = $actions[0],
                'Wrong instance.'
            );

            static::assertSame($resultValue, $action->getSearchKeywords()->toArray(), 'Wrong search keywords.');
            static::assertSame($isForStaging, $action->getStaged(), 'Wrong staged status.');
        }
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ProductActionBuilder::class, $this->fixture);
    }
}
