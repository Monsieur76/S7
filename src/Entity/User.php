<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Security\Core\User\UserInterface;
use Hateoas\Configuration\Annotation\Relation;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Relation("Back", href = "expr('/api/v1/users/' ~ object.Id2())",exclusion = @Hateoas\Exclusion
 * (groups={"show"}))
 * @Relation("Next", href = "expr('/api/v1/users/' ~ object.Id1())",exclusion = @Hateoas\Exclusion
 * (groups={"show"}))
 * @Relation("List Product", href = "expr('/api/v1/users')",exclusion = @Hateoas\Exclusion
 * (groups={"show"}))
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Type("integer")
     * @Serializer\Groups({"list","show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Serializer\Groups({"list","show"})
     * @Serializer\Type("string")
     * @Assert\NotBlank( message = "Le nom ne peut etre vide")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Le nom doit comprendre {{ limit }} caractere minimum",
     *      maxMessage = "Le nom doit comprendre {{ limit }} caractere maximum"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Serializer\Groups({"show"})
     * @Assert\NotBlank()
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     * @Assert\NotBlank(message = "Le mot de pass ne peut etre vide")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"list","show"})
     * @Serializer\Type("App\Entity\Customer")
     * @Assert\NotBlank()
     */
    private $customers;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCustomers(): ?Customer
    {
        return $this->customers;
    }

    public function setCustomers(?Customer $customers): self
    {
        $this->customers = $customers;

        return $this;
    }
    public function Id1 ()
    {
        return $this->getId() + 1;
    }
    public function Id2 ()
    {
        return $this->getId() - 1;
    }
}
