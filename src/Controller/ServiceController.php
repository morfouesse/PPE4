<?php

namespace App\Controller;


use App\Entity\Pack;
use App\Entity\User;
use App\Entity\Service;
use App\Entity\ServiceDuUser;
use App\Manager\ServiceManager;
use App\Manager\ServiceDuUserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServiceController extends AbstractController
{

     /**
     * @Route("/choisirService/sansPack/{idPack}", name="listeServiceSansPack")
     */
    public function ListeServiceSansPack($idPack,ServiceDuUserManager $sum,ObjectManager $om)
    {
        // id du pack aucun qui correspond donc a aucun pack
        $idPack=11;
        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findByPack($idPack);
       

        $lesServicesDesUsers=$this->getDoctrine()->getRepository(ServiceDuUser::class)->findAll();
         
        foreach ($lesServicesDesUsers as $leServiceDuUser) {
            //si l'id du user et l'id du user du service du user est commun alors
            //on l'affiche sous forme de liste
            if($leServiceDuUser->getUser()->getId()==$this->getUser()->getId())
            {
                $lesServicesDuUser[]=$leServiceDuUser;
            }
        }
        if($leServiceDuUser->getUser()->getId()==$this->getUser()->getId())
        {
            $nbServiceDuUser=count($lesServicesDuUser);
            foreach ($lesServicesDesUsers as $leServiceDuUser) {
                $limiteServiceSALARIE=$sum->limiteServiceSALA($leServiceDuUser,$nbServiceDuUser);
                $limiteServiceCADRE=$sum->limiteServiceCADR($leServiceDuUser,$nbServiceDuUser);
              }
        }
        else{
            $limiteServiceSALARIE=False;
            $limiteServiceCADRE=False;
            $lesServicesDuUser=False;
        }
      
        

       
   
        return $this->render('packEtService/listeService.html.twig',array(
            'lesServices'=>$lesServices,
            'limiteServiceSALARIE'=>$limiteServiceSALARIE,
            'limiteServiceCADRE'=>$limiteServiceCADRE,
            'lesServicesDuUser'=>$lesServicesDuUser,
            'idPack'=>$idPack,
        ));
    }

       /**
     * @Route("/choisirService/choixDuService/{idService}", name="choixDuService")
     */
    public function choixDuService($idService,ServiceDuUserManager $sdum,ServiceManager $sm,ObjectManager $om)
    {
        $idPack=11;
        $lesServicesDuPack=$this->getDoctrine()->getRepository(Service::class)->findByPack($idPack);
        //idDuService
        $leServiceId=$this->getDoctrine()->getRepository(Service::class)->findById($idService);
         // avoir l'objet de l'user actuel
        $user= $this->getUser();
        
             //leservice est un tableau,j'ai besoin d'un object service donc je prend l'indice 0 du tableau pour avoir acces a l'object
             $service=$leServiceId[0];
             //ajoute le service  du user
             $sdum->addServiceUser($service,$user,$om);

        // pour comparer les services du user aux services
        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findAll();
        $lesServicesDuUser=$this->getDoctrine()->getRepository(ServiceDuUser::class)->findAll();
        foreach($lesServices as $leService)
        {
            foreach ($lesServicesDuUser as  $leServiceDuUser) 
            {
               
                $sm->serviceActif($leService,$leServiceDuUser,$om);    
              
                   
            }
          }
     
        //pour afficher la nouvelle liste de service du user
        $lesServicesDuUser=$this->getDoctrine()->getRepository(ServiceDuUser::class)->findAll();
        return $this->redirectToRoute('vosServices');
    }

      /**
     * @Route("/choisirService/supprimmerService/{idServicesDuUser}", name="deleteService")
     */
    public function deleteService($idServicesDuUser, ServiceDuUserManager $sum,ObjectManager $om)
    {
       
        $lesServicesDuUser=$this->getDoctrine()->getRepository(ServiceDuUser::class)->findById($idServicesDuUser);
        //obtien l'objet serviceDuUser
        $leServiceDuUser=$lesServicesDuUser[0];
        //pour changer l'attribut "actif" pour le remetre a false j'ai besoin de l'objet Service
        $service=$lesServicesDuUser[0]->getService();

        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findAll();
        $lesPacks=$this->getDoctrine()->getRepository(Pack::class)->findAll();
   

        $sum->deleteServiceUser($service,$leServiceDuUser,$lesServices,$lesPacks,$om);
        
        return $this->redirectToRoute('vosServices');
       
       
    }

    /**
     * @Route("/choisirService/vosServices", name="vosServices")
     */
    public function vosServices(ServiceDuUserManager $sum,ObjectManager $om)
    {
        $lesServicesDesUsers=$this->getDoctrine()->getRepository(ServiceDuUser::class)->findAll();
        //pour avoir acces au attribut de service
        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findAll();
        // initialise des variables
        $lesServicesDuUser=array();
        foreach ($lesServicesDesUsers as $leServiceDuUser) {
               //desactive le service du user apres 1 mois
                 // regarder plus tard cette methode
             //  $sum->desactiveServiceDuUserApresUnMois($leServiceDuUser,$om);
             $sum->desactiveServiceDuUser($leServiceDuUser,$om);
         
            
            //si l'id du user et l'id du user du service du user est commun alors
            //on l'affiche sous forme de liste
            if($leServiceDuUser->getUser()->getId()==$this->getUser()->getId())
            {
                $lesServicesDuUser[]=$leServiceDuUser;
            } 
        }
            foreach($lesServicesDuUser as $leServiceDuUser)
            {
                if($leServiceDuUser->GetnbDroitUtiliser() > $leServiceDuUser->Getservice()->GetnbDroit())
                {
                    return $this->redirectToRoute('payerOuEnleverService');
                } 
            }
          //pour redirection
        $idPack=11; 
        $lesServicesDuPack=$this->getDoctrine()->getRepository(Service::class)->findByPack($idPack);

        return $this->render('packEtService/choixDesService.html.twig',[
            'lesServicesDuUser'=>$lesServicesDuUser,
            'idPack'=>$idPack,
            
        ]);
    }

      /**
     * @Route("/choisirService/vosServices/utiliserceService/{idServiceDuUser}", name="ceService")
     */
    public function ceService($idServiceDuUser,ServiceDuUserManager $sum,ObjectManager $om)
    {
        $serviceDuUser=$this->getDoctrine()->getRepository(ServiceDuUser::class)->findById($idServiceDuUser);
         //objet serviceDuUser
         $leServiceDuUser=$serviceDuUser[0];
        $sum->actifServiceDuUser($leServiceDuUser,$om);
        $sum->nbServiceUtiliser($leServiceDuUser,$om);

        return $this->redirectToRoute('vosServices');

    }

    /**
     * @Route("/choisirService/vosServices/payerOuEnlever", name="payerOuEnleverService")
     */
    public function payerOuEnlever(ServiceDuUserManager $sum)
    {
        $lesServicesDesUsers=$this->getDoctrine()->getRepository(ServiceDuUser::class)->findAll();
        $userId=$this->getUser()->getId();
         //pour avoir acces au attribut de service
        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findAll();

        $lesServicesDuUserDepasse=$sum->lesServicesDuUserDepasse($lesServicesDesUsers,$userId);
       
     
        return $this->render('packEtService/payerOuEnlever.html.twig',[
            'lesServicesDuUserDepasse'=>$lesServicesDuUserDepasse,
        ]);

    }


      /**
     * @Route("/choisirService/vosServices/choix", name="attentionChoix")
     */
    public function choix(ServiceDuUserManager $sum,ObjectManager $om)
    {
        $lesServicesDesUsers=$this->getDoctrine()->getRepository(ServiceDuUser::class)->findAll();
        //pour la methode nbdroitDepasse pour avoir le nombre de droit de chaque service reserver à chaque user
        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findAll();
        // initialise des variables
        $lesServicesDuUser=array();
       // $nbDroitDepassePaye=0;
       // $reutiliserService=0;
      
        foreach ($lesServicesDesUsers as $leServiceDuUser) {
            //si l'id du user et l'id du user du service du user est commun alors
            //on l'affiche sous forme de liste
            if($leServiceDuUser->getUser()->getId()==$this->getUser()->getId())
            {
                
                $lesServicesDuUser[]=$leServiceDuUser;
              
            }
          
        }
        foreach ($lesServicesDuUser as $leServiceDuUser)
         {
        
                $sum->nbDroitDepassePaye($leServiceDuUser,$om);
                $sum->reutiliserService($leServiceDuUser,$om);
               
            
        }
           
        return $this->render('packEtService/choixDuUser.html.twig',[
            'lesServicesDuUser'=>$lesServicesDuUser,
            
          
        ]);
      
    }


   
      
}
