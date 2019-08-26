<?php


    namespace App\Controller;

    use App\Entity\Product;
    use App\pager\Pager;
    use App\Repository\ProductRepository;
    use Hateoas\HateoasBuilder;
    use Hateoas\UrlGenerator\CallableUrlGenerator;
    use JMS\Serializer\SerializationContext;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Swagger\Annotations as SWG;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use Nelmio\ApiDocBundle\Annotation\Model;
    use Symfony\Component\Routing\Annotation\Route;


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
         * @Route("/products/{id}", methods={"GET"})
         * @SWG\Response(
         *     response=200,
         *     description="Returns the show of product",
         *     @SWG\Schema(
         *         type="object",
         *         @SWG\Items(ref="/products/{id}"),
         *         @SWG\Items(ref=@Model(type=Product::class))
         *     ))
         */
        public function showProduct(Product $product)
        {
            $hateoas = HateoasBuilder::create()->build();
            $response = $hateoas->serialize($product,'json',SerializationContext::create()->setGroups(['show']));
            return new JsonResponse($response,Response::HTTP_OK,array(),true);
        }

        /**
         * @Route("/products",name="products", methods={"GET"})
         *     @SWG\Response(
         *     response=200,
         *     description="Returns the list of product ",
         *     @SWG\Schema(
         *         type="object",
         *         @SWG\Items(ref="/products")
         * ))
         * @SWG\Parameter(
         *          name="order",
         *          in="query",
         *          type="string")
         */
        public function listProduct(ProductRepository $repository,Request $request)
        {
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