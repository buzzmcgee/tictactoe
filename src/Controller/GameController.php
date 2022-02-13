<?php

namespace Buzz\TTT\Controller;

use Buzz\TTT\Domain\Board;
use Buzz\TTT\Service\GameService;
use Buzz\TTT\Service\SessionService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class GameController
{
    private ?GameService $gameService;
    private ?SessionService $sessionService;
    private ?Environment $templateEnv;

    public function __construct(GameService $gameService, SessionService $sessionService, Environment $templateEnv) {
        $this->gameService = $gameService;
        $this->sessionService = $sessionService;
        $this->templateEnv = $templateEnv;
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

                $this->sessionService->storeBoard($board);
                break;
            case '/new/computer':
                $board = $this->gameService->createGame();

                $this->sessionService->storeBoard($board);
                break;
            case '/move':
                $board = $this->sessionService->loadBoard();
                break;
            case '/reset':
                $this->sessionService->clearBoard();
                break;
            default:
                $board = $this->sessionService->loadBoard();
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

        $parameters = ['showNewGame' => true];
        if (!is_null($board)) {
            $parameters = [
                'showNewGame' => false,
                'cellLayout' => $board->getCellLayoutRender(),
                'winner' => $board->getWinner(),
                'error' => $error,
            ];
        }

        try {
            $content = $this->templateEnv->render('main.html.twig', $parameters);

            $response->setContent($content);
            $response->setCache(['no_cache' => true]);

            $response->send();
        } catch (Exception $e) {
            (new Response('', Response::HTTP_NOT_FOUND))->send();
        }
    }
}