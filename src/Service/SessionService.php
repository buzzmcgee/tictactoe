<?php

namespace Buzz\TTT\Service;

use Buzz\TTT\Domain\Board;

class SessionService
{
    /** @var mixed */
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

    /**
     * Create board from session data
     *
     * @return Board|null
     */
    public function loadBoard(): ?Board
    {
        $boardLayout = $this->session->get('board_layout');

        if (!is_null($boardLayout)) {
            return new Board($boardLayout);
        }

        return null;
    }

    /**
     * Remove board layout from session
     *
     * @return void
     */
    public function clearBoard(): void
    {
        $this->session->remove('board_layout');
    }
}