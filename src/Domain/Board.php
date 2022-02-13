<?php

namespace Buzz\TTT\Domain;

class Board
{

    private array $cellLayout = [
        [_, _, _],
        [_, _, _],
        [_, _, _],
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