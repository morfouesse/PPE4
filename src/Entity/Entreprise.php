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
    private $serviceDuneEntreprise;


   

    public function __construct()
    {
        $this->packs = new ArrayCollection();
        $this->serviceDuneEntreprise = new ArrayCollection();
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
     * @return Collection|ServiceDuneEntreprise[]
     */
    public function getServiceDuneEntreprise(): Collection
    {
        return $this->serviceDuneEntreprise;
    }

    public function addServiceDuneEntreprise(ServiceDuneEntreprise $serviceDuneEntreprise): self
    {
        if (!$this->serviceDuneEntreprise->contains($serviceDuneEntreprise)) {
            $this->serviceDuneEntreprise[] = $serviceDuneEntreprise;
            $serviceDuneEntreprise->setEntreprise($this);
        }

        return $this;
    }

    public function removeServiceDuneEntreprise(ServiceDuneEntreprise $serviceDuneEntreprise): self
    {
        if ($this->serviceDuneEntreprise->contains($serviceDuneEntreprise)) {
            $this->serviceDuneEntreprise->removeElement($serviceDuneEntreprise);
            // set the owning side to null (unless already changed)
            if ($serviceDuneEntreprise->getEntreprise() === $this) {
                $serviceDuneEntreprise->setEntreprise(null);
            }
        }

        return $this;
    }

   


 
}
