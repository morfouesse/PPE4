<?php

namespace App\Manager;

use App\Entity\Pack;
use App\Entity\Service;




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
   
    



    





}
