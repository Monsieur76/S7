<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation\Relation;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as SWG;



/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @Relation("Back", href = "expr('/api/v1/products/' ~ object.Id2())",exclusion = @Hateoas\Exclusion
 * (groups={"show"}))
 * @Relation("Next", href = "expr('/api/v1/products/' ~ object.Id1())",exclusion = @Hateoas\Exclusion
 * (groups={"show"}))
 * @Relation("List Product", href = "expr('/api/v1/products')",exclusion = @Hateoas\Exclusion
 * (groups={"show"}))
 * @UniqueEntity(fields={"nameProduct","uid"})
 * @
 */

class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list","show"}).
     * @SWG\Property(description="Id product")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list","show"})
     * @Assert\NotBlank(message = "Le nom ne peut être vide")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Le nom doit comprendre {{ limit }} caractere minimum",
     *      maxMessage = "Le nom doit comprendre {{ limit }} caractere maximum"
     * )
     * @SWG\Property(description="name product")
     */
    private $nameProduct;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"show"})
     * @Assert\NotBlank(message = "La date ne peut etre vide")
     *  @SWG\Property(description="Creation Date of product")
     */
    private $creatDate;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list","show"})
     * @Assert\NotBlank(message = "Le prix ne peut etre vide")
     *  @SWG\Property(description="count product")
     */
    private $count;

    /**
     * @Serializer\Groups({"list"})
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"show"})
     * @Assert\NotBlank(message = "Le uid ne peut etre vide")
     *  @SWG\Property(description="uid product")
     */
    private $uid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     *  @SWG\Property(description="ManyToOne for customer")
     */
    private $customer;

    public function __construct()
    {
        $this->creatDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameProduct(): ?string
    {
        return $this->nameProduct;
    }

    public function setNameProduct(string $nameProduct): self
    {
        $this->nameProduct = $nameProduct;

        return $this;
    }

    public function getCreatDate(): ?\DateTimeInterface
    {
        return $this->creatDate;
    }

    public function setCreatDate(string $creatDate): self
    {
        $this->creatDate = $creatDate;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;

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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
