<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation\Relation;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * @ORM\Entity(repositoryClass="ProductRepository")
 * @Relation("Back", href = "expr('/api/v1/products/' ~ object.Id2())",exclusion = @Hateoas\Exclusion
 * (groups={"show"}))
 * @Relation("Next", href = "expr('/api/v1/products/' ~ object.Id1())",exclusion = @Hateoas\Exclusion
 * (groups={"show"}))
 * @Relation("List Product", href = "expr('/api/v1/products')",exclusion = @Hateoas\Exclusion
 * (groups={"show"}))
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list","show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list","show"})
     */
    private $nameProduct;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"show"})
     */
    private $creatDate;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list","show"})
     */
    private $count;

    /**
     * @Serializer\Groups({"list"})
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"show"})
     */
    private $uid;

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
}
