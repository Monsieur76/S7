<?php


    namespace App\Serializer\Handler;


    use App\Entity\Post;
    use JMS\Serializer\Context;
    use JMS\Serializer\GraphNavigatorInterface;
    use JMS\Serializer\Handler\SubscribingHandlerInterface;
    use JMS\Serializer\JsonDeserializationVisitor;

    class Handler implements SubscribingHandlerInterface
    {
        public static function getSubscribingMethods()
        {
            return [
                [
                    'direction'=> GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                    'format'=>'json',
                    'type'=>'App\Entity\Post',
                    'method'=>'serialize'

                ]
            ];
        }

        public static function serialize(JsonDeserializationVisitor $visitor,Post $post,array $type,Context $context)
        {
            $date = new \DateTime();
            return
            [
                'title'=>$post->getTitle(),
                'content'=>$post->getContent(),
                'serialized_at'=> $date->format('l jS \of F Y h:i:s A'),
                ];
        }

    }