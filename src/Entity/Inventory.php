<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InventoryRepository::class)
 */
class Inventory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Character::class, inversedBy="inventory", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $charact;

    /**
     * @ORM\OneToMany(targetEntity=InventorySlot::class, mappedBy="inventory", orphanRemoval=true)
     */
    private $inventorySlots;

    public function __construct()
    {
        $this->inventorySlots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharact(): ?Character
    {
        return $this->charact;
    }

    public function setCharact(Character $charact): self
    {
        $this->charact = $charact;

        return $this;
    }

    /**
     * @return Collection<int, InventorySlot>
     */
    public function getInventorySlots(): Collection
    {
        return $this->inventorySlots;
    }

    public function addInventorySlot(InventorySlot $inventorySlot): self
    {
        if (!$this->inventorySlots->contains($inventorySlot)) {
            $this->inventorySlots[] = $inventorySlot;
            $inventorySlot->setInventory($this);
        }

        return $this;
    }

    public function removeInventorySlot(InventorySlot $inventorySlot): self
    {
        if ($this->inventorySlots->removeElement($inventorySlot)) {
            // set the owning side to null (unless already changed)
            if ($inventorySlot->getInventory() === $this) {
                $inventorySlot->setInventory(null);
            }
        }

        return $this;
    }
}
