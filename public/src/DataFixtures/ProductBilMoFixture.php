<?php

namespace App\DataFixtures;

use App\Entity\ProductBileMo;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductBilMoFixture extends BaseFixture implements OrderedFixtureInterface
{

    public function getOrder(){
        return 2;
    }

    public function load(ObjectManager $manager)
    {

        $this->createMany('product',ProductBileMo::class,$this->count,function (ProductBileMo $bileMo) use ($manager){
            $bileMo->setCount(rand(50,500));
            $bileMo->setNameProduct($this->faker->name);
            $bileMo->setUid($this->faker->uuid);
        });
    }
}
