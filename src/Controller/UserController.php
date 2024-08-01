<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route("/api", name: 'user_api')]
class UserController extends AbstractController
{
    #[Route('/users', name: 'users', methods: ['GET'])]
    public function getUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        $data = [];
        foreach ($users as $user)
        {
            $data[] = [
                'name' => $user->getUsername(),
                'email' => $user->getEmail(),
            ];
        }

        return $this->json($data);
    }
 
    #[Route('/users/add', name: 'user_add', methods: ['POST'])]
    public function addUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        try
        {
            $request = $this->transformJsonBody($request);
 
            if (!$request || !$request->get('name') || !$request->request->get('email') || !$request->request->get('password'))
            {
                throw new \Exception();
            }
 
            $user = new User();
            $user->setUsername($request->get('username'));
            $user->setEmail($request->get('email'));
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $request->get('password')
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
 
            $data = [
                'status' => 201,
                'success' => "User added successfully",
            ];
            return $this->json($data, 201);
        }
        catch (\Exception $e)
        {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return $this->json($data, 422);
        }
    }
 
    #[Route('/users/{id}', name: 'user_get', methods: ['GET'])]
    public function getUserAPI(UserRepository $userRepository, int $id): JsonResponse
    {
        $user = $userRepository->find($id);
 
        if (!$user)
        {
            $data = [
                'status' => 404,
                'errors' => "User not found",
            ];
            return $this->json($data, 404);
        }
        return $this->json([
            'name' => $user->getUsername(),
            'email' => $user->getEmail(),
        ]);
    }
 
    #[Route('/users/{id}', name: 'user_put', methods: ['PUT'])]
    public function updateUser(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, int $id): JsonResponse
    {
        try
        {
            $user = $userRepository->find($id);
            if (!$user)
            {
                $data = [
                    'status' => 404,
                    'errors' => "User not found",
                ];
                return $this->json($data, 404);
            }
 
            $request = $this->transformJsonBody($request);
 
            if (!$request || !$request->get('username') || !$request->request->get('email'))
            {
                throw new \Exception();
            }
 
            $user->setUsername($request->get('username'));
            $user->setEmail($request->get('email'));
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $request->get('password')
                )
            );
            $entityManager->flush();
        
            $data = [
                'status' => 200,
                'errors' => "User updated successfully",
            ];
            return $this->json($data);
        }
        catch (\Exception $e)
        {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return $this->json($data, 422);
        }
    }
 
    #[Route('/users/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function deleteUser(EntityManagerInterface $entityManager, UserRepository $userRepository, $id): JsonResponse
    {
        $user = $userRepository->find($id);
 
        if (!$user)
        {
            $data = [
                'status' => 404,
                'errors' => "User not found",
            ];
            return $this->json($data, 404);
        }
    
        $entityManager->remove($user);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "User deleted successfully",
        ];
        return $this->json($data);
    }
 
    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);
    
        if ($data === null) {
            return $request;
        }
    
        $request->request->replace($data);
    
        return $request;
    }
 
}