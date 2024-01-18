<?php

require_once './vendor/autoload.php';

use App\Config;
use App\Controller\SAMLController;
use App\Controller\UserController;
use App\UserBackend;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

function initSession(): Session
{
    $sessionHandler = new NativeFileSessionHandler('./session');
    $sessionStorage = new NativeSessionStorage([], $sessionHandler);
    $session        = new Session($sessionStorage);
    $session->start();

    return $session;
}

function initTemplateEngine(): Environment
{
    $loader = new FilesystemLoader('./src/templates');
    return new Environment($loader);
}

function initRoute(): RouteCollection
{
    $session        = initSession();
    $twig           = initTemplateEngine();
    $routes         = new RouteCollection();
    $config         = new Config('./settings.json');
    $userBackend    = new UserBackend();
    $SAMLController = new SAMLController($config);
    $userController = new UserController($twig, $session, $userBackend);


    $routes->add('default-page', new Route('/', [
        '_controller' => [$userController, 'defaultPage']
    ]));
    $routes->add('login-api', new Route('/login', [
        '_controller' => [$userController, 'login']
    ]));
    $routes->add('dashboard', new Route('/dashboard', [
        '_controller' => [$userController, 'dashboard']
    ]));
    $routes->add('saml-request', new Route('/saml', [
        '_controller' => [$SAMLController, 'sendAuthnRequest']
    ]));
    $routes->add('saml-acs', new Route('/acs', [
        '_controller' => [$SAMLController, 'handleSPInitResponse']
    ]));
    $routes->add('saml-acs', new Route('/acs-idp', [
        '_controller' => [$SAMLController, 'handleSPInitResponse']
    ]));

    return $routes;
}

$routes  = initRoute();
$context = new RequestContext();
$request = Request::createFromGlobals();

$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    $attributes = $matcher->match($request->getPathInfo());
    $response = call_user_func($attributes['_controller']);
} catch (ResourceNotFoundException $e) {
    $response = new Response('Not Found', 404);
} catch (\Exception $e) {
    $response = new Response('An error occurred', 500);
}

$response->send();