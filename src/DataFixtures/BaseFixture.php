<?php


    namespace App\DataFixtures;


    use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
    use Doctrine\Common\Persistence\ObjectManager;
    use Faker;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
    use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

    abstract class BaseFixture extends Fixture implements OrderedFixtureInterface
    {
        protected $faker;
        protected $token;
        protected $encoder;
        protected $manager;
        protected $count = 101;
        protected $maxRandom = 100;
        protected $password = 'moi';

        public function __construct(
            UserPasswordEncoderInterface $encoder,
            TokenGeneratorInterface $token ,
            ObjectManager $manager
        ){
            $this->faker = Faker\Factory::create('fr_FR');
            $this->encoder = $encoder;
            $this->token = $token;
            $this->manager = $manager;
        }

        protected function createMany($reference,string $className, int $count, callable $factory)
        {
            for ($i = 1; $i < $count; $i++) {
                $entity = new $className();
                $factory($entity,$i);
                $this->manager->persist($entity);
                $this->addReference($reference.$i, $entity);
                $this->manager->flush();
            }
        }
    }
