<?php

namespace App\Manager;

use DateTime;
use App\Entity\User;
use App\Entity\Service;
use App\Entity\ServiceDuUser;
use Doctrine\Common\Collections\ArrayCollection;





class ServiceDuUserManager
{
    public function addServiceUser(Service $service,User $user,$om){
        $serviceUser= new ServiceDuUser();
          // partie pour ajouter un service à un user
        $serviceUser->setService($service);
        $serviceUser->setUser($user);
        $serviceUser->setTarif(0);
        $serviceUser->setStatutDuServiceDuUser(0);
        $serviceUser->setNbDroitUtiliser(0);
        $serviceUser->setDateStrDuService(null);
        $om->persist($serviceUser);
        $om->flush();
    }

    public function deleteServiceUser($service,$leServiceDuUser,$lesServices,$lesPacks,$om){
        $service->setActif(0);
        $om->flush();
        
        $lesServicesDunPack=array();
        $lesServicesDesactiver=array();
        foreach($lesServices as $leService)
        {
          if($leService->getPack()->getActif()== 1)
          { //collection des services qui appartiennent à un pack
            $lesServicesDunPack[]=$leService;
          }
        }  
        foreach($lesServices as $leService)
        {// si l'id du pack est différent de 11(auncun pack) et que le service n'est pas actif
            if($leService->getPack()->getActif()== 1 && $leService->getActif() == 0)
            {// collection des services désactiver
              $lesServicesDesactiver[]=$leService;    
            }
        }
        // compte le nombre de services dans un pack
        $nbServicesDunPack=count($lesServicesDunPack);
        // compte les services desactiver
        $nbServices=count($lesServicesDesactiver);
      
          // si le nombre de services du pack non actif est égale au nombre de services present dans un pack
                      // alors on desactive le pack
        foreach ($lesPacks as $lePack) 
        {
          if($nbServices == $nbServicesDunPack)
            {
              $lePack->setActif(0);
            } 
        }
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
        $VerifSALA=True;
      }
      else{
        $VerifSALA=False;
      }
     return $VerifSALA;
    }
  //limite le droit de reserver des services du user SALA a 5 et celui du CADRE a 10
    public function limiteServiceCADR($leServiceDuUser,$nbServiceDuUser)
    { 
      $VerifCADR=False;
      if($leServiceDuUser->getUser()->getTypeUser()=="CADR" && $nbServiceDuUser>9)
      {
        $VerifCADR=True;
      }
      else{
        $VerifCADR=False;
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
           
         if($dateActuelle>=$dateDuServicePlusUnJour){
            $leServiceDuUser->setStatutDuServiceDuUser(0);
            $leServiceDuUser->setDateStrDuService(null);
            $om->flush();
         } 
      }
      return $leServiceDuUser;
    }

     //si le nombtre de droit d'utilisation d'un service est dépassé alors il devra payer une somme pour l'utiliser encore une fois
      public function nbDroitDepassePaye($leServiceDuUser,$om)
    { 
          // nombre de droit du service 
          $nombreDroitDuService=$leServiceDuUser->getService()->getNbDroit();
          //tarif du service du user
          $tarif=$leServiceDuUser->getTarif(); 
          //nombre de droit utiliser
          $nbDroitUtiliser= $leServiceDuUser->getNbDroitUtiliser();
            if($nbDroitUtiliser>$nombreDroitDuService)
            {
              $tarif=$tarif+100;
              $leServiceDuUser->setTarif($tarif);
              $om->flush();     
            }
      
      return $tarif;
    }

    //si le user a payer alors on enlevre un droit utiliser pour qu'il puisse le  réutiliser une fois
    public function reutiliserService($leServiceDuUser,$om)
    {
     $res=0;
      if($leServiceDuUser->getStatutDuServiceDuUser()== 1)
      {
         
        //nombre de droit utiliser
        $nbDroitUtiliser= $leServiceDuUser->getNbDroitUtiliser();
        // sa lui permet d'utiliser encore une fois le service
        $res=$nbDroitUtiliser-1;  
      
        $changeNbDroitUtiliser=$leServiceDuUser->setNbDroitUtiliser($res);
        $om->flush();
      }
      return $res;
    }

    public function lesServicesDuUserDepasse($lesServicesDesUsers,$userId):ArrayCollection
    {
      $lesServicesDuUser=new ArrayCollection();
      foreach ($lesServicesDesUsers as $leServiceDuUser) {
          //si l'id du user et l'id du user du service du user est commun, ET que le service utilisé par le user a un nombre de droit utiliser
          // supérieur au nombre de droit du service alors
          //on l'affiche sous forme de liste
          if($leServiceDuUser->getUser()->getId()==$userId &&
           $leServiceDuUser->GetnbDroitUtiliser() > $leServiceDuUser->Getservice()->GetnbDroit())
          {
              $lesServicesDuUser->add($leServiceDuUser);
          } 
      }
      return $lesServicesDuUser;
    }
   
  

      //si leServiceActiveDuUser est activer et si un mois est passé, alors le service du user est désactivé
      // regarder plus tard
     /* public function desactiveServiceDuUserApresUnMois($leServiceDuUser,$om)
      {

        $final = date("Y-m-d", strtotime("+1 month", $now='time()'));
        $dateFin= date('j-M-Y');
        //ajoute un mois
        $dateFin->modify('+1 month');
        $dateActuel=  date("Y-m-d");
        //tant que la date debut n'est pas égale à la date fin alors on 
          while($dateActuel!=$dateFin)
            {
              // ecrase la date actuel pour boucler sur la date actuel pour un jour finir sur la meme date que la date de fin
              $dateActuel= DateTime::createFromFormat('j-M-Y');
            }
              if($duree==$dateFin && $leServiceDuUser->getStatutDuServiceDuUser()==1)
              {
                $leServiceDuUser->setStatutDuServiceDuUser(0);
                $leServiceDuUser->setDateUtilisationService(null);
                $om->flush();
              
              }   
              return $leServiceDuUser;
        }*/
     



   
}
