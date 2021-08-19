<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $images;


    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="Products")
     */
    private $CAT;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DateAdded;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->CAT = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
    public function __toString() 
    {
        return (string) $this->name; 
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images)
    {
        if ($images != null) {
            $this->images = $images;
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCAT(): Collection
    {
        return $this->CAT;
    }

    public function addCAT(Category $cAT): self
    {
        if (!$this->CAT->contains($cAT)) {
            $this->CAT[] = $cAT;
            $cAT->addProduct($this);
        }

        return $this;
    }

    public function removeCAT(Category $cAT): self
    {
        if ($this->CAT->removeElement($cAT)) {
            $cAT->removeProduct($this);
        }

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->DateAdded;
    }

    public function setDateAdded(?\DateTimeInterface $DateAdded): self
    {
        $this->DateAdded = $DateAdded;

        return $this;
    }
    
}