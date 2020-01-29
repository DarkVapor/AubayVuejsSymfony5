<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Validator\UserValidator;
use App\Validator\LoginValidator;
use App\Service\TokenService;
use App\Controller\ITokenController;

class UserController extends AbstractController implements ITokenController 
{

    /**
     * @param UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface 
     */
    private $entityManager;

    /**
     * @var UserValidator
     */
    private $validator;
    /**
     * @var LoginValidator
     */
    private $loginValidator;

    /**
     * @var TokenService
     */
    private $tokenService;

    public function __construct(UserRepository $ur, EntityManagerInterface $em, UserValidator $uv, LoginValidator $lv, TokenService $ts)
    {
        $this->userRepository = $ur;
        $this->entityManager = $em;
        $this->validator = $uv;
        $this->loginValidator = $lv;
        $this->tokenService = $ts;
    }

    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        
        return $this->render('user/index.html.twig', []);
    }

    /**
     * @Route("/user_list", name="user_list", methods={"POST"})
     */
    public function list(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        $u = [];
        $filters = null;

        if (is_array($data) && isset($data['filters'])) {
            $filters = $data['filters'];
        }

        $users = $this->userRepository->filter($filters);
        $tc = $this->userRepository->getTotalCount($filters);


        foreach ($users as $user) {
            $u[] = $user->toArray();
        }

        return new JsonResponse(['success' => true, 'list' => $u, 'totalCount' => $tc], Response::HTTP_CREATED);
    }

    /**
     * @Route("/user_add", name="user_add")
     */
    public function add(Request $request): JsonResponse
    {
        if ($request->isMethod('post')) {
            $data = json_decode($request->getContent(), true);

            if ($this->validator->setData($data['user'])->valid()) {
                $user = User::create($data['user']);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->tokenService->generateToken($data['user']['email']);
                return new JsonResponse(['message' => 'User saved with success.'], Response::HTTP_CREATED);
            }

            return  new JsonResponse([
                'success' => false,
                'errors' => $this->validator->getErrors(),
                'message' => 'Unable to save user'
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("/user_edit", name="user_edit")
     */
    public function edit(Request $request): JsonResponse
    {
        if ($request->isMethod('post')) {
            $data = json_decode($request->getContent(), true);
            if ($this->validator->setData($data['user'])->valid()) {
                $user = $this->userRepository->find($data['user']['id']);
                $user->populate($data['user']);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return new JsonResponse(['success' => true, 'message' => 'User saved with success.'], Response::HTTP_CREATED);
            }
            return  new JsonResponse([
                'success' => false,
                'errors' => $this->validator->getErrors(),
                'message' => 'Unable to save user'
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("/user_remove", name="user_remove")
     */
    public function remove(Request $request): JsonResponse
    {
        if ($request->isMethod('post')) {
            $data = json_decode($request->getContent(), true);
            if (isset($data['id'])) {
                $user = $this->userRepository->find($data['id']);
                $this->entityManager->remove($user);
                $this->entityManager->flush();
                return new JsonResponse([ 'success'=>true, 'message' => 'user_removed'], Response::HTTP_CREATED);
            }
            return new JsonResponse(['success'=>false, 'message' => 'user_not_removed'], Response::HTTP_CREATED);
        }
    }



    /**
     * @Route("/user_find", name="user_find")
     */
    public function find(Request $request): JsonResponse
    {
        if ($request->isMethod('post')) {
            $data = json_decode($request->getContent(), true);
            if (isset($data['id'])) {
                $user = $this->userRepository->find($data['id']);
                return new JsonResponse($user->toArray(), Response::HTTP_CREATED);
            }
            return new JsonResponse(['success' => false, 'message' => 'user_not_found'], Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("/user_connection", name="user_connection")
     */
    public function connection(Request $request): JsonResponse
    {
        if ($request->isMethod('post')) {
            $data = json_decode($request->getContent(), true);
            if ($this->loginValidator->setData($data)->valid()) {   
                $token = $this->tokenService->generateToken($data['email']);
                return new JsonResponse([
                    'success' => 'true',
                    'token' => $token,
                    'message' => 'OK'
                ], Response::HTTP_CREATED);
            }
        }
        return new JsonResponse([
            'success' => false,
            'message' => 'Access Denied'
        ], Response::HTTP_CREATED);
    }
}
