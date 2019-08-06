<?php


    namespace App\Controller;

    use App\Entity\Product;
    use App\Repository\ProductRepository;
    use Doctrine\ORM\EntityManager;
    use Hateoas\HateoasBuilder;
    use JMS\Serializer\SerializationContext;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;
    use FOS\RestBundle\Controller\Annotations as Rest;

    /**
     * Class ProductController
     * @package App\Controller
     * @Rest\Route("/api/v1")
     * @Cache()che(expires="tomorrow")
     */
    class ProductController
    {
        /**
         * @Rest\Get("/products/{id}")
         */
        public function showProduct(Product $product)
        {
            $hateoas = HateoasBuilder::create()->build();
            return new JsonResponse($hateoas->serialize($product,'json',
                SerializationContext::create()->setGroups(['show'])),Response::HTTP_OK);
        }

        /**
         * @Rest\Get("/products",name="products")
         */
        public function listProduct(ProductRepository $repository)
        {
            $find = $repository->findAll();
            $hateoas = HateoasBuilder::create()->build();
            return new JsonResponse($hateoas->serialize($find,'json',
                SerializationContext::create()->setGroups(['list'])),Response::HTTP_OK);
        }
    }