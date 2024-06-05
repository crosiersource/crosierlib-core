<?php

namespace CrosierSource\CrosierLibCoreBundle\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiLoginController extends AbstractController
{

	#[Route('/api/login', name: 'api_login')]
	public function index(): Response
	{
		return $this->json([
			'message' => 'Welcome to your new controller!',
			'path' => 'src/Controller/ApiLoginController.php',
		]);
	}

}
