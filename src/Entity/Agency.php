<?php

namespace App\Entity;

use App\Repository\AgencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('name', message: 'Cette agence existe déjà. Veuillez en choisir une autre')]
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: AgencyRepository::class)]
class Agency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: "/^[0-9a-zA-Z-_' áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/i",
        match: true,
        message: 'Le nom doit contenir uniquement des lettres, des chiffres le tiret du milieu de l\'undescore.',
    )]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[Assert\NotBlank(message: "L'adresse est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'adresse ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[Assert\Regex(
        pattern: "/^[0-9a-zA-Z-_' áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/i",
        match: true,
        message: "L'adresse doit contenir uniquement des lettres, des chiffres le tiret du milieu de l\'undescore.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Assert\NotBlank(message: "Le nom de la ville est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom de la ville ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[Assert\Regex(
        pattern: "/^[0-9a-zA-Z-_' áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/i",
        match: true,
        message: "Le nom de la ville doit contenir uniquement des lettres, des chiffres le tiret du milieu de l\'undescore.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $town = null;

    #[Assert\NotBlank(message: "Le code postal est obligatoire.")]
    #[Assert\Length(
        max: 5,
        maxMessage: "Le code postal ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[Assert\Regex(
        pattern: "/^\d{5}(?:\d{4})?$/i",
        match: true,
        message: "Le code postal doit contenir uniquement des chiffres.",
    )]
    #[ORM\Column(length: 15)]
    private ?string $zipCode = null;

    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Assert\File(
        maxSize: '4096k',
        extensions: ['jpeg', 'jpg', 'png', 'webp'],
        extensionsMessage: "Seuls les formats d'images jpeg, jpg, png, webp sont autorisés.",
    )]
    #[Vich\UploadableField(mapping: 'agencies', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'agency', targetEntity: Vehicle::class, orphanRemoval: true)]
    private Collection $vehicles;

    #[ORM\OneToMany(mappedBy: 'agency', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'agency', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'agency', targetEntity: Reservation::class, orphanRemoval: true)]
    private Collection $reservations;

    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): static
    {
        $this->town = $town;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isLikedBy(User $user): bool
    {
        // Récupérons tous les likes associés à cet article
        $likes = $this->getLikes()->toArray();
        // Parcourons le tableau des likes,
        foreach ($likes as $like) {
            // Si l'utilisateur associé à l'un des likes est le même que l'utilisateur connecté
            if ($like->getUser() == $user) {
                // c'est qu'il a déjà aimé cet article
                return true;
            }
        }
        // Dans le cas contraire, c'est qu'il n'a pas encore aimé cet article
        return false;
    }


    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    /**
     * @return array
     */
    public function getAvailableVehicles(): array
    {
        $allVehicles = $this->getVehicles()->toArray();

        $availableVehicles = [];

        foreach ($allVehicles as $vehicle) {
            if ($vehicle->isAvailable() === true) {
                $availableVehicles[] = $vehicle;
            }
        }
        return $availableVehicles;
    }

    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
            $vehicle->setAgency($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        if ($this->vehicles->removeElement($vehicle)) {
            // set the owning side to null (unless already changed)
            if ($vehicle->getAgency() === $this) {
                $vehicle->setAgency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAgency($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAgency() === $this) {
                $comment->setAgency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setAgency($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getAgency() === $this) {
                $like->setAgency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setAgency($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getAgency() === $this) {
                $reservation->setAgency(null);
            }
        }

        return $this;
    }
}
