<?php


    namespace App\Controller;


    use App\Entity\ProductBileMo;
    use Doctrine\ORM\EntityManagerInterface;
    use Hateoas\HateoasBuilder;
    use JMS\Serializer\SerializationContext;
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
            $hateoas = HateoasBuilder::create()->build();
            return new Response($hateoas->serialize($bileMo,'json',
                SerializationContext::create()->setGroups(['show'])),Response::HTTP_OK,
                ['Content-Type'=>'application/json']);
        }

        /**
         * @Rest\Get("/products",name="products")
         */
        public function listProduct(EntityManagerInterface $entityManager)
        {
            $find = $entityManager->getRepository('App\Entity\ProductBileMo')->findAll();
            $hateoas = HateoasBuilder::create()->build();
            return new Response($hateoas->serialize($find,'json',
                SerializationContext::create()->setGroups(['list'])),Response::HTTP_OK
                ,['Content-Type'=>'application/json']);
        }
    }