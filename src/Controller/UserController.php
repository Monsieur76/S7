<?php

    namespace App\Controller;

    use App\Entity\User;
    use Hateoas\HateoasBuilder;
    use JMS\Serializer\SerializationContext;
    use JMS\Serializer\SerializerBuilder;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * Class UserController
     * @package App\Controller
     * @Cache(expires="tomorrow")
     * @Route("/api/v1")
     * @Cache(expires="tomorrow")
     * */

    class UserController extends AbstractController
{
    /**
     * @Rest\Get("/users/{id}")
     */
    public function showUser(User $user)
    {
        $hateoas = HateoasBuilder::create()->build();
        $data = $hateoas->serialize($user,'json',SerializationContext::create()->setGroups(['show']));
        return new Response($data,Response::HTTP_OK,['Content-Type'=>'application/json']);
    }

    /**
     * @Rest\Get("/users")
     */
    public function listUser()
    {
        $find = $this->getDoctrine()->getRepository('App\Entity\User')->findAll();
        $hateoas = HateoasBuilder::create()->build();
        $data = $hateoas->serialize($find,'json',SerializationContext::create()->setGroups(['list']));
        return new Response($data,Response::HTTP_OK,['Content-Type'=>'application/json']);
    }

    /**
     * @Rest\Delete("/users")
     */
    public function deleteUser(Request $request)
    {
        $data = $request->getContent();
        $serializer = SerializerBuilder::create()->build();
        $object = $serializer->deserialize($data, 'App\Entity\User', 'json');
        $id = $object->getId();
        if ($id)
        {
            $user = $this->getDoctrine()->getRepository('App\Entity\User')->findOneBy(['id'=>$id]);
            if ($user === null){
                return new JsonResponse(['Utilisateur inconnue'],404,['Content-Type'=>'application/json']);
            }
        }
        else {
            return new JsonResponse(['Utilisateur inconnue'],404,['Content-Type'=>'application/json']);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return new Response('Utilisateur supprimer',Response::HTTP_OK
            ,['Content-Type'=>'application/json']);
    }
}