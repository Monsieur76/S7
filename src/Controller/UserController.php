<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\Repository\UserRepository;
    use Hateoas\HateoasBuilder;
    use JMS\Serializer\SerializationContext;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;
    use FOS\RestBundle\Controller\Annotations as Rest;

    /**
     * Class UserController
     * @package App\Controller
     * @Cache(expires="tomorrow")
     * @Rest\Route("/api/v1")
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
        return new JsonResponse($data,Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/users")
     */
    public function listUser(UserRepository $repository)
    {
        $find = $repository->findAll();
        $hateoas = HateoasBuilder::create()->build();
        $data = $hateoas->serialize($find,'json',SerializationContext::create()->setGroups(['list']));
        return new JsonResponse($data,Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/users/{id}")
     */
    public function deleteUser($id,UserRepository $repository)
    {
        $user = $repository->find(['id'=>$id]);
        $userName = $user->getUsername();
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return new JsonResponse(['id'=>$id,'username'=>$userName] ,Response::HTTP_OK);
    }
}