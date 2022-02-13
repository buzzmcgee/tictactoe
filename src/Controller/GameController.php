<?php

namespace Buzz\TTT\Controller;

use Buzz\TTT\Domain\Board;
use Buzz\TTT\Service\GameService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController
{
    private ?GameService $gameService;

    public function __construct(GameService $gameService) {
        $this->gameService = $gameService;
    }

    /**
     * Handle incoming request
     *
     * @param Request $request
     * @return void
     */
    public function handle(Request $request)
    {
        $uri = $request->getRequestUri();
        $board = null;
        $error = false;

        switch ($uri) {
            case '/new/player':
                $board = $this->gameService->createGame();
                break;
            case '/new/computer':
                $board = $this->gameService->createGame();
                break;
            case '/move':
                break;
            case '/reset':
                break;
            default:
        }

        $this->render($board, $error);
    }

    /**
     * Render current state of the game
     *
     * @param Board|null $board
     * @param bool $error
     * @return void
     */
    private function render(?Board $board, bool $error)
    {
        $response = new Response();

        try {
            $response->setCache(['no_cache' => true]);

        $response->send();
        } catch (Exception $e) {
            (new Response('', Response::HTTP_NOT_FOUND))->send();
        }
    }
}