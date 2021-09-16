<?php

namespace App\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigGlobalSubscriber implements EventSubscriberInterface {

    /**
     * @var Environment
     */
    private $twig;


    public function __construct(Environment $twig) {
        $this->twig    = $twig;
    }

    public function injectGlobalVariables(ControllerEvent $event ) {
        $index_route = $event->getRequest()->get('_route');
        
        $this->twig->addGlobal('index_route', $index_route);
    }

    public static function getSubscribedEvents() {
        return [
            KernelEvents::CONTROLLER =>  'injectGlobalVariables' 
        ];
    }
}