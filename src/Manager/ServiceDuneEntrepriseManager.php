<?php

namespace App\Manager;

use App\Entity\ServiceDuneEntreprise;







class ServiceDuneEntrepriseManager
{
    public function AddServiceCompany($uneEntreprise,$data,$om){
        $ServiceDuneEntreprise= new ServiceDuneEntreprise();
        $ServiceDuneEntreprise->setService($data->getService());
        $ServiceDuneEntreprise->setEntreprise($uneEntreprise);
        $om->persist($ServiceDuneEntreprise);
        $om->flush();
    }

    public function ModifyServiceCompany($unServiceDuneEntreprise,$uneEntreprise,$data,$om){
        $unServiceDuneEntreprise->setService($data->getService());
        $unServiceDuneEntreprise->setEntreprise($uneEntreprise);
        $om->persist($unServiceDuneEntreprise);
        $om->flush();
    }

    
    public function DeleteServiceCompany($unServiceDuneEntreprise,$om){
        $om->remove($unServiceDuneEntreprise);
        $om->flush();
    }


}