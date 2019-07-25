<?php

    namespace App\Serializer\listener;


    use JMS\Serializer\EventDispatcher\Events;
    use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
    use JMS\Serializer\EventDispatcher\ObjectEvent;

    class PostListener implements EventSubscriberInterface
    {
        public static function getSubscribedEvents()
        {
            return [
                [
                    'event' => Events::POST_SERIALIZE,
                    'format' => 'json',
                    'class' => 'App\Entity\Post',
                    'method' => 'onPostSerialize',
                ]
            ];
        }

        public static function onPostSerialize(ObjectEvent $event)
        {
            $object = $event->getObject();
            $date = new \Datetime();
            $event->getVisitor()->addData('delivered_at', $date->format('l jS \of F Y h:i:s A'));
        }


    }