<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductBileMoRepository")
 */
class ProductBileMo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list","show"})
     */
    private $nameProduct;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"list","show"})
     */
    private $creatDate;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list","show"})
     */
    private $count;

    /**
     * @ORM\Column(type="string", length=255)
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
}
