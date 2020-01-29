<?php 
namespace App\EventSubscriber;

use App\Controller\ITokenController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Service\TokenService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TokenSubscriber implements EventSubscriberInterface
{
    private $api;
    private $tokenService;

    public function __construct($api, TokenService $ts)
    {   
        $this->api = $api;
        $this->tokenService = $ts;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $method = $controller[1];
            $controller = $controller[0];
        }
        
        if ($controller instanceof ITokenController && in_array($method, $this->api)) {
            
            $data = json_decode($event->getRequest()->getContent(), true);
           
            try{
                if(!$this->tokenService->validateToken($data['token'])){
                    echo json_encode([
                        'success' => false,
                        'message' => 'Access Denied'
                    ]);
                    die();
                }
            }catch(Exception $e){
                
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                
                die();
            }
           
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}