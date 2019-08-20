<?php

    namespace App\DataFixtures;

    use App\Entity\Customer;
    use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
    use Doctrine\Common\Persistence\ObjectManager;

    class CustomerFixture extends BaseFixture implements OrderedFixtureInterface
    {
        public function getOrder(){
            return 1;
        }
        public function load(ObjectManager $manager)
        {
            $this->createMany('customer',Customer::class,2,function (Customer $customer) use ($manager){
                $customer->setSociety('BileMo');
            });

        }
    }
