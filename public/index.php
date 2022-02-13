<?php

use Buzz\TTT\Controller\GameController;
use Buzz\TTT\Service\GameService;
use Buzz\TTT\Service\SessionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once "../vendor/autoload.php";
require_once "../const.php";

$request = Request::createFromGlobals();

$templateLoader = new FilesystemLoader('../templates');
$templateEnv = new Environment($templateLoader, ['cache' => false]);

(new GameController())
    ->addServices(new GameService(), new SessionService(new Session()), $templateEnv)
    ->handle($request);