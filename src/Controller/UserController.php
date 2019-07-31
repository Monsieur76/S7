<?php

    namespace App\Controller;

    use App\Entity\Post;
    use JMS\Serializer\SerializationContext;
    use JMS\Serializer\SerializerBuilder;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

class Posts extends Controller
{
    /**
     * @Route("/creatPost",methods={"POST"},name="creatPost")
     */
    public function creatPost(Request $request)
    {
        $data = $request->getContent();
        $serializer = SerializerBuilder::create()->build();
        $object = $serializer->deserialize($data, 'App\Entity\Post', 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        $em->flush();
        return new Response('',Response::HTTP_CREATED);
    }

    /**
     * @Route("/showPost/{id}",name="showPost",methods={"GET"})
     */
    public function showPost(Post $post)
    {
        $serializer = SerializerBuilder::create()->build();
        $data = $serializer->serialize($post,'json',SerializationContext::create()->setGroups(['detail']));
        $response = new Response($data);
        $response->headers->set('Content-Type','application/json');
        return $response;
    }

    /**
     * @Route("/list",name="list",methods={"GET"})
     */
    public function listPost()
    {
        $find = $this->getDoctrine()->getRepository('App:Post')->findAll();
        $serializer = SerializerBuilder::create()->build();
        $data = $serializer->serialize($find,'json',SerializationContext::create()->setGroups(['list']));
        $response = new Response($data);
        return $response;
    }

    /**
     * @Route("/updatePost",name="update",methods={"PUT"})
     */
    public function updatePost()
    {

    }

}