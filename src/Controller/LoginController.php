<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route("/api", name: 'login_api')]
class LoginController extends AbstractController
{
	#[Route('/login', name: 'app_login')]
	public function index(AuthenticationUtils $authenticationUtils, Request $request, TranslatorInterface $translator) : Response
	{
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		if ($error)
		{
			return $this->json([
				'message' => $translator->trans($error->getMessageKey(), domain: 'security'),
			], Response::HTTP_UNAUTHORIZED);
		}

		return $this->json([
			'last_username' => $lastUsername
		]);
	}//end index()

	#[Route('/login_check', name: 'login_check')]
	public function json_login(#[CurrentUser] ?User $user) : Response|RedirectResponse
	{
		if (null === $user) {
			return $this->json([
				'message' => 'missing credentials',
			], Response::HTTP_UNAUTHORIZED);
		}

		return $this->json([
			'lgn' => $user->getUserIdentifier(),
			'id'  => $user->getId(),
		]);
	}

	#[Route('/logout', name: 'app_logout')]
	public function logout(Security $security) : Response
	{
		$response = $security->logout();

		return $response;
	}//end index()
}//end class
