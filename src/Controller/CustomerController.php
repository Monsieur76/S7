<?php


    namespace App\Controller;


    use App\model\Valid;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use JMS\Serializer\SerializerBuilder;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * Class CustomerController
     * @package App\Controller
     * @Rest\Route("/api/v1")
     */
    class CustomerController extends AbstractController
    {
        /**
         * @Route("/customers",methods={"POST"},name="customers")
         */
        public function createCustomer(Request $request)
        {
            $data = $request->getContent();
            $serializer = SerializerBuilder::create()->build();
            $object = $serializer->deserialize($data, 'App\Entity\Customer', 'json');
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            return new Response('Client enregistrer',Response::HTTP_CREATED);
        }
    }