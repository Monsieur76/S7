<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixture extends BaseFixture implements OrderedFixtureInterface
{

    public function getOrder(){
        return 2;
    }

    public function load(ObjectManager $manager)
    {

        $this->createMany('product',Product::class,$this->count,function (Product $bileMo) use ($manager){
            $customer = $this->getReference('customer1');
            $bileMo->setCustomer($customer);
            $bileMo->setCount(rand(50,500));
            $bileMo->setNameProduct($this->faker->domainName);
            $bileMo->setUid($this->faker->uuid);
        });
    }
}
