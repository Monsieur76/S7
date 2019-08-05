<?php

    namespace App\Controller;

    use App\Entity\Customer;
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
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * Class UserController
     * @package App\Controller
     * @Route("/api/v1")
     */
    class UserController extends AbstractController
{
    /**
     * @Rest\Get("/users/{id}")
     */
    public function showUser(User $user)
    {
        $hateoas = HateoasBuilder::create()->build();
        $data = $hateoas->serialize($user,'json',SerializationContext::create()->setGroups(['show']));
        $response = new Response($data);
        $response->headers->set('Content-Type','application/json');
        return $response;
    }

    /**
     * @Rest\Get("/users")
     */
    public function listUser()
    {
        $find = $this->getDoctrine()->getRepository('App\Entity\User')->findAll();
        $hateoas = HateoasBuilder::create()->build();
        $data = $hateoas->serialize($find,'json',SerializationContext::create()->setGroups(['list']));
        $response = new Response($data);
        return $response;
    }

    /**
     * @Cache(expires="tomorrow")
     * @Rest\Delete("/users")
     */
    public function deleteUser(Request $request)
    {
        $data = $request->getContent();
        $serializer = SerializerBuilder::create()->build();
        $object = $serializer->deserialize($data, 'App\Entity\User', 'json');
        $name = $object->getUsername();
        if ($name)
        {
            $user = $this->getDoctrine()->getRepository('App\Entity\User')->findOneBy(['username'=>$name]);
            if ($user === null){
                throw new  NotFoundHttpException('Utilisateur inconnue');
            }
        }
        else {
            throw new  NotFoundHttpException('Utilisateur inconnue');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return new Response('Utilisateur supprimer',Response::HTTP_CREATED);
    }

}