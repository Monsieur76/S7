<?php


    namespace App\pager;


    use Hateoas\Configuration\Route;
    use Hateoas\Representation\Factory\PagerfantaFactory;
    use Pagerfanta\Adapter\ArrayAdapter;
    use Pagerfanta\Pagerfanta;

    class Pager
    {
        public function urlPage($request)
        {
            if ($request === null) {
                $page = 1 ;
            }
            else{
                $page = explode('=',$request);
                $page = $page[2];
            }
            return $page;
        }

        public function pager($page,$find,$url)
        {
            $adapter = new ArrayAdapter($find);
            $pagerFanta = new Pagerfanta($adapter);
            $pagerFanta->setCurrentPage($page);
            $pagerFanta->setMaxPerPage(20);
            $pagerFantaFactory   = new PagerfantaFactory();
            $paginatedCollection = $pagerFantaFactory->createRepresentation(
                $pagerFanta,
                new Route($url
                    ,array()
                ));
            return $paginatedCollection;
        }
    }