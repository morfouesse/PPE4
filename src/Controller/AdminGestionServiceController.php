<?php

namespace App\Controller;


use App\Entity\Pack;
use App\Entity\Service;
use App\Form\CreatePackType;
use App\Form\CreateServiceType;
use App\Manager\ServiceManager;
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

   

}