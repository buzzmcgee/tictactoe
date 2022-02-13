<?php

use Buzz\TTT\Controller\GameController;
use Buzz\TTT\Service\GameService;
use Symfony\Component\HttpFoundation\Request;

require_once "../vendor/autoload.php";

$request = Request::createFromGlobals();

(new GameController(
    new GameService()
))->handle($request);