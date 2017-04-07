<?php
use App\Controllers\PagesController;
use App\Middlewares\FlashMiddleware;
use App\Middlewares\OldMiddleware;
use App\Middlewares\TwigCsrfMiddleware;

require dirname(__DIR__) . '/vendor/autoload.php';

session_start();

$app = new \Slim\App([
		'settings' => [
				'displayErrorDetails' => true
		]
]);

require dirname(__DIR__) . '/app/container.php';

// Middlewares
$twigEnvironment = $app->getContainer()->view->getEnvironment();
$app->add(new FlashMiddleware($twigEnvironment));
$app->add(new OldMiddleware($twigEnvironment));
$app->add(new TwigCsrfMiddleware($twigEnvironment, $app->getContainer()->csrf));
$app->add($container->csrf);

// Routes
$app->get('/', PagesController::class . ':home')->setName('root');
$app->get('/contact', PagesController::class . ':getContact')->setName('contact');
$app->post('/contact', PagesController::class . ':postContact');

$app->run();
