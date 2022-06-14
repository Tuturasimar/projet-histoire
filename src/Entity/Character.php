<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * @ORM\Table(name="`character`")
 */
class Character
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="characters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Archetype::class, inversedBy="characters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $archetype;

    /**
     * @ORM\ManyToOne(targetEntity=Pj::class, inversedBy="characters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pj;

    /**
     * @ORM\Column(type="integer")
     */
    private $pv;

    /**
     * @ORM\Column(type="integer")
     */
    private $pm;

    /**
     * @ORM\Column(type="integer")
     */
    private $strength;

    /**
     * @ORM\Column(type="integer")
     */
    private $agility;

    /**
     * @ORM\Column(type="integer")
     */
    private $intelligence;

    /**
     * @ORM\Column(type="integer")
     */
    private $alignment;

    /**
     * @ORM\Column(type="text")
     */
    private $story_done;

    /**
     * @ORM\OneToOne(targetEntity=Inventory::class, mappedBy="charact", cascade={"persist", "remove"})
     */
    private $inventory;

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

    public function getArchetype(): ?Archetype
    {
        return $this->archetype;
    }

    public function setArchetype(?Archetype $archetype): self
    {
        $this->archetype = $archetype;

        return $this;
    }

    public function getPj(): ?Pj
    {
        return $this->pj;
    }

    public function setPj(?Pj $pj): self
    {
        $this->pj = $pj;

        return $this;
    }

    public function getPv(): ?int
    {
        return $this->pv;
    }

    public function setPv(int $pv): self
    {
        $this->pv = $pv;

        return $this;
    }

    public function getPm(): ?int
    {
        return $this->pm;
    }

    public function setPm(int $pm): self
    {
        $this->pm = $pm;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getAgility(): ?int
    {
        return $this->agility;
    }

    public function setAgility(int $agility): self
    {
        $this->agility = $agility;

        return $this;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(int $intelligence): self
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getAlignment(): ?int
    {
        return $this->alignment;
    }

    public function setAlignment(int $alignment): self
    {
        $this->alignment = $alignment;

        return $this;
    }

    public function getStoryDone(): ?string
    {
        return $this->story_done;
    }

    public function setStoryDone(string $story_done): self
    {
        $this->story_done = $story_done;

        return $this;
    }

    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }

    public function setInventory(Inventory $inventory): self
    {
        // set the owning side of the relation if necessary
        if ($inventory->getCharact() !== $this) {
            $inventory->setCharact($this);
        }

        $this->inventory = $inventory;

        return $this;
    }
}
