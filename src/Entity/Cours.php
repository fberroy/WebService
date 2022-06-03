<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CoursRepository::class)
 */
class Cours
{
    const COURS = ['CM', 'TD', 'TP'];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $nomCours;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\PositiveOrZero
     */
    private $nbHeures;

   /**
 * @ORM\Column(type="string", length=2)
 * @Assert\Choice(choices=COURS::COURS, message="Choissisez un type de cours valide TP, TD ou CM!")

 */
    private $typeCours;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\PositiveOrZero
     * @Assert\LessThan(
     *     value = 20
     * )
     */
    private $NbEnseignants;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\PositiveOrZero
      * @Assert\LessThan(
     *     value = 20
     * )
     */
    private $nbGroupes;

    /**
     * @ORM\ManyToOne(targetEntity=UE::class, inversedBy="cours")
     */
    private $Ue;

    /**
     * @ORM\OneToMany(targetEntity=CoursEnseignant::class, mappedBy="Cours")
     */
    private $coursEnseignants;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomGroupe;

    public function __construct()
    {
        $this->enseignants = new ArrayCollection();
        $this->coursEnseignants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCours(): ?string
    {
        return $this->nomCours;
    }

    public function setNomCours(string $nomCours): self
    {
        $this->nomCours = $nomCours;

        return $this;
    }

    public function getNbHeures(): ?int
    {
        return $this->nbHeures;
    }

    public function setNbHeures(?int $nbHeures): self
    {
        $this->nbHeures = $nbHeures;

        return $this;
    }

    public function getTypeCours(): ?string
    {
        return $this->typeCours;
    }

    public function setTypeCours(?string $typeCours): self
    {
        $this->typeCours = $typeCours;

        return $this;
    }

    public function getNbEnseignants(): ?int
    {
        return $this->NbEnseignants;
    }

    public function setNbEnseignants(int $NbEnseignants): self
    {
        $this->NbEnseignants = $NbEnseignants;

        return $this;
    }

    public function getNbGroupes(): ?int
    {
        return $this->nbGroupes;
    }

    public function setNbGroupes(int $nbGroupes): self
    {
        $this->nbGroupes = $nbGroupes;

        return $this;
    }

    public function getUe(): ?UE
    {
        return $this->Ue;
    }

    public function setUe(?UE $Ue): self
    {
        $this->Ue = $Ue;

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
            $coursEnseignant->setCours($this);
        }

        return $this;
    }

    public function removeCoursEnseignant(CoursEnseignant $coursEnseignant): self
    {
        if ($this->coursEnseignants->removeElement($coursEnseignant)) {
            // set the owning side to null (unless already changed)
            if ($coursEnseignant->getCours() === $this) {
                $coursEnseignant->setCours(null);
            }
        }

        return $this;
    }

    public function __toString() {
	return $this->getNomCours().'-'.$this->getTypeCours();
    }

    public function getNomGroupe(): ?string
    {
        return $this->nomGroupe;
    }

    public function setNomGroupe(?string $nomGroupe): self
    {
        $this->nomGroupe = $nomGroupe;

        return $this;
    }

}
