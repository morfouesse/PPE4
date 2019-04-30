<?php

namespace App\Manager;

use App\Entity\Entreprise;




class EntrepriseManager
{

  
    public function AddCompany($data,$om)
    {
        $Entreprise=new Entreprise();
        $Entreprise->setNom($data->getNom());
        $om->persist($Entreprise);
        $om->flush();
    }

    public function DeleteCompany($uneEntreprise,$om)
    {
       $om->remove($uneEntreprise);
       $om->flush();
    }

    public function ChangeCompany($uneEntreprise,$om,$data)
    {
        $uneEntreprise->setNom($data->getNom());
        $om->flush();
    }
   



    





}