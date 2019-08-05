<?php


    namespace App\Controller;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use JMS\Serializer\SerializerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * Class SecurityController
     * @package App\Controller
     */
    class SecurityController extends AbstractController
    {
        /**
         * @Route("/register/{id}", name="register", methods={"POST"})
         */
        public function register(
            $id,
            Request $request,
            ValidatorInterface $validator,
            SerializerInterface $serializer,
            UserPasswordEncoderInterface $passwordEncoder,
            EntityManagerInterface $entityManager
        ){
            $values = json_decode($request->getContent());
            if (isset($values->username, $values->password)) {
                $customer = $entityManager->getRepository('App\Entity\Customer')->findOneBy(['id'=>$id]);
                $user = new User();
                $user->setCustomers($customer);
                $user->setUsername($values->username);
                $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
                $user->setRoles($user->getRoles());
                $errors = $validator->validate($user);
                if(count($errors)) {
                    $errors = $serializer->serialize($errors, 'json');
                    return new Response($errors, 500, [
                        'Content-Type' => 'application/json'
                    ]);
                }

                $entityManager->persist($user);
                $entityManager->flush();

                return new JsonResponse(['message' => 'L\'utilisateur a été créé'], 201);
            }
            return new JsonResponse(['message' => 'Vous devez renseigner les clés username et password'], 500);

        }
    }