<?php

namespace App\Entity;

use App\Repository\CoursEnseignantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CoursEnseignantRepository::class)
 */
class CoursEnseignant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Voeux;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Enseigne;

    /**
     * @ORM\ManyToOne(targetEntity=Enseignant::class, inversedBy="coursEnseignants")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\JoinColumn(nullable=false)

     */
    private $Enseignant;

    /**
     * @ORM\ManyToOne(targetEntity=Cours::class, inversedBy="coursEnseignants")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Cours;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbHeuresAtt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbGroupesAtt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $Validation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anneeVoeux;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVoeux(): ?bool
    {
        return $this->Voeux;
    }

    public function setVoeux(bool $Voeux): self
    {
        $this->Voeux = $Voeux;

        return $this;
    }

    public function getEnseigne(): ?bool
    {
        return $this->Enseigne;
    }

    public function setEnseigne(bool $Enseigne): self
    {
        $this->Enseigne = $Enseigne;

        return $this;
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->Enseignant;
    }

    public function setEnseignant(?Enseignant $Enseignant): self
    {
        $this->Enseignant = $Enseignant;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->Cours;
    }

    public function setCours(?Cours $Cours): self
    {
        $this->Cours = $Cours;

        return $this;
    }

    public function getNbHeuresAtt(): ?int
    {
        return $this->nbHeuresAtt;
    }

    public function setNbHeuresAtt(?int $nbHeuresAtt): self
    {
        $this->nbHeuresAtt = $nbHeuresAtt;

        return $this;
    }

    public function getNbGroupesAtt(): ?int
    {
        return $this->nbGroupesAtt;
    }

    public function setNbGroupesAtt(?int $nbGroupesAtt): self
    {
        $this->nbGroupesAtt = $nbGroupesAtt;

        return $this;
    }

    public function isValidation(): ?bool
    {
        return $this->Validation;
    }

    public function setValidation(?bool $Validation): self
    {
        $this->Validation = $Validation;

        return $this;
    }

    public function getAnneeVoeux(): ?int
    {
        return $this->anneeVoeux;
    }

    public function setAnneeVoeux(?int $anneeVoeux): self
    {
        $this->anneeVoeux = $anneeVoeux;

        return $this;
    }
}
