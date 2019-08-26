<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\pager\Pager;
    use App\Repository\UserRepository;
    use Hateoas\HateoasBuilder;
    use Hateoas\UrlGenerator\CallableUrlGenerator;
    use JMS\Serializer\SerializationContext;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use Symfony\Component\Routing\Annotation\Route;
    use Swagger\Annotations as SWG;


    /**
     * Class UserController
     * @package App\Controller
     * @Cache(expires="tomorrow")
     * @Rest\Route("/api/v1")
     * */

    class UserController extends AbstractController
{
    /**
     * @Route("/users/{id}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Show one user",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref="/users/{id}")
     * ))
     * @SWG\Parameter(
     *          name="order",
     *          in="query",
     *          type="string")
     */
    public function showUser(User $user)
    {
        $hateoas = HateoasBuilder::create()->build();
        $data = $hateoas->serialize($user,'json',SerializationContext::create()->setGroups(['show']));
        return new JsonResponse($data,Response::HTTP_OK,array(),true);
    }

    /**
     * @Route("/users", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="List an user",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref="/users")
     * ))
     * @SWG\Parameter(
     *          name="order",
     *          in="query",
     *          type="string")
     */
    public function listUser(UserRepository $repository,Request $request)
    {
        $hateoas = HateoasBuilder::create()
            ->setUrlGenerator(null, new CallableUrlGenerator(function ($name, $parameters, $absolute) {
                return sprintf('%s/%s%s', $absolute ? 'http://api/v1/users' : '', $name,  http_build_query($parameters));}))
            ->build();
        $pager = new Pager();
        $page = $pager->urlPage($request->getQueryString());
        $find = $repository->findUser();
        $data = $hateoas->serialize($pager->pager($page,$find,'api/v1/users?'),'json');
        return new JsonResponse($data,Response::HTTP_OK,array(),true);
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Delete an user",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref="/users/{id}")
     * ))
     * @SWG\Parameter(
     *          name="order",
     *          in="query",
     *          type="string")
     */
    public function deleteUser($id,UserRepository $repository)
    {
        $user = $repository->find(['id'=>$id]);
        $userName = $user->getUsername();
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return new JsonResponse(['id'=>$id,'username'=>$userName,'message'=>'a bien été supprimé'] ,Response::HTTP_OK);
    }
}