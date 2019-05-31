<?php

namespace App\Controller;


use App\Entity\Pack;
use App\Entity\Service;
use App\Entity\Entreprise;
use App\Form\CreatePackType;
use App\Form\CreateServiceType;
use App\Manager\ServiceManager;
use App\Entity\ServiceDuneEntreprise;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminGestionServiceController extends AbstractController
{

  
    /**
     * @Route("/gestion/ajouter/addService", name="addService")
     */
    public function addServiceAndList(Request $request,ServiceManager $sm,ObjectManager $om)
    {
     
        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findAll();

        
        $form= $this->createForm(CreateServiceType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $data=$form->getData();
            $pack=$data->getPack();
            $sm->AddService($data,$om,$pack);
            return $this->redirectToRoute('addService');
        }
        
        return $this->render('Admin/gestionDesServices.html.twig',[
            'form' =>$form->createView(),'lesServices'=>$lesServices
            ]);
     
    }

  

       /**
     * @Route("/gestion/supprimer/deleteService/{id}", name="deleteServiceAdmin")
     */
    public function deleteService($id,ServiceManager $sm,ObjectManager $om)
    {
        $leService=$this->getDoctrine()->getRepository(Service::class)->findOneById($id);
        $sm->DeleteService($leService,$om);    
        return $this->redirectToRoute('addService');
     
        return $this->render('Admin/gestionDesServices.html.twig');
    }

     /**
     * @Route("/gestion/changer/changeService/{id}", name="changeServiceAdmin")
     */
    public function changePack($id,Request $request,ServiceManager $sm,ObjectManager $om)
    {
   
        $leService=$this->getDoctrine()->getRepository(Service::class)->findOneById($id);  
        $form2= $this->createForm(CreateServiceType::class);
        $form2->handleRequest($request);
        if ($form2->isSubmitted() && $form2->isValid())
        {
            $data=$form2->getData();
            $pack=$data->getPack();
            $sm->ChangeService($leService,$om,$data,$pack);  
            return $this->redirectToRoute('addService');
        }
     
        return $this->render('Admin/modifierUnService.html.twig',[
            'form2' =>$form2->createView()]);
   
    }

     /**
     * @Route("/gestion/serviceInutilise", name="serviceInutilise")
     */
    public function lesServicesInutilises(ServiceManager $sm)
    {
        //tous les services
        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findAll();
        // appelle la méthode pour avoir les services inutilisés
        $lesServicesInutilises=$sm->lesServicesInutilises($lesServices);
      
       

        return $this->render('Admin/lesServicesInutilises.html.twig',[
            'lesServicesInutilises'=>$lesServicesInutilises
        ]);
          
    }

     /**
     * @Route("/gestion/supprimmerlesServicesInutilises", name="supprimmerlesServicesInutilises")
     */
    public function supprimmerlesServicesInutilises(ServiceManager $sm,ObjectManager $om)
    {   // la liste des services des entreprises
        $lesServicesDesEntreprises=$this->getDoctrine()->getRepository(ServiceDuneEntreprise::class)->findAll();
        // on a besoin de ce parametre pour la methode
        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findAll();
          // appelle la méthode pour supprimer tous les services inutilises
        $supprimentLesServiceInutilises=$sm->supprimentLesServiceInutilises($lesServicesDesEntreprises,$lesServices,$om);

        return $this->redirectToRoute('serviceInutilise');
    }

}