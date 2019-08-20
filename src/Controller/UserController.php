<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\pager\Pager;
    use App\Repository\UserRepository;
    use Hateoas\Configuration\Route;
    use Hateoas\HateoasBuilder;
    use Hateoas\Representation\Factory\PagerfantaFactory;
    use Hateoas\UrlGenerator\CallableUrlGenerator;
    use JMS\Serializer\SerializationContext;
    use Pagerfanta\Adapter\ArrayAdapter;
    use Pagerfanta\Pagerfanta;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
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
    public function listUser(UserRepository $repository,Request $request)
    {
        $hateoas = HateoasBuilder::create()
            ->setUrlGenerator(null, new CallableUrlGenerator(function ($name, $parameters, $absolute) {
                return sprintf('%s/%s%s', $absolute ? 'http://api/v1/products' : '', $name,  http_build_query($parameters));}))
            ->build();
        $pager = new Pager();
        $page = $pager->urlPage($request->getQueryString());
        $find = $repository->findUser();
        $data = $hateoas->serialize($pager->pager($page,$find,'api/v1/users?'),'json');
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