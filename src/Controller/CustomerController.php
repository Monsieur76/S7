<?php


    namespace App\Controller;

    use FOS\RestBundle\Controller\Annotations as Rest;
    use JMS\Serializer\SerializerBuilder;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * Class CustomerController
     * @package App\Controller
     * @Cache(expires="tomorrow")
     * @Rest\Route("/api/v1")
     */
    class CustomerController extends AbstractController
    {
        /**
         * @Route("/customers",name="customers", methods={"POST"})
         *     @SWG\Response(
         *     response=200,
         *     description="Creat Customer for to come up",
         *     @SWG\Schema(
         *         type="object",
         *         @SWG\Items(ref="/customers")
         * ))
         * @SWG\Parameter(
         *          name="order",
         *          in="query",
         *          type="string")
         */
//        public function createCustomer(Request $request)
//        {
//            $data = $request->getContent();
//            $serializer = SerializerBuilder::create()->build();
//            $object = $serializer->deserialize($data, 'App\Entity\Customer', 'json');
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($object);
//            $em->flush();
//            return new JsonResponse($data,Response::HTTP_CREATED,array(),true);
//        }
    }