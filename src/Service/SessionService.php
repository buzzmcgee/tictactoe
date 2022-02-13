<?php

namespace Buzz\TTT\Service;

use Buzz\TTT\Domain\Board;

class SessionService
{
    private $session;

    public function __construct($session) {
        $this->session = $session;
    }

    /**
     * @param Board $board
     * @return void
     */
    public function storeBoard(Board $board): bool
    {
        $this->session->set('board_layout', $board->getCurrentLayout());

        return true;
    }

    public function loadBoard(): ?Board
    {
        $boardLayout = $this->session->get('board_layout');

        if (!is_null($boardLayout)) {
            return new Board($boardLayout);
        }

        return null;
    }
}