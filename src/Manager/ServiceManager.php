<?php

namespace App\Manager;

use App\Entity\Pack;
use App\Entity\Service;
use Doctrine\Common\Collections\ArrayCollection;




class ServiceManager{

    public function AddService($data,$om,Pack $pack)
    {
        $service=new Service();
        $service->setNom($data->getNom());
        $service->setNbDroit($data->getNbDroit());
        $service->setActif(0);
        $service->setPack($pack);
        $om->persist($service);
        $om->flush();
    }

    public function DeleteService($leService,$om)
    {
       $om->remove($leService);
       $om->flush();
    }

    public function ChangeService($leService,$om,$data,Pack $pack)
    {
        $leService->setNom($data->getNom());
        $leService->setNbDroit($data->getNbDroit());
        $leService->setPack($pack);
        $om->flush();
    }

    public function serviceActif($leService,$leServiceDuUser,$om)
    {
       // si l'id du service correspond à l'id du service du user alors le service est activé
       if($leService->getId()==$leServiceDuUser->getService()->getId())
       {
           $serviceActif=$leService->setActif(1);
           $om->flush();  
       }
    }  
   
    //retourne la liste des services inutilisés
    public function lesServicesInutilises($lesServices):ArrayCollection
    {
        // declaration d'une liste vide
        $lesServicesInutilises=new ArrayCollection();
        // on parcourt la liste des services
        foreach($lesServices as $leService)
        {
            // si un service n'est pas actif alors on l'ajoute à la collection des services inutilisés
            if($leService->GetActif()==0)
            {
                $lesServicesInutilises->add($leService);
            }
        }
        return $lesServicesInutilises;
    }

    // supprime tous les services inutilises en fin d'annees
    public function supprimentLesServiceInutilises($lesServicesDesEntreprises,$lesServices,$om)
    {
        // on parcourt la liste des services inutilises
        foreach($this->lesServicesInutilises($lesServices) as $leServiceInutilise)
        {
            foreach($lesServicesDesEntreprises as $unServiceDuneEntreprise)
            {// si un service dune entrepprise a le meme id que le service inutilisé alors on les supprimment
               if($unServiceDuneEntreprise->GetService()->GetId()==$leServiceInutilise->GetId())
               {  
               $om->remove($unServiceDuneEntreprise);
               $om->remove($leServiceInutilise);
               $om->flush();
               }
            }
        }
      
    }

    





}
