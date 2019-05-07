<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields= {"username"},
 * message="Ce nom d'utilisateur est déjà utilié !"
 * )
 */
class User implements UserInterface
{
    CONST TYPE=array(
        'Salarier'=>"SALA",
        "Cadre"=>"CADR"
        
    );
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

  

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeUser;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

  
    /**
     * @ORM\Column(type="json")
    */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ServiceDuUser", mappedBy="user")
     */
    private $serviceDuUsers;

    

    public function __construct()
    {
        $this->serviceDuUsers = new ArrayCollection();
    }

   


    public function getTypeUser(): ?string
    {
        return $this->typeUser;
    }

    public function setTypeUser(string $typeUser): self
    {
        $this->typeUser = $typeUser;

        return $this;
    }


      #neccessaire pour connexion
      /**
       * @see UserInterface
       */
      public function getRoles(): array //renvoi un tableau qui explique le role de 
    {
      $roles = $this->roles;
      // guarantee every user at least has ROLE_USER
      $roles[] = "ROLE_USER";
     
      
      return array_unique($roles);
    }

    public function addRole($roles)
    {
       $roles = strtoupper($roles);
        if(!in_array($roles,$this->roles,true)){
            $this->roles[]=$roles;
        }

        return $this;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials() // obliger de mettrre supprime les données sensibles chez le user
    {
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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
            $serviceDuUser->setUser($this);
        }

        return $this;
    }

    public function removeServiceDuUser(ServiceDuUser $serviceDuUser): self
    {
        if ($this->serviceDuUsers->contains($serviceDuUser)) {
            $this->serviceDuUsers->removeElement($serviceDuUser);
            // set the owning side to null (unless already changed)
            if ($serviceDuUser->getUser() === $this) {
                $serviceDuUser->setUser(null);
            }
        }

        return $this;
    }

   

  
}
