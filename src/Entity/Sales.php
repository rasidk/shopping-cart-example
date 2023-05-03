<?php

namespace App\Entity;

use App\Repository\SalesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SalesRepository::class)
 */
class Sales
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sales")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $totalAmount;

    /**
     * @ORM\OneToMany(targetEntity=PurchasedProducts::class, mappedBy="sale")
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

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
            $purchasedProduct->setSale($this);
        }

        return $this;
    }

    public function removePurchasedProduct(PurchasedProducts $purchasedProduct): self
    {
        if ($this->purchasedProducts->removeElement($purchasedProduct)) {
            // set the owning side to null (unless already changed)
            if ($purchasedProduct->getSale() === $this) {
                $purchasedProduct->setSale(null);
            }
        }

        return $this;
    }

   
}
