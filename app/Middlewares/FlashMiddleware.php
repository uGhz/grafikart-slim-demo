<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class FlashMiddleware {
	
	private $twigEnvironment;
	
	public function __construct(\Twig_Environment $twigEnvironment) {
		$this->twigEnvironment = $twigEnvironment;	
	}
	
	public function __invoke(Request $request, Response $response, $nextCallable) {
		
		$this->twigEnvironment->addGlobal('flash', isset($_SESSION['flash']) ? $_SESSION['flash'] : []);
		if (isset($_SESSION['flash'])) {
			unset($_SESSION['flash']);
		}
		return $nextCallable($request, $response);
	}
	
}