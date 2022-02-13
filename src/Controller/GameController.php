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

    /**
     * Add necessary services
     *
     * @param GameService $gameService
     * @param SessionService $sessionService
     * @param Environment $templateEnv
     * @return self
     */
    public function addServices(
        GameService $gameService,
        SessionService $sessionService,
        Environment $templateEnv
    ): self {
        $this->gameService = $gameService;
        $this->sessionService = $sessionService;
        $this->templateEnv = $templateEnv;

        return $this;
    }

    /**
     * Handle incoming request
     *
     * @param Request $request
     * @return void
     */
    public function handle(Request $request)
    {
        if ((!isset($this->gameService, $this->sessionService, $this->templateEnv))) {
            $this->sendNotFound();
        }

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

                $coordinates = $this->gameService->bestNextMove($board);
                $this->gameService->makeMove($board, $coordinates[0], $coordinates[1], O);

                $this->sessionService->storeBoard($board);
                break;
            case '/move':
                $board = $this->sessionService->loadBoard();
                if (!$this->gameService->isValidLayout($board->getCurrentLayout())) {
                    $error = true;
                    break;
                }

                $coordinates = $this->getCoordinates($request);
                $error = !$this->gameService->makeMove($board, $coordinates[0], $coordinates[1], X);

                if (!$error) {
                    $this->gameService->updateWinner($board);

                    if (!$board->hasWinner()) {
                        $coordinates = $this->gameService->bestNextMove($board);
                        if (is_null($coordinates)) {
                            $board->setTie();
                        }

                        if (!$board->isTie()) {
                            $this->gameService->makeMove($board, $coordinates[0], $coordinates[1], O);

                            $this->gameService->updateWinner($board);
                        }
                    }

                    $this->sessionService->storeBoard($board);
                }
                break;
            case '/reset':
                $this->sessionService->clearBoard();
                break;
            default:
                $board = $this->sessionService->loadBoard();
                if (!$this->gameService->isValidLayout($board->getCurrentLayout())) {
                    $error = true;
                    break;
                }

                $this->gameService->updateWinner($board);
        }

        $this->render($board, $error);
    }

    /**
     * Get Coordinates from Request
     *
     * @param Request $request
     * @return ?array
     */
    public function getCoordinates(Request $request): ?array
    {
        $parameterValue = $request->get('coordinates');

        if (is_null($parameterValue)) {
            return null;
        }

        $matches = [];
        if (preg_match('/^([0-2]):([0-2])$/', $parameterValue, $matches)) {
            $row = $matches[1];
            $col = $matches[2];
            return [intval($row), intval($col)];
        }

        return null;
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
                'cellLayout' => $this->gameService->getCellLayoutRender($board->getCurrentLayout()),
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
            $this->sendNotFound();
        }
    }

    /**
     * Send a HTTP_NOT_FOUND Response
     *
     * @return void
     */
    private function sendNotFound(): void
    {
        (new Response('', Response::HTTP_NOT_FOUND))->send();
    }
}