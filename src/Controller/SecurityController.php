<?php


    namespace App\Controller;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use JMS\Serializer\SerializerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Class SecurityController
     * @package App\Controller
     */
    class SecurityController extends AbstractController
    {
        /**
         * @Rest\Post("/register/{id}", name="register")
         */
        public function register(
            $id,
            Request $request,
            ValidatorInterface $validator,
            SerializerInterface $serializer,
            UserPasswordEncoderInterface $passwordEncoder,
            EntityManagerInterface $entityManager
        ){
            $customer = $entityManager->getRepository('App\Entity\Customer')->findOneBy(['id'=>$id]);
            if ($customer) {
                $values = json_decode($request->getContent());
                if (isset($values->username, $values->password)) {
                    $user = new User();
                    $user->setCustomers($customer);
                    $user->setUsername($values->username);
                    $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
                    $user->setRoles($user->getRoles());
                    $errors = $validator->validate($user);
                    if (count($errors)) {
                        $errors = $serializer->serialize($errors, 'json');
                        return new Response($errors, 500, [
                            'Content-Type' => 'application/json'
                        ]);
                    }

                    $entityManager->persist($user);
                    $entityManager->flush();

                    return new JsonResponse(['message' => 'L\'utilisateur a été créé'], 201,
                        ['Content-Type' => 'application/json']);
                }
                return new JsonResponse(['message' => 'Vous devez renseigner les clés username et password'],
                    500,['Content-Type' => 'application/json']);
            }
            else{
                return new JsonResponse(['message'=> 'Cette entreprise n\'existe pas'],
                    404,['Content-Type' => 'application/json']);
            }
        }
    }