<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\EntityCommon\IEntity;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController {

    /**
     * @param UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface 
     */
    private $entityManager;

    public function __construct(UserRepository $ur, EntityManagerInterface $em) {
        $this->userRepository = $ur;
        $this->entityManager = $em;
    }

    /**
     * @Route("/user", name="user")
     */
    public function index() {
        return $this->render('user/index.html.twig', []);
    }

    /**
     * @Route("/user_list", name="user_list", methods={"POST"})
     */
    public function list(Request $request): JsonResponse {

        $data = json_decode($request->getContent(), true);
        $u = [];
        $filters = null;
        
        if (is_array($data) && isset($data['filters'])) {
            $filters = $data['filters'];
        }
        
        $users = $this->userRepository->filter($filters);
     
       
        foreach ($users as $user) {
            $u[] = $user->toArray();
        }
        
        return new JsonResponse($u, Response::HTTP_CREATED);
    }

    /**
     * @Route("/user_add", name="user_add")
     */
    public function add(Request $request): JsonResponse {
        if ($request->isMethod('post')) {
            $data = json_decode($request->getContent(), true);
            $this->entityManager->persist(User::create($data['user']));
            $this->entityManager->flush();
            return new JsonResponse(['message' => 'user_saved'], Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("/user_edit", name="user_edit")
     */
    public function edit(Request $request): JsonResponse {
        if ($request->isMethod('post')) {
            $data = json_decode($request->getContent(), true);
            $user = $this->userRepository->find($data['user']['id']);
            $user->populate($data['user']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return new JsonResponse(['message' => 'user_saved'], Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("/user_remove", name="user_remove")
     */
    public function remove(Request $request): JsonResponse {
        if ($request->isMethod('post')) {
            $data = json_decode($request->getContent(), true);
            if (isset($data['id'])) {
                $user = $this->userRepository->find($data['id']);
                $this->entityManager->remove($user);
                $this->entityManager->flush();
                return new JsonResponse(['message' => 'user_removed'], Response::HTTP_CREATED);
            }
            return new JsonResponse(['message' => 'user_not_removed'], Response::HTTP_CREATED);
        }
    }
    public function count(Request $request): JsonResponse {
        
    }
    /**
     * @Route("/user_find", name="user_find")
     */
    public function find(Request $request): JsonResponse {
        if ($request->isMethod('post')) {
            $data = json_decode($request->getContent(), true);
            if (isset($data['id'])) {
                $user = $this->userRepository->find($data['id']);
                return new JsonResponse($user->toArray(), Response::HTTP_CREATED);
            }
            return new JsonResponse(['message' => 'user_not_found'], Response::HTTP_CREATED);
        }
    }

}
