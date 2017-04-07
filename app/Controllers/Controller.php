<?php
namespace App\Controllers;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Controller {
	
	private $container;
	
	public function __construct(\Slim\Container $container) {
		$this->container = $container;
	}
	
	public function render(ResponseInterface $response, $page, $params = []) {
		$this->container->view->render($response, $page, $params);
	}
	
	public function __get($name) {
		return $this->container->get($name);
	}
	
	public function redirect(ResponseInterface $response, $routeName, $statut = 302) {
		return $response->withStatus($statut)->withHeader('Location', $this->router->pathFor($routeName));
	}
	
	public function flash($message, $type = 'success') {
		if (!isset($_SESSION['flash'])) {
			$_SESSION['flash'] = [];
		}
		$_SESSION['flash'][$type] = $message;
	}
}