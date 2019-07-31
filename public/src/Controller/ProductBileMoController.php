<?php


    namespace App\Controller;


    use App\Entity\ProductBileMo;
    use Doctrine\ORM\EntityManagerInterface;
    use JMS\Serializer\SerializationContext;
    use JMS\Serializer\SerializerBuilder;
    use Symfony\Component\HttpFoundation\Response;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * Class ProductBileMoController
     * @package App\Controller
     * @Route("/api/v1")
     */
    class ProductBileMoController
    {
        /**
         * @Rest\Get("/products/{id}")
         */
        public function showProduct(ProductBileMo $bileMo)
        {
            $serializer = SerializerBuilder::create()->build();
            $data = $serializer->serialize($bileMo,'json',SerializationContext::create()->setGroups(['show']));
            $response = new Response($data);
            $response->headers->set('Content-Type','application/json');
            return $response;
        }

        /**
         * @Rest\Get("/products")
         */
        public function listProduct(EntityManagerInterface $entityManager)
        {
            $find = $entityManager->getRepository('App\Entity\ProductBileMo')->findAll();
            $serializer = SerializerBuilder::create()->build();
            $data = $serializer->serialize($find,'json',SerializationContext::create()->setGroups(['list']));
            $response = new Response($data);
            return $response;
        }
    }