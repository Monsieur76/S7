<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends BaseFixture implements OrderedFixtureInterface
{
    public function getOrder(){
        return 3;
    }
    public function load(ObjectManager $manager)
    {
        $this->createMany('user',User::class,$this->count,function (User $user) use ($manager){
            $customer = $this->getReference('customer'.rand(1,$this->maxRandom));
            $user->setUsername($this->faker->userName);
            $user->setCustomers($customer);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
//                $this->faker->password
                'moi'
            );
        });

    }
}
