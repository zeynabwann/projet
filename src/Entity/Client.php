<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: "client")]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200, nullable: true)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Length(
        min: 2,
        max: 200,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide.")]
    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: "Le prénom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 25, nullable: true)]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas un email valide.")]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne peut pas être vide.")]
    #[Assert\Regex(
        pattern: "/^\+?[0-9]*$/",
        message: "Le numéro de téléphone doit contenir uniquement des chiffres."
    )]
    private ?string $telephone = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Dette::class, cascade: ['persist', 'remove'])]
    private Collection $dettes;

    public function __construct()
    {
        $this->dettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
 

    public function getDettes(): Collection
    {
        return $this->dettes;
    }

    public function addDette(Dette $dette): self
    {
        if (!$this->dettes->contains($dette)) {
            $this->dettes->add($dette);
            $dette->setClient($this);
        }

        return $this;
    }

    public function removeDette(Dette $dette): self
    {
        if ($this->dettes->removeElement($dette)) {
            // Set the owning side to null (unless already changed)
            if ($dette->getClient() === $this) {
                $dette->setClient(null);
            }
        }

        return $this;
    }
}