<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiAuthorizationController extends AbstractController
{
    private $em;
    private $userRepository;
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }
    #[Route('/api/register', name: 'app_api_register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        // return $this->json($body);

        if (empty($body["username"]) || empty($body["email"]) || empty($body['email'])) {
            return $this->json([
                "status" => "false",
                "message" => "Invalid credentials"
            ]);
        }

        $userExist = $this->userRepository->findBy(["email" => $body["email"]]);

        if ($userExist) {
            return $this->json([
                "status" => false,
                "message" => "User with this email already exist"
            ]);
        }


        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword($user, $body["password"]);

        $user->setEmail($body["email"]);
        $user->setPassword($hashedPassword);
        $user->setUsername($body["username"]);

        $this->em->persist($user);
        $this->em->flush();


        return $this->json([
            'message' => 'user created',
            'status' => true
        ]);
    }
    #[Route('/api/user')]
    public function get()
    {
        $currentUser = $this->getUser();

        if ($currentUser instanceof User) {
            $user = [
                "username" => $currentUser->getUsername(),
                "email" => $currentUser->getEmail()
            ];

            return $this->json([
                "status" => true,
                "user" => $user
            ]);
        }

        return $this->json(["status" => false, "message" => "user is not logged in"], 401);
    }
}
