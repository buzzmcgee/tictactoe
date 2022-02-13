<?php

namespace Buzz\TTT\Domain;

class Board
{
    public const HUMAN = 1;
    public const ENEMY = -1;
    public const EMPTY = 0;

    private array $cellLayout = [
        [0, 0, 0],
        [0, 0, 0],
        [0, 0, 0],
    ];
    private ?int $winner = null;
    private bool $tie = false;

    /**
     * @param array|int[][] $cellLayout
     */
    public function __construct(?array $cellLayout = null)
    {
        if (!is_null($cellLayout)) {
            $this->cellLayout = $cellLayout;
        }
    }
}