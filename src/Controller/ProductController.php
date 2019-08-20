<?php


    namespace App\Controller;

    use App\Entity\Product;
    use App\pager\Pager;
    use App\Repository\ProductRepository;
    use Hateoas\Configuration\Route;
    use Hateoas\HateoasBuilder;
    use Hateoas\Representation\CollectionRepresentation;
    use Hateoas\Representation\Factory\PagerfantaFactory;
    use Hateoas\UrlGenerator\CallableUrlGenerator;
    use Hateoas\UrlGenerator\SymfonyUrlGenerator;
    use Hateoas\UrlGenerator\UrlGeneratorInterface;
    use JMS\Serializer\SerializationContext;
    use Pagerfanta\Adapter\ArrayAdapter;
    use Pagerfanta\Pagerfanta;
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
     * @Cache()che(expires="tomorrow")
     */
    class ProductController extends AbstractController
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
            return new JsonResponse($hateoas->serialize($pager->pager($page,$find,'api/v1/products?'),'json'),Response::HTTP_OK);
        }
    }