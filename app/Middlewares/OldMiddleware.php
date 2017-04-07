<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class OldMiddleware {
	
	private $twigEnvironment;
	
	public function __construct(\Twig_Environment $twigEnvironment) {
		$this->twigEnvironment = $twigEnvironment;	
	}
	
	public function __invoke(Request $request, Response $response, $nextCallable) {
		
		$this->twigEnvironment->addGlobal('old', isset($_SESSION['old']) ? $_SESSION['old'] : []);
		
		if (isset($_SESSION['old'])) {
			unset($_SESSION['old']);
		}
		
		$response =  $nextCallable($request, $response);
		
		if ($response->getStatusCode() === 400) {
			$_SESSION['old'] = $request->getParams();
		}
		
		return $response;
	}
	
}