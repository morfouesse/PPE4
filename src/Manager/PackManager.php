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

    public function ActifPack($lePack,$serviceId,$om)
    {
         // si l'id du pack correspond à l'id du pack du service alors le pack est activé
         if($lePack==$serviceId)
         {
             $serviceActif=$lePack->setActif(1);
             $om->flush();  
         }
    }
   



    





}