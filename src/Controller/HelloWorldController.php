<?php

namespace CrosierSource\CrosierLibCoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController extends AbstractController
{

	#[Route('/hello-world', name: 'hello_world')]
	public function index(): Response
	{
		phpinfo();
		return $this->render('hello_world/index.html.twig', [
			'controller_name' => 'HelloWorldController',
		]);
	}

}
