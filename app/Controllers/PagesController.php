<?php
namespace App\Controllers;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Validator;

class PagesController extends Controller {
	
	public function home (RequestInterface $request, ResponseInterface $response) {
		
		$this->render($response, 'pages/home.twig', ['name' => 'Marc']);
		
	}
	
	public function getContact (RequestInterface $request, ResponseInterface $response) {

		return $this->render($response, 'pages/contact.twig');
		
	}
	
	public function postContact (RequestInterface $request, ResponseInterface $response) {
		
		$errors = [];
		Validator::email()->validate($request->getParam('email')) || $errors['email'] = 'Votre email n\'est pas valide.';
		Validator::notEmpty()->validate($request->getParam('name')) || $errors['name'] = 'Veuillez entrer votre nom.';
		Validator::notEmpty()->validate($request->getParam('content')) || $errors['content'] = 'Veuillez entrer un message.';
		
		if (empty($errors)) {
			
			$message = \Swift_Message::newInstance('Message de contact')
				->setFrom([$request->getParam('email') => $request->getParam('name')])
				->setTo('contact@monsite.fr')
				->setBody('Un email vous a été envoyé : ' .
						$request->getParam('content'));
			$this->mailer->send($message);
			
			$this->flash('Votre message a bien été envoyé.');
			return $this->redirect($response, 'contact');
		} else {
			
			$this->flash('Certains champs n\'ont pas été remplis correctement.', 'error');
			$this->flash($errors, 'errors');
			return $this->redirect($response, 'contact', 400);
		}
		
	}
	
}