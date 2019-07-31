<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onOnKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();

        $data = [
            'status' => $exception->getCode(),
            'message' => 'Resource not found'
        ];

        $response = new JsonResponse($data);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
           'onKernelException' => 'onOnKernelException',
        ];
    }
}
