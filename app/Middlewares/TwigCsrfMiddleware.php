<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Csrf\Guard;

class TwigCsrfMiddleware {
	
	private $twigEnvironment;
	private $csrfGuard;
	
	public function __construct(\Twig_Environment $twigEnvironment, Guard $csrfGuard) {
		$this->twigEnvironment = $twigEnvironment;	
		$this->csrfGuard = $csrfGuard;
	}
	
	public function __invoke(Request $request, Response $response, $nextCallable) {
		$csrf = $this->csrfGuard;
		$this->twigEnvironment->addFunction(
				new \Twig_SimpleFunction('csrf', function () use ($csrf, $request) {
					$nameKey	= $csrf->getTokenNameKey();
					$valueKey	= $csrf->getTokenValueKey();
					$name		= $request->getAttribute($nameKey);
					$value		= $request->getAttribute($valueKey);
					
					return '<input type="hidden" name="' . $nameKey . '" value="' . $name . '">' .
    					'<input type="hidden" name="' . $valueKey . '" value="' . $value . '">';
				}, ['is_safe' => ['html']]
		));
		
		$response =  $nextCallable($request, $response);
		
		return $response;
	}
	
}