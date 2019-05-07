<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServiceDuUserRepository")
 */
class ServiceDuUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

  

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="serviceDuUsers")
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="serviceDuUsers")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statutDuServiceDuUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbDroitUtiliser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dateStrDuService;

    /**
     * @ORM\Column(type="float")
     */
    private $tarif;

  


  

  

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatutDuServiceDuUser(): ?bool
    {
        return $this->statutDuServiceDuUser;
    }

    public function setStatutDuServiceDuUser(bool $statutDuServiceDuUser): self
    {
        $this->statutDuServiceDuUser = $statutDuServiceDuUser;

        return $this;
    }

    public function getNbDroitUtiliser(): ?int
    {
        return $this->nbDroitUtiliser;
    }

    public function setNbDroitUtiliser(int $nbDroitUtiliser): self
    {

        $this->nbDroitUtiliser = $nbDroitUtiliser;

        return $this;
    }

    public function getDateStrDuService(): ?string
    {
        return $this->dateStrDuService;
    }

    public function setDateStrDuService(?string $dateStrDuService): self
    {
        $this->dateStrDuService = $dateStrDuService;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

 
  

  
}
