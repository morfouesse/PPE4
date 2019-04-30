<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Service;
use App\Entity\ServiceDuUser;
use DateTime;





class ServiceDuUserManager
{
    public function addServiceUser(Service $service,User $user,$om){
        $serviceUser= new ServiceDuUser();
          // partie pour ajouter un service à un user
          $tarif=0;
        $serviceUser->setTarif($tarif);
        $serviceUser->setService($service);
        $serviceUser->setUser($user);
        $serviceUser->setStatutDuServiceDuUser(0);
        $serviceUser->setNbDroitUtiliser(0);
        $serviceUser->setDateStrDuService(null);
        $om->persist($serviceUser);
        $om->flush();
    }

    public function deleteServiceUser($service,$leServiceDuUser,$om){
        $service->setActif(0);
        $om->remove($leServiceDuUser);
        $om->flush();
    }

    public function actifServiceDuUser($leServiceDuUser,$om){

        $leServiceDuUser->setStatutDuServiceDuUser(1);
        $dateActuelleSTR=date("Y-m-d");
        $leServiceDuUser->setDateStrDuService($dateActuelleSTR);
        $om->flush();
        return $leServiceDuUser;   
    }

  
    public function nbServiceUtiliser($leServiceDuUser,$om){
       
        $nbUtilisation=0;
       if($this->actifServiceDuUser($leServiceDuUser,$om))
       {
          //cumule le nombre de droit du service utiliser
           $nbUtilisation=$leServiceDuUser->setNbDroitUtiliser($leServiceDuUser->getNbDroitUtiliser()+1);
           $om->flush();  
       }
       return $nbUtilisation;
    }

    //limite le droit de reserver des services du user SALA a 5 et celui du CADRE a 10
    public function limiteServiceSALA($leServiceDuUser,$nbServiceDuUser)
    { 
      $VerifSALA=False;
      //  si c'est un salarier et que le nombre de service du user est supérieur à 4
      if($leServiceDuUser->getUser()->getTypeUser()=="SALA" && $nbServiceDuUser>4)
      {
        $VerifSALA=False;
      }
      else{
        $VerifSALA=True;
      }
     return $VerifSALA;
    }
  //limite le droit de reserver des services du user SALA a 5 et celui du CADRE a 10
    public function limiteServiceCADR($leServiceDuUser,$nbServiceDuUser)
    { 
      $VerifCADR=False;
      if($leServiceDuUser->getUser()->getTypeUser()=="CADR" && $nbServiceDuUser>9)
      {
        $VerifCADR=False;
      }
      else{
        $VerifCADR=True;
      }
     return $VerifCADR;
    }

      // desactiver le service du user  actif apres 1 jour 
    public function desactiveServiceDuUser($leServiceDuUser,$om)
    {//statut active
      if($leServiceDuUser->getStatutDuServiceDuUser()==1)
      {
        //date en string
        $dateActuelleSTR=date("Y-m-d");
        //date actuelle
        $dateActuelle = date_create($dateActuelleSTR);
        //date du service du user en string
        $dateDuServiceSTR=$leServiceDuUser->getDateStrDuService();
        //date transformer en timestamp pour ajouter un jour 
        $dateFinTimestamp= strtotime(date("Y-m-d", strtotime($dateDuServiceSTR)) . " +1 day");
        // transforme le timestamp en string
        $dateDuServicePlusUnJourSTR=date("Y-m-d",$dateFinTimestamp);
          //date du service du user plus un jour
          $dateDuServicePlusUnJour = date_create($dateDuServicePlusUnJourSTR);
       
          
         if($dateActuelle==$dateDuServicePlusUnJour){
            $leServiceDuUser->setStatutDuServiceDuUser(0);
            $leServiceDuUser->setDateStrDuService(null);
            $om->flush();
         } 
      }
      return $leServiceDuUser;
    }

      //si le nombtre de droit d'utilisation d'un service est dépassé alors on enleve le service du user
      public function nbDroitDepasseSupprimme($lesServices,$lesServicesDuUser,$om)
    {
      foreach ($lesServicesDuUser as $leServiceDuUser) {
          // nombre de droit du service 
          $nombreDroitDuService=$leServiceDuUser->getService()->getNbDroit();
          //nombre de droit utiliser
          $nbDroitUtiliser= $leServiceDuUser->getNbDroitUtiliser();
         if($nbDroitUtiliser>$nombreDroitDuService)
         {
           $service=$leServiceDuUser->getService();
            $this->deleteServiceUser($service,$leServiceDuUser,$om);
         }
      }
    }
     //si le nombtre de droit d'utilisation d'un service est dépassé alors il devra payer une somme pour l'utiliser encore une fois
      public function nbDroitDepassePaye($lesServices,$lesServicesDuUser,$om)
    {
      foreach ($lesServicesDuUser as $leServiceDuUser) {
          // nombre de droit du service 
          $nombreDroitDuService=$leServiceDuUser->getService()->getNbDroit();
          //nombre de droit utiliser
          $nbDroitUtiliser= $leServiceDuUser->getNbDroitUtiliser();
            if($nbDroitUtiliser>$nombreDroitDuService){
              $tarif=$leServiceDuUser->getTarif();
              //on ajoute 100 au tarif si le user choisie de payer
              $tarif+=100;
              // sa lui permet d'utiliser encore une fois le service
              $nbDroitUtiliser-=1; 
            }
      }
    }
  

      //si leServiceActiveDuUser est activer et si un mois est passé, alors le service du user est désactivé
      // regarder plus tard
      public function desactiveServiceDuUserApresUnMois($leServiceDuUser,$om)
      {

        $final = date("Y-m-d", strtotime("+1 month", $now='time()'));
        $dateFin= date('j-M-Y');
        //ajoute un mois
        $dateFin->modify('+1 month');
        $dateActuel=  date("Y-m-d");
        dump($dateFin);
        dump($dateActuel);die;
        //tant que la date debut n'est pas égale à la date fin alors on 
          while($dateActuel!=$dateFin)
            {
              // ecrase la date actuel pour boucler sur la date actuel pour un jour finir sur la meme date que la date de fin
              $dateActuel= DateTime::createFromFormat('j-M-Y');
             
            
             dump($dateActuel);die;
            }
              if($duree==$dateFin && $leServiceDuUser->getStatutDuServiceDuUser()==1)
              {
                $leServiceDuUser->setStatutDuServiceDuUser(0);
                $leServiceDuUser->setDateUtilisationService(null);
                $om->flush();
              
              }   
              return $leServiceDuUser;
        }
     



   
}
