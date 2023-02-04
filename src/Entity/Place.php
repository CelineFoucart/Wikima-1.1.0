<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: PlaceRepository::class)]
#[UniqueEntity('slug')]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private ?string $situation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private ?string $dominatedBy = null;

    /**
     * @Vich\UploadableField(mapping="upload_images", fileNameProperty="mapFile")
     */
    private ?File $imageMap = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private ?string $mapFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private ?string $population = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 1,
        max: 255
    )]
    private ?string $capitaleCity = null;

    #[ORM\Column(length: 1500, nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 1500
    )]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $history = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $presentation = null;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'places')]
    private Collection $localisations;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'localisations')]
    private Collection $places;

    #[ORM\ManyToMany(targetEntity: PlaceType::class, inversedBy: 'places')]
    private Collection $types;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'places')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Portal::class, inversedBy: 'places')]
    private Collection $portals;

    #[ORM\ManyToOne(inversedBy: 'places')]
    private ?Image $illustration = null;

    public function __construct()
    {
        $this->localisations = new ArrayCollection();
        $this->places = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->portals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getSituation(): ?string
    {
        return $this->situation;
    }

    public function setSituation(?string $situation): self
    {
        $this->situation = $situation;

        return $this;
    }

    public function getDominatedBy(): ?string
    {
        return $this->dominatedBy;
    }

    public function setDominatedBy(?string $dominatedBy): self
    {
        $this->dominatedBy = $dominatedBy;

        return $this;
    }

    public function getMapFile(): ?string
    {
        return $this->mapFile;
    }

    public function setMapFile(?string $mapFile): self
    {
        $this->mapFile = $mapFile;

        return $this;
    }

    public function getPopulation(): ?string
    {
        return $this->population;
    }

    public function setPopulation(?string $population): self
    {
        $this->population = $population;

        return $this;
    }

    public function getCapitaleCity(): ?string
    {
        return $this->capitaleCity;
    }

    public function setCapitaleCity(?string $capitaleCity): self
    {
        $this->capitaleCity = $capitaleCity;

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

    public function getHistory(): ?string
    {
        return $this->history;
    }

    public function setHistory(string $history): self
    {
        $this->history = $history;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getLocalisations(): Collection
    {
        return $this->localisations;
    }

    public function addLocalisation(self $localisation): self
    {
        if (!$this->localisations->contains($localisation)) {
            $this->localisations->add($localisation);
        }

        return $this;
    }

    public function removeLocalisation(self $localisation): self
    {
        $this->localisations->removeElement($localisation);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(self $place): self
    {
        if (!$this->places->contains($place)) {
            $this->places->add($place);
            $place->addLocalisation($this);
        }

        return $this;
    }

    public function removePlace(self $place): self
    {
        if ($this->places->removeElement($place)) {
            $place->removeLocalisation($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, PlaceType>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(PlaceType $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
        }

        return $this;
    }

    public function removeType(PlaceType $type): self
    {
        $this->types->removeElement($type);

        return $this;
    }

    public function __toString()
    {
        return ($this->title) ? $this->title : 'Lieu';
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
            $this->categories->add($category);
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
            $this->portals->add($portal);
        }

        return $this;
    }

    public function removePortal(Portal $portal): self
    {
        $this->portals->removeElement($portal);

        return $this;
    }

    public function setImageMap(File $imageMap = null): self
    {
        $this->imageMap = $imageMap;

        if ($imageMap) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    public function getImageMap(): ?File
    {
        return $this->imageMap;
    }

    public function getIllustration(): ?Image
    {
        return $this->illustration;
    }

    public function setIllustration(?Image $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }
}