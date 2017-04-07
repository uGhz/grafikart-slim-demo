<?php
// Get container
$container = $app->getContainer();

$container['debug'] = function () {
	return true;
};


// Register CSRF component
$container['csrf'] = function ($c) {
	return new \Slim\Csrf\Guard();
};

// Register component on container
$container['view'] = function ($container) {
	
	$dir = dirname(__DIR__);
	$view = new \Slim\Views\Twig($dir . '/app/views', [
			'cache' => $container->debug ? false : $dir . '/tmp/cache',
			'debug' => $container->debug
	]);
	if ($container->debug) {
		$view->addExtension(new Twig_Extension_Debug());
	}
	// Instantiate and add Slim specific extension
	$basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
	$view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
	
	return $view;
};

// Register a mailer component
$container['mailer'] = function ($container) {
	if ($container->debug) {
		$transport = Swift_SmtpTransport::newInstance('localhost', 1025);
	} else {
		$transport = Swift_MailTransport::newInstance();
	}
	$mailer = Swift_Mailer::newInstance($transport);
	return $mailer;
};