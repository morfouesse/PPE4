<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntrepriseRepository")
 */
class Entreprise
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ServiceDuneEntreprise", mappedBy="entreprise")
     */
    private $serviceDuneEntreprises;


   

    public function __construct()
    {
        $this->packs = new ArrayCollection();
        $this->serviceDuneEntreprises = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|ServiceDuneEntreprises[]
     */
    public function getServiceDuneEntreprises(): Collection
    {
        return $this->serviceDuneEntreprises;
    }

    public function addServiceDuneEntreprises(ServiceDuneEntreprise $serviceDuneEntreprises): self
    {
        if (!$this->serviceDuneEntreprises->contains($serviceDuneEntreprises)) {
            $this->serviceDuneEntreprises[] = $serviceDuneEntreprises;
            $serviceDuneEntreprises->setEntreprise($this);
        }

        return $this;
    }

    public function removeServiceDuneEntreprise(ServiceDuneEntreprise $serviceDuneEntreprises): self
    {
        if ($this->serviceDuneEntreprises->contains($serviceDuneEntreprises)) {
            $this->serviceDuneEntreprises->removeElement($serviceDuneEntreprises);
            // set the owning side to null (unless already changed)
            if ($serviceDuneEntreprises->getEntreprise() === $this) {
                $serviceDuneEntreprises->setEntreprise(null);
            }
        }

        return $this;
    }

   


 
}
