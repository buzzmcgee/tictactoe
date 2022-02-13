<?php

namespace Buzz\TTT\Service;

use Buzz\TTT\Domain\Board;

class GameService
{

    /**
     * Create new Game and Board
     *
     * @return void
     */
    public function createGame(): Board
    {
        return new Board();
    }
}