<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 */
class Service
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
     * @ORM\Column(type="integer")
     */
    private $nbDroit;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pack", inversedBy="service")
     */
    private $pack;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ServiceDuUser", mappedBy="service")
     */
    private $serviceDuUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ServiceDuneEntreprise", mappedBy="service")
     */
    private $serviceDuneEntreprises;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $serviceInutilise;

  


    public function __construct()
    {
        $this->serviceDuUsers = new ArrayCollection();
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

    public function getNbDroit(): ?int
    {
        return $this->nbDroit;
    }

    public function setNbDroit(int $nbDroit): self
    {
        $this->nbDroit = $nbDroit;

        return $this;
    }


    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): self
    {
        $this->pack = $pack;

        return $this;
    }

    /**
     * @return Collection|ServiceDuUser[]
     */
    public function getServiceDuUsers(): Collection
    {
        return $this->serviceDuUsers;
    }

    public function addServiceDuUser(ServiceDuUser $serviceDuUser): self
    {
        if (!$this->serviceDuUsers->contains($serviceDuUser)) {
            $this->serviceDuUsers[] = $serviceDuUser;
            $serviceDuUser->setService($this);
        }

        return $this;
    }

    public function removeServiceDuUser(ServiceDuUser $serviceDuUser): self
    {
        if ($this->serviceDuUsers->contains($serviceDuUser)) {
            $this->serviceDuUsers->removeElement($serviceDuUser);
            // set the owning side to null (unless already changed)
            if ($serviceDuUser->getService() === $this) {
                $serviceDuUser->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ServiceDuneEntreprise[]
     */
    public function getServiceDuneEntreprises(): Collection
    {
        return $this->serviceDuneEntreprises;
    }

    public function addServiceDuneEntreprise(ServiceDuneEntreprise $serviceDuneEntreprise): self
    {
        if (!$this->serviceDuneEntreprises->contains($serviceDuneEntreprise)) {
            $this->serviceDuneEntreprises[] = $serviceDuneEntreprise;
            $serviceDuneEntreprise->setService($this);
        }

        return $this;
    }

    public function removeServiceDuneEntreprise(ServiceDuneEntreprise $serviceDuneEntreprise): self
    {
        if ($this->serviceDuneEntreprises->contains($serviceDuneEntreprise)) {
            $this->serviceDuneEntreprises->removeElement($serviceDuneEntreprise);
            // set the owning side to null (unless already changed)
            if ($serviceDuneEntreprise->getService() === $this) {
                $serviceDuneEntreprise->setService(null);
            }
        }

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getServiceInutilise(): ?string
    {
        return $this->serviceInutilise;
    }

    public function setServiceInutilise(string $serviceInutilise): self
    {
        $this->serviceInutilise = $serviceInutilise;

        return $this;
    }

   

   
}
