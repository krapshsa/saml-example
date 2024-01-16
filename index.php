<?php

require_once './vendor/autoload.php';

use \App\Router;
use \App\SAMLController;

$router         = new Router();
$SAMLController = new SAMLController();

$router->add('/saml'   , [$SAMLController, 'sendAuthnRequest']);
$router->add('/acs'    , [$SAMLController, 'handleSPInitResponse']);
$router->add('/acs-idp', [$SAMLController, 'handleIDPInitResponse']);

$currentUrl = $_SERVER['REQUEST_URI'];

$router->dispatch($currentUrl);
