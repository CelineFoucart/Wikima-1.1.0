<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[UniqueEntity('slug')]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private $fullname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')]
    private $slug;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private $nationality;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private $job;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private $birthday;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private $birthdayPlace;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private $deathDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private $deathPlace;

    #[ORM\Column(type: 'string', length: 1500, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private $parents;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private $description;

    #[ORM\Column(type: 'string', length: 2500)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 2500
    )]
    private $presentation;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        min: 20
    )]
    private $biography;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        min: 20
    )]
    private $personality;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'people')]
    private $categories;

    #[ORM\ManyToMany(targetEntity: Portal::class, inversedBy: 'people')]
    private $portals;

    #[ORM\ManyToOne(targetEntity: Image::class, inversedBy: 'people')]
    private $image;

    #[ORM\ManyToMany(targetEntity: PersonType::class, inversedBy: 'people')]
    private Collection $type;

    #[ORM\Column(nullable: true)]
    private ?bool $isSticky = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $children = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siblings = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $partner = null;

    #[ORM\Column(length: 400, nullable: true)]
    private ?string $physicalDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private ?string $species = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 100
    )]
    private ?string $gender = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->portals = new ArrayCollection();
        $this->type = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(?string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(?string $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getBirthdayPlace(): ?string
    {
        return $this->birthdayPlace;
    }

    public function setBirthdayPlace(?string $birthdayPlace): self
    {
        $this->birthdayPlace = $birthdayPlace;

        return $this;
    }

    public function getDeathDate(): ?string
    {
        return $this->deathDate;
    }

    public function setDeathDate(?string $deathDate): self
    {
        $this->deathDate = $deathDate;

        return $this;
    }

    public function getDeathPlace(): ?string
    {
        return $this->deathPlace;
    }

    public function setDeathPlace(?string $deathPlace): self
    {
        $this->deathPlace = $deathPlace;

        return $this;
    }

    public function getParents(): ?string
    {
        return $this->parents;
    }

    public function setParents(?string $parents): self
    {
        $this->parents = $parents;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): self
    {
        $this->biography = $biography;

        return $this;
    }

    public function getPersonality(): ?string
    {
        return $this->personality;
    }

    public function setPersonality(?string $personality): self
    {
        $this->personality = $personality;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Portal>
     */
    public function getPortals(): Collection
    {
        return $this->portals;
    }

    public function addPortal(Portal $portal): self
    {
        if (!$this->portals->contains($portal)) {
            $this->portals[] = $portal;
        }

        return $this;
    }

    public function removePortal(Portal $portal): self
    {
        $this->portals->removeElement($portal);

        return $this;
    }

    public function __toString()
    {
        $firstname = $this->firstname;
        $lastname = ($this->lastname) ? ' '.$this->lastname : '';

        return $firstname.$lastname;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, PersonType>
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(PersonType $type): self
    {
        if (!$this->type->contains($type)) {
            $this->type->add($type);
        }

        return $this;
    }

    public function removeType(PersonType $type): self
    {
        $this->type->removeElement($type);

        return $this;
    }

    public function getIsSticky(): ?bool
    {
        return $this->isSticky;
    }

    public function setIsSticky(?bool $isSticky): self
    {
        $this->isSticky = $isSticky;

        return $this;
    }

    public function getChildren(): ?string
    {
        return $this->children;
    }

    public function setChildren(?string $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getSiblings(): ?string
    {
        return $this->siblings;
    }

    public function setSiblings(?string $siblings): self
    {
        $this->siblings = $siblings;

        return $this;
    }

    public function getPartner(): ?string
    {
        return $this->partner;
    }

    public function setPartner(?string $partner): self
    {
        $this->partner = $partner;

        return $this;
    }

    public function getPhysicalDescription(): ?string
    {
        return $this->physicalDescription;
    }

    public function setPhysicalDescription(?string $physicalDescription): self
    {
        $this->physicalDescription = $physicalDescription;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(?string $species): self
    {
        $this->species = $species;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }
}
