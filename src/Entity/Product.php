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
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $productName;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="json")
     */
    private $sizes = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted=false;

    /**
     * @ORM\OneToMany(targetEntity=PurchasedProducts::class, mappedBy="product")
     */
    private $purchasedProducts;

    public function __construct()
    {
        $this->purchasedProducts = new ArrayCollection();
    }

   


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getSizes(): ?array
    {
        return $this->sizes;
    }

    public function setSizes(array $sizes): self
    {
        $this->sizes = $sizes;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection<int, PurchasedProducts>
     */
    public function getPurchasedProducts(): Collection
    {
        return $this->purchasedProducts;
    }

    public function addPurchasedProduct(PurchasedProducts $purchasedProduct): self
    {
        if (!$this->purchasedProducts->contains($purchasedProduct)) {
            $this->purchasedProducts[] = $purchasedProduct;
            $purchasedProduct->setProduct($this);
        }

        return $this;
    }

    public function removePurchasedProduct(PurchasedProducts $purchasedProduct): self
    {
        if ($this->purchasedProducts->removeElement($purchasedProduct)) {
            // set the owning side to null (unless already changed)
            if ($purchasedProduct->getProduct() === $this) {
                $purchasedProduct->setProduct(null);
            }
        }

        return $this;
    }

  

}
