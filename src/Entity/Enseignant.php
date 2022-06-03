<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EnseignantRepository::class)
 */
class Enseignant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $identifiant;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $motDePasse;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Email(
     *     message = "L'adresse : '{{ value }}' n'est pas une adresse mail valide."
     * )
     */
    private $mail;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\PositiveOrZero
     */
    private $nbUC;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\PositiveOrZero
     */
    private $nbUCattribue;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nomDepartement;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $statutEnseignant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $AccesAdmin;

    /**
     * @ORM\OneToMany(targetEntity=CoursEnseignant::class, mappedBy="Enseignant")
     */
    private $coursEnseignants;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archive;

    public function __construct()
    {
        $this->Enseigne = new ArrayCollection();
        $this->coursEnseignants = new ArrayCollection();
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

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(?string $identifiant): self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getNbUC(): ?int
    {
        return $this->nbUC;
    }

    public function setNbUC(?int $nbUC): self
    {
        $this->nbUC = $nbUC;

        return $this;
    }

    public function getNbUCattribue(): ?int
    {
        return $this->nbUCattribue;
    }

    public function setNbUCattribue(?int $nbUCattribue): self
    {
        $this->nbUCattribue = $nbUCattribue;

        return $this;
    }

    public function getNomDepartement(): ?string
    {
        return $this->nomDepartement;
    }

    public function setNomDepartement(?string $nomDepartement): self
    {
        $this->nomDepartement = $nomDepartement;

        return $this;
    }

    public function getStatutEnseignant(): ?string
    {
        return $this->statutEnseignant;
    }

    public function setStatutEnseignant(string $statutEnseignant): self
    {
        $this->statutEnseignant = $statutEnseignant;

        return $this;
    }

    public function getAccesAdmin(): ?bool
    {
        return $this->AccesAdmin;
    }

    public function setAccesAdmin(bool $AccesAdmin): self
    {
        $this->AccesAdmin = $AccesAdmin;

        return $this;
    }

    /**
     * @return Collection<int, CoursEnseignant>
     */
    public function getCoursEnseignants(): Collection
    {
        return $this->coursEnseignants;
    }

    public function addCoursEnseignant(CoursEnseignant $coursEnseignant): self
    {
        if (!$this->coursEnseignants->contains($coursEnseignant)) {
            $this->coursEnseignants[] = $coursEnseignant;
            $coursEnseignant->setEnseignant($this);
        }

        return $this;
    }

    public function removeCoursEnseignant(CoursEnseignant $coursEnseignant): self
    {
        if ($this->coursEnseignants->removeElement($coursEnseignant)) {
            // set the owning side to null (unless already changed)
            if ($coursEnseignant->getEnseignant() === $this) {
                $coursEnseignant->setEnseignant(null);
            }
        }

        return $this;
    }

    public function __toString() {
	return $this->getNom().'-'.$this->getPrenom();
    }

    public function isArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }
}
