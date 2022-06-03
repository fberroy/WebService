<?php

namespace App\Entity;

use App\Repository\UERepository;
use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UERepository::class)
 */
class UE
{
	const STATUT = ['Obligatoire','Facultatif'];
	const FORMATION = ['Licence','Master MIAGE','Master Informatique'];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $Intitule;

   /**
	* @ORM\Column(type="string", length=100)
	* @Assert\Choice(choices=UE::FORMATION, message="A quelle formation appartient ce cours")
	*/
    private $formation;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $semestre;

    /**
	* @ORM\Column(type="string", length=100)
	* @Assert\Choice(choices=UE::STATUT, message="Cours obligatoire ou facultatif ?")
	*/
    private $statut;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $effectif;

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="Ue")
     */
    private $cours;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->Intitule;
    }

    public function setIntitule(string $Intitule): self
    {
        $this->Intitule = $Intitule;

        return $this;
    }

    public function getFormation(): ?string
    {
        return $this->formation;
    }



    public function setFormation(?string $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getSemestre(): ?string
    {
        return $this->semestre;
    }

    public function setSemestre(?string $semestre): self
    {
        $this->semestre = $semestre;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getEffectif(): ?int
    {
        return $this->effectif;
    }

    public function setEffectif(?int $effectif): self
    {
        $this->effectif = $effectif;

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours[] = $cour;
            $cour->setUe($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getUe() === $this) {
                $cour->setUe(null);
            }
        }

        return $this;
    }

    public function __toString() {
	return $this->getIntitule();
    }
}
