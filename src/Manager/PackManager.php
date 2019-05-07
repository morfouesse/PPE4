<?php

namespace App\Manager;

use App\Entity\Pack;




class PackManager
{

  
    public function AddPack($data,$om)
    {
        $pack=new Pack();
        $pack->setNom($data->getNom());
        $pack->setActif(0);
        $om->persist($pack);
        $om->flush();
    }

    public function DeletePack($lePack,$om)
    {
       $om->remove($lePack);
       $om->flush();
    }

    public function ChangePack($lePack,$om,$data)
    {
        $lePack->setNom($data->getNom());
        $om->flush();
    }

    public function ActifPack($lePack,$servicesIdDuPack,$om)
    {
        $actif=0;
         // si l'id du pack correspond à l'id du pack du service alors le pack est activé
         if($lePack->getId()==$servicesIdDuPack)
         {
            $actif=1;
             $packActif=$lePack->setActif($actif);
             $om->flush();  
         }
        return $actif;
    }

    public function ServiceDuPackActif($lesServices,$lePack,$servicesIdDuPack,$om)
    {
        $actif=0;
         //si le pack est activé alors ces services aussi
         if($this->ActifPack($lePack,$servicesIdDuPack,$om)== 1)
         {
            foreach($lesServices as $leService)
            {
                $actif=1;
                $leService->setActif($actif);
                $om->flush();  
            }
         }
        return $actif;
    }

    public function ServiceDuUserAvecPack($sum,$user,$lesServices,$lePack,$servicesIdDuPack,$om)
    {

         //si les services du pack sont activé alors on les ajoutent au services du user
         if($this->ServiceDuPackActif($lesServices,$lePack,$servicesIdDuPack,$om)== 1)
         {
            foreach($lesServices as $leService)
            {
            $service=$leService;
             $sum->addServiceUser($service,$user,$om);
            }
         }
      
    }
   



    





}