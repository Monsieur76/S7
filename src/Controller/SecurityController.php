<?php


    namespace App\Controller;

    use App\Entity\User;
    use App\Repository\CustomerRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use JMS\Serializer\SerializerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use Symfony\Component\HttpFoundation\Response;
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
            EntityManagerInterface $entityManager,
            CustomerRepository $repository
        ){
            $customer = $repository->find(['id'=>$id]);
            if ($customer) {
                $values = json_decode($request->getContent());
                if (isset($values->username, $values->password)) {
                    $user = new User();
                    $user->setCustomers($customer);
                    $user->setUsername($values->username);
                    $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
                    $user->setRoles($user->getRoles());
                    $errors = $validator->validate($user);
                    if (count($errors)>0) {
                        $errors = $serializer->serialize($errors, 'json');
                        return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
                    }

                    $entityManager->persist($user);
                    $entityManager->flush();

                    return new JsonResponse(['message' => 'L\'utilisateur a été créé'], Response::HTTP_CREATED);
                }
                return new JsonResponse(['message' => 'Vous devez renseigner les clés username et password'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            else{
                return new JsonResponse(['message'=> 'Cette entreprise n\'existe pas'],
                    Response::HTTP_NOT_FOUND);
            }
        }
    }