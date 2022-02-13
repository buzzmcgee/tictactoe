<?php

namespace Buzz\Tests\Service;

use Buzz\TTT\Domain\Board;
use Buzz\TTT\Service\GameService;
use PHPUnit\Framework\TestCase;

class GameServiceTest extends TestCase
{
    private ?GameService $gameService = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gameService = new GameService();
    }

    /**
     * @return void
     * @dataProvider winnerDataSets
     */
    public function testUpdateWinner($winner, $set)
    {
        $board = new Board($set);

        if (!is_null($winner)) {
            $this->assertTrue($this->gameService->updateWinner($board));
            $this->assertTrue($board->hasWinner());
        } else {
            $this->assertFalse($this->gameService->updateWinner($board));
            $this->assertFalse($board->hasWinner());
        }
        $this->assertSame($winner, $board->getWinner());
    }

    /**
     * Variations on winning, losing and tied boards
     *
     * @return array[]
     */
    public function winnerDataSets(): array
    {
        define('X', 1);// HUMAN
        define('O', -1);// COMPUTER
        define('_', 0);// EMPTY

        return [
            'new' => [
                'winner' => null,
                'board' => [
                    [_, _, _],
                    [_, _, _],
                    [_, _, _],
                ]
            ],
            'mid game' => [
                'winner' => null,
                'board' => [
                    [_, _, _],
                    [O, X, X],
                    [_, _, _],
                ]
            ],
            'tie #1' => [
                'winner' => _,
                'board' => [
                    [X, X, O],
                    [O, X, X],
                    [X, O, O],
                ]
            ],
            'tie #2' => [
                'winner' => _,
                'board' => [
                    [X, X, O],
                    [O, O, X],
                    [X, X, O],
                ]
            ],
            'tie #3' => [
                'winner' => _,
                'board' => [
                    [X, X, O],
                    [O, O, X],
                    [X, O, X],
                ]
            ],
            'win row #1 human' => [
                'winner' => X,
                'board' => [
                    [X, X, X],
                    [_, _, _],
                    [_, _, _],
                ]
            ],
            'win row #2 human' => [
                'winner' => X,
                'board' => [
                    [_, _, _],
                    [X, X, X],
                    [_, _, _],
                ]
            ],
            'win row #3 human' => [
                'winner' => X,
                'board' => [
                    [_, _, _],
                    [_, _, _],
                    [X, X, X],
                ]
            ],
            'win row #1 computer' => [
                'winner' => O,
                'board' => [
                    [O, O, O],
                    [_, _, _],
                    [_, _, _],
                ]
            ],
            'win col #1 computer' => [
                'winner' => O,
                'board' => [
                    [O, _, _],
                    [O, _, _],
                    [O, _, _],
                ]
            ],
            'win col #2 computer' => [
                'winner' => O,
                'board' => [
                    [_, O, _],
                    [_, O, _],
                    [_, O, _],
                ]
            ],
            'win col #2 human' => [
                'winner' => X,
                'board' => [
                    [_, X, _],
                    [_, X, _],
                    [_, X, _],
                ]
            ],
            'win row #3 computer' => [
                'winner' => O,
                'board' => [
                    [_, _, O],
                    [_, _, O],
                    [_, _, O],
                ]
            ],
            'win diag ltr #1 human' => [
                'winner' => X,
                'board' => [
                    [X, _, _],
                    [_, X, _],
                    [_, _, X],
                ]
            ],
            'win diag rtl #1 enemy' => [
                'winner' => O,
                'board' => [
                    [_, _, O],
                    [_, O, _],
                    [O, _, _],
                ]
            ],
            'win col #3 computer w/ human noise' => [
                'winner' => O,
                'board' => [
                    [X, _, O],
                    [_, X, O],
                    [X, _, O],
                ]
            ],
            'win diag ltr human w/ computer noise' => [
                'winner' => X,
                'board' => [
                    [X, O, _],
                    [_, X, O],
                    [_, O, X],
                ]
            ],
            'win diag rtl computer w/ human noise' => [
                'winner' => O,
                'board' => [
                    [X, _, O],
                    [O, O, _],
                    [O, _, X],
                ]
            ],
        ];
    }
    /**
     * @return void
     * @dataProvider validationDataSets
     */
    public function testHasValidLayout($set, $isValid)
    {
        $this->assertSame($isValid, $this->gameService->isValidLayout($set));
    }

    /**
     * Set of valid/invalid boards and expected result
     *
     * @return array[]
     */
    public function validationDataSets(): array
    {
        define('X', 1);// HUMAN
        define('O', -1);// COMPUTER
        define('_', 0);// EMPTY

        return [
            'new' => [
                'board' => [
                    [_, _, _],
                    [_, _, _],
                    [_, _, _],
                ],
                'valid' => true,
            ],
            'used' => [
                'board' => [
                    [_, _, _],
                    [O, X, X],
                    [_, _, _],
                ],
                'valid' => true,
            ],
            'tie #X' => [
                'board' => [
                    [X, X, O],
                    [O, X, X],
                    [X, O, O],
                ],
                'valid' => true,
            ],
            'tie #2' => [
                'board' => [
                    [X, X, O],
                    [O, O, X],
                    [X, X, O],
                ],
                'valid' => true,
            ],
            'tie #3' => [
                'board' => [
                    [X, X, O],
                    [O, O, X],
                    [X, O, X],
                ],
                'valid' => true,
            ],
            'wrong turn count' => [
                'board' => [
                    [X, X, _],
                    [_, _, _],
                    [_, _, _],
                ],
                'valid' => false,
            ],
            'foreign value' => [
                'board' => [
                    [X, X, O],
                    [O, O, 2],
                    [X, O, X],
                ],
                'valid' => false,
            ],
        ];
    }

    /**
     * @return void
     * @dataProvider nextBestMoveVariations
     */
    public function testBestNextMove($currentSet, $nextSet)
    {
        $this->markTestIncomplete();

        $board = new Board($currentSet);
        $nextMove = $this->gameService->bestNextMove($board);
        $this->gameService->makeMove($board, $nextMove[0], $nextMove[1], O);

        $this->assertSame($nextSet, $board->getCurrentLayout());
    }

    /**
     * Current boards and expected next move results
     *
     * @return int[][][][]
     */
    public function nextBestMoveVariations(): array
    {
        define('X', 1);// HUMAN
        define('O', -1);// COMPUTER
        define('_', 0);// EMPTY

        return [
            'computer start ul' => [
                'current' => [
                    [_, _, _],
                    [_, _, _],
                    [_, _, _],
                ],
                'next' => [
                    [O, _, _],
                    [_, _, _],
                    [_, _, _],
                ]
            ],
            'human start ul' => [
                'current' => [
                    [X, _, _],
                    [_, _, _],
                    [_, _, _],
                ],
                'next' => [
                    [X, _, _],
                    [_, O, _],
                    [_, _, _],
                ]
            ],
        ];
    }
}
