<?php


    namespace App\Controller;

    use App\Entity\Product;
    use App\pager\Pager;
    use App\Repository\ProductRepository;
    use Hateoas\HateoasBuilder;
    use Hateoas\UrlGenerator\CallableUrlGenerator;
    use JMS\Serializer\SerializationContext;
    use JMS\Serializer\SerializerBuilder;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Swagger\Annotations as SWG;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use Nelmio\ApiDocBundle\Annotation\Model;


    /**
     * Class ProductController
     * @package App\Controller
     * @Rest\Route("/api/v1")
     * @Cache(expires="tomorrow")
     */
    class ProductController extends AbstractController
    {
        /**
         *
         * @Rest\Get("/products/{id}")
         * @SWG\Response(
         *     response=200,
         *     description="Returns the rewards of an user",
         *     @SWG\Schema(
         *         type="array",
         *         @SWG\Items(ref=@Model(type=Reward::class, groups={"full"}))
         *     ))
         */
        public function showProduct(Product $product)
        {
            $hateoas = HateoasBuilder::create()->build();
            $response = $hateoas->serialize($product,'json',SerializationContext::create()->setGroups(['show']));
            return new JsonResponse($response,Response::HTTP_OK,array(),true);
        }

        /**
         * @Rest\Get("/products",name="products")
         */
        public function listProduct(ProductRepository $repository,Request $request)
        {
            $serializer = SerializerBuilder::create()->build();
            $hateoas = HateoasBuilder::create()
                ->setUrlGenerator(null, new CallableUrlGenerator(function ($name, $parameters, $absolute) {
                    return sprintf('%s/%s%s', $absolute ? 'http://api/v1/products' : '',
                        $name,  http_build_query($parameters));}))
                ->build();
            $pager = new Pager();
            $page = $pager->urlPage($request->getQueryString());
            $find = $repository->findProduct();
            $response = $hateoas->serialize($pager->pager($page,$find,'api/v1/products?'),'json');
            return new JsonResponse($response,Response::HTTP_OK,array(),true);
        }
    }