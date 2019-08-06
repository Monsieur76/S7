<?php


    namespace App\Controller;

    use FOS\RestBundle\Controller\Annotations as Rest;
    use JMS\Serializer\SerializerBuilder;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    /**
     * Class CustomerController
     * @package App\Controller
     * @Cache(expires="tomorrow")
     * @Rest\Route("/api/v1")
     */
    class CustomerController extends AbstractController
    {
        /**
         * @Rest\Post("/customers",name="customers")
         */
        public function createCustomer(Request $request)
        {
            $data = $request->getContent();
            $serializer = SerializerBuilder::create()->build();
            $object = $serializer->deserialize($data, 'App\Entity\Customer', 'json');
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            return new JsonResponse($data,Response::HTTP_CREATED);
        }
    }