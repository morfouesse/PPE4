<?php

namespace App\Util;

use App\Entity\Pack;
use App\Entity\Service;




class ServiceDuUserUtil{


    public function addServiceUser(Service $service,User $user,$om){
        $serviceUser= new ServiceDuUser();
          // partie pour ajouter un service à un user
          $tarif=0;
        $serviceUser->setTarif($tarif);
        $serviceUser->setService($service);
        $serviceUser->setUser($user);
        $serviceUser->setStatutDuServiceDuUser(0);
        $serviceUser->setNbDroitUtiliser(0);
        $serviceUser->setDateUtilisationService(null);
        $om->persist($serviceUser);
        $om->flush();
    }

   
    





}
